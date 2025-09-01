<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Reply;
use Illuminate\Support\Facades\Auth;      // ← Add this
use Illuminate\Support\Facades\Storage; 

class EmailController extends Controller
{
    public function inbox()
    {
        $emails = Email::with(['senderUser', 'receiverUser', 'replies.senderUser'])
            ->where('receiver_id', Auth::id())
            ->orWhereHas('replies', function ($query) {
                $query->where('receiver_id', Auth::id());
            })
            ->latest()
            ->paginate(10);

        return view('emails.inbox', compact('emails'));
    }
    
    public function sent()
    {
        $emails = Email::with(['senderUser', 'receiverUser'])
            ->where('sender_id', Auth::id())
            ->latest()
            ->paginate(10);
    
        return view('emails.sent', compact('emails'));
    }

    public function important()
    {
        $emails = Email::where('tag', 'important')
            ->paginate(15); // Changed to paginate

        return view('emails.important', compact('emails'));
    }

    public function bin()
    {
        $emails = Email::where('is_deleted', true)
            ->paginate(15); // Changed to paginate

        return view('emails.bin', compact('emails'));
    }

    public function create()
    {
        // load only those roles you allow (admin, client service, etc.)
        $users = User::where('company_id', auth()->user()->company_id)
        ->get();
    
        return view('emails.create', compact('users'));
    }

 public function store(Request $request)
{
    $request->validate([
        'receiver_id' => 'required|exists:users,id',
        'subject'     => 'required|string',
        'content'     => 'required|string',
        'file'        => 'nullable|file|max:10240',
    ]);

    $filePath = null;
    $fileName = null;

    if ($request->hasFile('file')) {
        $filePath = $request->file('file')->store('attachments', 'public');
        $fileName = $request->file('file')->getClientOriginalName();
    }

    Email::create([
        'sender_id'   => Auth::id(),
        'receiver_id' => $request->receiver_id,
        'subject'     => $request->subject,
        'content'     => $request->content,
        'folder'      => 'sent',
        'client_id'   => $request->input('client_id'),
        'file_path'   => $filePath,
        'file_name'   => $fileName,
    ]);

    return redirect()->route('emails.sent')->with('success', 'Email sent successfully.');
}
    public function show($id)
    {
        $email = Email::with([
            'senderUser',
            'receiverUser',
            'replies.senderUser'
        ])->findOrFail($id);

        if (!$email->is_read && $email->receiver_id == Auth::id()) {
            $email->is_read = true;
            $email->save();
        }

        foreach ($email->replies as $reply) {
            if (!$reply->is_read && $reply->receiver_id == Auth::id()) {
                $reply->is_read = true;
                $reply->save();
            }
        }

        return view('emails.show', compact('email'));
    }

    public function destroy(Email $email)
    {
        $email->delete(); // or forceDelete() if using SoftDeletes
        return redirect()->back()->with('success', 'Email supprimé définitivement.');
    }

    public function restore($id)
    {
        $email = Email::findOrFail($id);
        $email->is_deleted = false;
        $email->tag = null;
        $email->label_color = null;
        $email->save();
    
        return redirect()->route('emails.bin')->with('success', 'Email restored.');
    }

    public function toggleStar($id)
    {
        $email = Email::findOrFail($id);
        $email->update(['starred' => !$email->starred]);

        return back();
    }

    public function permanentDelete($id)
    {
        $email = Email::findOrFail($id);
        $email->delete();

        return back()->with('success', 'Email permanently deleted.');
    }

    public function markImportant($id)
    {
        $email = Email::findOrFail($id);
        $email->tag = 'important';
        $email->tag_color = '#facc15'; // yellow
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
            $email->tag_color = '#facc15'; // Yellow
        }

        $email->save();

        return redirect()->back()->with('success', 'Email mis à jour.');
    }

    public function moveToTrash($id)
    {
        $email = Email::findOrFail($id);
        $email->is_deleted = true;
        $email->tag = 'bin';
        $email->label_color = '#ef4444'; // red
        $email->save();

        return redirect()->back()->with('success', 'Email moved to trash.');
    }

    public function reply(Request $request, $id)
    {
        $email = Email::findOrFail($id);

        $request->validate([
            'content' => 'required|string',
            'file' => 'nullable|file|max:2048',
        ]);

        $reply = new Reply([
            'email_id'   => $email->id,
            'sender_id'  => Auth::id(),         // ← add this
            'content'    => $request->content,
            'file_path'  => null,
            'file_name'  => null,
        ]);

        // Set receiver_id before saving
        $reply->receiver_id = $email->receiver_id;

        // Handle file upload if present
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
                   ->latest()
                   ->paginate(12); // Paginate with 12 items per page

        $readCount = Email::where('folder', 'inbox')->where('is_read', true)->count();
        $unreadCount = Email::where('folder', 'inbox')->where('is_read', false)->count();

        return view('emails.notifications', compact('emails', 'readCount', 'unreadCount'));
    }

    public function markAllRead()
    {
        Email::where('folder', 'inbox')->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    public function markAsRead(Email $email)
{
    $email->markAsRead();
    return back();
}


    public function upload(Request $request)
{
    if ($request->hasFile('upload')) {
        $originName = $request->file('upload')->getClientOriginalName();
        $fileName = pathinfo($originName, PATHINFO_FILENAME);
        $extension = $request->file('upload')->getClientOriginalExtension();
        $fileName = $fileName . '_' . time() . '.' . $extension;

        $request->file('upload')->move(public_path('uploads'), $fileName);

        $url = asset('uploads/' . $fileName);

        return response()->json([
            'uploaded' => 1,
            'fileName' => $fileName,
            'url' => $url
        ]);
    }

    return response()->json([
        'uploaded' => 0,
        'error' => [
            'message' => 'Upload failed.'
        ]
    ], 400);
}
}