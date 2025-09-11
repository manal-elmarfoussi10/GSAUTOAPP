<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ConversationThread;
use App\Models\Email;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /** Can the user act on this client? */
    private function canActOnClient(Client $client, User $user): bool
    {
        if ($user->canSeeAllConversations()) return true;       // superadmin / service client
        if (is_null($user->company_id)) return false;            // must belong to a company

        // Allow if client's company matches OR client has no company (legacy)
        return is_null($client->company_id) ||
               (int)$client->company_id === (int)$user->company_id;
    }

    /** Show client’s conversations */
    public function show(Client $client)
    {
        $emails = $client->emails()
            ->with([
                'senderUser:id,name',
                'receiverUser:id,name',
                'replies.sender:id,name',
                'replies.receiver:id,name',
            ])
            ->orderBy('created_at')
            ->get();

        $supportUsers = User::supportUsers()->orderBy('name')->get();

        return view('clients.show', compact('client', 'emails', 'supportUsers'));
    }

    /** Start a new conversation (broadcast to GS Auto support) */
    public function store(Request $request, Client $client)
    {
        $me = auth()->user();
        if (! $this->canActOnClient($client, $me)) abort(403, 'Accès refusé.');

        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'file'    => 'nullable|file|max:2048',
        ]);

        // Resolve company id
        $companyId = $client->company_id ?: $me->company_id;
        if (is_null($client->company_id) && $me->company_id) {
            $client->company_id = $me->company_id;  // normalize legacy data
            $client->save();
        }

        // Create thread
        $thread = ConversationThread::create([
            'client_id'  => $client->id,
            'company_id' => $companyId,
            'subject'    => $request->subject,
            'creator_id' => $me->id,
        ]);

        // Root email in thread
        $email = new Email([
            'client_id'   => $client->id,
            'company_id'  => $companyId,
            'sender_id'   => $me->id,
            'receiver_id' => null,           // GS Auto support pool
            'subject'     => $request->subject,
            'content'     => $request->content,
            'folder'      => 'sent',
        ]);
        $thread->emails()->save($email);      // sets email.thread_id

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('conversations', 'public');
            $email->update([
                'file_path' => $path,
                'file_name' => $request->file('file')->getClientOriginalName(),
            ]);
        }

        return back()->with('success', 'Conversation créée et envoyée au support GS Auto.');
    }

    /** Reply to an email inside a thread */
    public function reply(Request $request, Email $email)
    {
        $me = auth()->user();
        if ($email->client && ! $this->canActOnClient($email->client, $me)) {
            abort(403, 'Accès refusé.');
        }

        $request->validate([
            'content'  => 'required|string',
            'file'     => 'nullable|file|max:2048',
            'receiver' => 'nullable|exists:users,id',
        ]);

        $receiverId = $this->resolveCounterparty($email, $me->id, $request->integer('receiver'));
        if (! $receiverId) return back()->withErrors("Impossible d’identifier le destinataire.");

        $reply = Reply::create([
            'email_id'    => $email->id,
            'thread_id'   => $email->thread_id,
            'sender_id'   => $me->id,
            'receiver_id' => $receiverId,
            'content'     => $request->content,
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('conversations', 'public');
            $reply->update([
                'file_path' => $path,
                'file_name' => $request->file('file')->getClientOriginalName(),
            ]);
        }

        // First reply sets the root receiver for better sorting
        if (is_null($email->receiver_id)) {
            $email->update(['receiver_id' => $receiverId]);
        }

        return back()->with('success', 'Réponse envoyée.');
    }

    /** AJAX refresh */
    public function fetch(Client $client)
    {
        $emails = $client->emails()
            ->with([
                'senderUser:id,name',
                'receiverUser:id,name',
                'replies.sender:id,name',
                'replies.receiver:id,name',
            ])
            ->orderBy('created_at')
            ->get();

        return view('clients.partials._messages', compact('emails'));
    }

    /** Delete a whole thread (emails + replies) */
    public function destroyThread(ConversationThread $thread)
    {
        $thread->emails()->each(fn (Email $email) => $email->replies()->delete());
        $thread->emails()->delete();
        $thread->delete();

        return back()->with('success', 'Conversation supprimée.');
    }

    /** Download a reply attachment */
    public function download(Reply $reply)
    {
        $fullPath = storage_path('app/public/' . $reply->file_path);
        if (! file_exists($fullPath)) abort(404);
        return response()->download($fullPath, $reply->file_name);
    }

    /** Decide who receives the message */
    private function resolveCounterparty(Email $email, int $currentUserId, ?int $override = null): ?int
    {
        if ($override) return $override;

        $me = User::find($currentUserId, ['id','company_id']);
        if (! $me) return null;

        // Company user → send to GS Auto support (any global user)
        if (! is_null($me->company_id)) {
            return User::whereNull('company_id')->orderBy('id')->value('id');
        }

        // Support → send back to original company sender, or any user in that company
        if ($email->sender_id && User::whereKey($email->sender_id)->whereNotNull('company_id')->exists()) {
            return (int) $email->sender_id;
        }
        if ($email->company_id) {
            return (int) User::where('company_id', $email->company_id)->orderBy('id')->value('id');
        }

        return User::whereNotNull('company_id')->orderBy('id')->value('id');
    }
}