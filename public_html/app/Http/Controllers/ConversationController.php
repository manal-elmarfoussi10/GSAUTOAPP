<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ConversationThread;
use App\Models\Email;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class ConversationController extends Controller
{
    /**
     * Display the conversation threads and emails for a client.
     */
    public function show(Client $client)
    {
        $emails = $client->emails()
            ->with(['senderUser', 'receiverUser', 'replies.senderUser'])
            ->orderBy('created_at')
            ->get();

        $allowedRoles = [
            User::ROLE_ADMIN,
            User::ROLE_CLIENT_SERVICE,
            User::ROLE_CLIENT_LIMITED,
            User::ROLE_PLANNER,
            User::ROLE_SUPERADMIN,
        ];

        $users = User::where('company_id', Auth::user()->company_id)
            ->whereIn('role', $allowedRoles)
            ->get();

        return view('clients.show', compact('client', 'emails', 'users'));
    }

    /**
     * Store a new conversation thread and the initial email.
     */
    public function store(Request $request, Client $client)
    {
        $allowedRoles = [User::ROLE_ADMIN, User::ROLE_CLIENT_SERVICE];

        $request->validate([
            'receiver' => [
                'required',
                Rule::exists('users', 'id')->where(function($q) use ($allowedRoles) {
                    $q->whereIn('role', $allowedRoles)
                        ->where('company_id', Auth::user()->company_id);
                }),
            ],
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'file'    => 'nullable|file|max:2048',
        ]);

        // Create conversation thread
        $thread = ConversationThread::create([
            'client_id'  => $client->id,
            'company_id' => Auth::user()->company_id,
            'subject'    => $request->subject,
            'creator_id' => Auth::id(),
        ]);

        // Create the initial email
        $email = new Email([
            'client_id'   => $client->id,
            'company_id'  => Auth::user()->company_id,
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver,
            'subject'     => $request->subject,
            'content'     => $request->content,
            'folder'      => 'sent',
        ]);
        $thread->emails()->save($email);

        // Handle file upload: move directly into public/storage/app/public/conversations
        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $relative = 'conversations/' . $filename;
            $dest     = public_path('storage/app/public/conversations');

            if (! File::exists($dest)) {
                File::makeDirectory($dest, 0755, true);
            }
            $file->move($dest, $filename);

            // Update email record
            $email->file_path = $relative;
            $email->file_name = $file->getClientOriginalName();
            $email->save();
        }

        return back()->with('success', 'Conversation started!');
    }

    /**
     * Reply to an existing email.
     */
    public function reply(Request $request, Email $email)
    {
        $allowedRoles = [User::ROLE_ADMIN, User::ROLE_CLIENT_SERVICE];

        $rules = ['content' => 'required|string', 'file' => 'nullable|file|max:2048'];
        if ($request->has('receiver')) {
            $rules['receiver'] = [
                'required',
                Rule::exists('users', 'id')->where(function($q) use ($allowedRoles) {
                    $q->whereIn('role', $allowedRoles)
                        ->where('company_id', Auth::user()->company_id);
                }),
            ];
        }
        $request->validate($rules);

        $receiverId = $request->has('receiver') ? $request->receiver : $email->sender_id;

        $reply = Reply::create([
            'email_id'        => $email->id,
            'conversation_id' => $email->thread_id,
            'sender_id'       => Auth::id(),
            'receiver_id'     => $receiverId,
            'content'         => $request->content,
        ]);

        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $relative = 'conversations/' . $filename;
            $dest     = public_path('storage/app/public/conversations');

            if (! File::exists($dest)) {
                File::makeDirectory($dest, 0755, true);
            }
            $file->move($dest, $filename);

            $reply->file_path = $relative;
            $reply->file_name = $file->getClientOriginalName();
            $reply->save();
        }

        return back()->with('success', 'Reply sent!');
    }

    /**
     * Return the latest messages partial for polling.
     */
    public function fetch(Client $client)
    {
        $emails = $client->emails()
            ->with(['senderUser','receiverUser','replies.senderUser'])
            ->orderBy('created_at')
            ->get();

        return view('clients.partials._messages', compact('emails'));
    }

    /**
     * Delete an entire conversation thread.
     */
    public function destroyThread(ConversationThread $thread)
    {
        $thread->emails()->each->replies()->delete();
        $thread->emails()->delete();
        $thread->delete();

        return back()->with('success', 'Conversation deleted.');
    }

    /**
     * Download an uploaded file.
     */
    public function download(Reply $reply)
    {
        $fullPath = storage_path('app/public/' . $reply->file_path);
        if (! File::exists($fullPath)) {
            abort(404);
        }
        return Response::download($fullPath, $reply->file_name);
    }
}
