<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\User;
use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;

class EmailController extends Controller
{
    public function inbox()
    {
        $userId = Auth::id();

        $emails = Email::with(['senderUser', 'receiverUser', 'replies.sender'])
            ->where(function ($q) use ($userId) {
                $q->where('receiver_id', $userId)
                  ->orWhere('sender_id', $userId)
                  ->orWhereHas('replies', fn($rq) => $rq->where('receiver_id', $userId)
                                                        ->orWhere('sender_id', $userId));
            })
            ->latest('created_at')
            ->paginate(10);

        return view('emails.inbox', compact('emails'));
    }

    public function sent()
    {
        $emails = Email::with(['senderUser', 'receiverUser'])
            ->where('sender_id', Auth::id())
            ->latest('created_at')
            ->paginate(10);

        return view('emails.sent', compact('emails'));
    }

    public function important()
    {
        $emails = Email::where('tag', 'important')->paginate(15);
        return view('emails.important', compact('emails'));
    }

    public function bin()
    {
        $emails = Email::where('is_deleted', true)->paginate(15);
        return view('emails.bin', compact('emails'));
    }

    public function create()
    {
        $me = auth()->user();

        if ($me->canSeeAllConversations()) {
            // Superadmin / Service client: see ALL companies + users
            $companies = Company::query()
                ->with(['users' => fn($q) => $q->where('is_active', true)->orderBy('name')])
                ->orderBy('name')
                ->get(['id','name']);

            // GS Auto support users (no company)
            $supportUsers = User::supportUsers()
                ->whereNull('company_id')
                ->orderBy('name')
                ->get(['id','name','role']);
        } else {
            // Regular company user: only their company’s users
            $companies = Company::query()
                ->whereKey($me->company_id)
                ->with(['users' => fn($q) => $q->where('is_active', true)->orderBy('name')])
                ->get(['id','name']);

            // Still allow GS Auto support as recipients
            $supportUsers = User::supportUsers()
                ->whereNull('company_id')
                ->orderBy('name')
                ->get(['id','name','role']);
        }

        return view('emails.create', [
            'companies'    => $companies,
            'supportUsers' => $supportUsers,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject'     => 'required|string|max:255',
            'content'     => 'required|string',
            'file'        => 'nullable|file|max:10240',
            'client_id'   => 'nullable|exists:clients,id',
        ]);

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('attachments', 'public');
            $fileName = $request->file('file')->getClientOriginalName();
        }

        Email::create([
            'thread_id'   => null,                 // can be null for standalone email
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'subject'     => $request->subject,
            'content'     => $request->content,
            'folder'      => 'sent',
            'client_id'   => $request->input('client_id'),
            'company_id'  => auth()->user()->company_id,
            'file_path'   => $filePath,
            'file_name'   => $fileName,
        ]);

        return redirect()->route('emails.sent')->with('success', 'Email envoyé.');
    }

    public function show($id)
    {
        $email = Email::with([
            'senderUser',
            'receiverUser',
            'replies.sender',
            'replies.receiver',
        ])->findOrFail($id);

        if ($email->receiver_id == Auth::id()) {
            $email->markAsRead(); // if is_read exists
        }

        return view('emails.show', compact('email'));
    }

    public function destroy(Email $email)
    {
        $email->delete();
        return redirect()->back()->with('success', 'Email supprimé définitivement.');
    }

    public function restore($id)
    {
        $email = Email::findOrFail($id);
        $email->is_deleted = false;
        $email->tag = null;
        $email->label_color = null;
        $email->save();

        return redirect()->route('emails.bin')->with('success', 'Email restauré.');
    }

    public function toggleStar($id)
    {
        $email = Email::findOrFail($id);
        $email->update(['starred' => ! (bool) $email->starred]);

        return back();
    }

    public function permanentDelete($id)
    {
        $email = Email::findOrFail($id);
        $email->delete();

        return back()->with('success', 'Email définitivement supprimé.');
    }

    public function markImportant($id)
    {
        $email = Email::findOrFail($id);
        $email->tag = 'important';
        $email->tag_color = '#facc15';
        $email->save();

        return redirect()->back()->with('success', 'Email marqué comme important.');
    }

    public function toggleImportant($id)
    {
        $email = Email::findOrFail($id);

        if ($email->tag === 'important') {
            $email->tag = null;
            $email->tag_color = null;
        } else {
            $email->tag = 'important';
            $email->tag_color = '#facc15';
        }

        $email->save();

        return redirect()->back()->with('success', 'Email mis à jour.');
    }

    public function moveToTrash($id)
    {
        $email = Email::findOrFail($id);
        $email->is_deleted = true;
        $email->tag = 'bin';
        $email->label_color = '#ef4444';
        $email->save();

        return redirect()->back()->with('success', 'Email déplacé vers la corbeille.');
    }

    public function reply(Request $request, $id)
    {
        $email = Email::findOrFail($id);

        $request->validate([
            'content' => 'required|string',
            'file'    => 'nullable|file|max:2048',
        ]);

        // Determine counterparty: reply goes to "the other side"
        $receiverId = (Auth::id() == $email->receiver_id)
            ? $email->sender_id
            : $email->receiver_id;

        $reply = new Reply([
            'email_id'    => $email->id,
            'thread_id'   => $email->thread_id, // ✅ preserve thread linkage if any
            'sender_id'   => Auth::id(),
            'receiver_id' => $receiverId,
            'content'     => $request->content,
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('attachments', 'public');
            $reply->file_path = $path;
            $reply->file_name = $request->file('file')->getClientOriginalName();
        }

        $reply->save();

        return redirect()->route('emails.show', $email->id)->with('success', 'Réponse envoyée.');
    }

    public function notifications()
    {
        $emails = Email::where('folder', 'inbox')
                   ->where('is_read', false)
                   ->latest('created_at')
                   ->paginate(12);

        $readCount   = Email::where('folder', 'inbox')->where('is_read', true)->count();
        $unreadCount = Email::where('folder', 'inbox')->where('is_read', false)->count();

        return view('emails.notifications', compact('emails', 'readCount', 'unreadCount'));
    }

    public function markAllRead()
    {
        Email::where('folder', 'inbox')->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName   = pathinfo($originName, PATHINFO_FILENAME) . '_' . time() . '.' . $request->file('upload')->getClientOriginalExtension();

            $request->file('upload')->move(public_path('uploads'), $fileName);

            return response()->json([
                'uploaded' => 1,
                'fileName' => $fileName,
                'url'      => asset('uploads/' . $fileName),
            ]);
        }

        return response()->json([
            'uploaded' => 0,
            'error'    => ['message' => 'Upload failed.'],
        ], 400);
    }
}