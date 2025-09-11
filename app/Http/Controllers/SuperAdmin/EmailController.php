<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Email;
use App\Models\Reply;
use App\Models\User;

class EmailController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $supportIds = \App\Models\User::supportUsers()->pluck('id');
    
        $emails = \App\Models\Email::with([
                'senderUser:id,name',
                'receiverUser:id,name',
                'replies' => fn ($q) => $q->latest()->with(['sender:id,name','receiver:id,name']),
            ])
            ->where(function ($q) use ($supportIds) {
                $q->whereIn('receiver_id', $supportIds)
                  ->orWhereIn('sender_id', $supportIds)
                  ->orWhereNull('receiver_id'); // broadcast
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->string('search')->toString();
                $q->where(fn ($sq) => $sq->where('subject','like',"%{$s}%")
                                         ->orWhere('content','like',"%{$s}%"));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();
    
        return view('superadmin.emails.inbox', compact('emails'));
    }
    public function show(Email $email)
    {
        $email->load([
            'senderUser:id,name',
            'receiverUser:id,name',
            'replies.sender:id,name',
            'replies.receiver:id,name',  // ✅ correct relation name
        ]);

        if (property_exists($email, 'is_read') && ! $email->is_read) {
            $email->is_read = true;
            $email->save();
        }

        return view('superadmin.emails.show', compact('email'));
    }

    public function reply(Request $request, Email $email)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'file'    => 'nullable|file|max:5120',
        ]);

        $me        = (int) auth()->id();

        // detect if current user belongs to a company -> goes to service
        $meUser = User::find($me, ['id','company_id']);
        $goesToService = $meUser && !is_null($meUser->company_id);

        // upload
        $filePath = null;
        $fileName = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('emails', 'public');
            $fileName = $request->file('file')->getClientOriginalName();
        }

        if ($goesToService) {
            $globals = User::whereNull('company_id')->pluck('id');
            if ($globals->isEmpty()) {
                return back()->withErrors('Aucun utilisateur “Service Client” (company_id NULL) trouvé.');
            }

            foreach ($globals as $receiverId) {
                Reply::create([
                    'email_id'    => (int) $email->id,
                    'thread_id'   => (int) $email->thread_id,
                    'sender_id'   => $me,
                    'receiver_id' => (int) $receiverId,
                    'content'     => $validated['content'],
                    'file_path'   => $filePath,
                    'file_name'   => $fileName,
                ]);
            }
        } else {
            // service/global → send back to original sender of this email
            $receiverId = (int) $email->sender_id;   // ✅ use sender_id (not sender_user_id)
            if (!User::whereKey($receiverId)->exists()) {
                return back()->withErrors('Destinataire introuvable.');
            }

            Reply::create([
                'email_id'    => (int) $email->id,
                'thread_id'   => (int) $email->thread_id,
                'sender_id'   => $me,
                'receiver_id' => $receiverId,
                'content'     => $validated['content'],
                'file_path'   => $filePath,
                'file_name'   => $fileName,
            ]);
        }

        return back()->with('success', 'Réponse envoyée.');
    }

    public function toggleImportant(Email $email)
    {
        $email->tag = $email->tag === 'important' ? null : 'important';
        $email->tag_color = $email->tag ? '#f59e0b' : null;
        $email->save();

        return back();
    }

    public function moveToTrash(Email $email)
    {
        if (in_array('Illuminate\\Database\\Eloquent\\SoftDeletes', class_uses_recursive(Email::class))) {
            $email->delete();
        } else {
            $email->in_trash = true;
            $email->save();
        }

        return back();
    }

    public function markAllRead()
    {
        Email::where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'Tous les messages ont été marqués comme lus.');
    }

    public function upload(Request $request)
    {
        $file = $request->file('upload') ?? $request->file('file') ?? $request->file('image');
        abort_unless($file && $file->isValid(), 422, 'No file uploaded');

        $request->validate([
            'upload' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:5120',
            'file'   => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:5120',
            'image'  => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $path = $file->store('emails', 'public');
        $url  = asset('storage/'.$path);

        if ($func = $request->input('CKEditorFuncNum')) {
            return response(
                "<script>window.parent.CKEDITOR.tools.callFunction({$func}, '{$url}', '');</script>",
                200,
                ['Content-Type' => 'text/html; charset=utf-8']
            );
        }

        return response()->json([
            'uploaded' => 1,
            'fileName' => $file->getClientOriginalName(),
            'url'      => $url,
        ]);
    }
}