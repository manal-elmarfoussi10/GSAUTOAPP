<?php

namespace App\Services;

use App\Models\Email;
use App\Models\Reply;
use App\Models\User;

class MailThreadService
{
    /**
     * Ensure we have ONE email thread per client conversation.
     */
    public function ensureEmailThreadForClient(int $clientId, ?int $conversationId = null): Email
    {
        return Email::firstOrCreate(
            [
                'client_id'       => $clientId,
                'conversation_id' => $conversationId,
            ],
            [
                'subject'         => '[Conversation client]',   // you can improve this
                'content'         => '',                        // root body (not used after)
                'sender_user_id'  => auth()->id(),              // creator
                'receiver_user_id'=> null,                      // set by show/reply logic
                'is_read'         => false,
            ]
        );
    }

    /**
     * Decide who the message is sent TO.
     * - If sender is a company user (has company_id) → send to “service user” (global).
     * - If sender is service user (company_id is null) → send to the company side (origin owner).
     */
    public function resolveCounterparty(Email $email, int $currentUserId): ?int
    {
        $me = User::find($currentUserId, ['id','company_id']);

        if (!$me) return null;

        if (!is_null($me->company_id)) {
            // Company → Service client
            $service = User::whereNull('company_id')->orderBy('id')->value('id');
            return $service ?: null;
        }

        // Service → Company side (prefer original sender if he is company user; otherwise fallback)
        $candidate = $email->sender_user_id ?: $email->receiver_user_id;
        if ($candidate && User::whereKey($candidate)->whereNotNull('company_id')->exists()) {
            return (int) $candidate;
        }

        // Last fallback: first company user linked to that client (adjust to your data)
        if ($email->client_id) {
            $companyAdmin = User::whereNotNull('company_id')
                ->orderBy('id')
                ->value('id');
            if ($companyAdmin) return (int) $companyAdmin;
        }

        return null;
    }

    /**
     * Store a reply (used by both Superadmin and Tenant controllers).
     */
    public function storeReply(Email $email, int $senderId, string $content, ?string $filePath = null, ?string $fileName = null): bool
    {
        $receiverId = $this->resolveCounterparty($email, $senderId);
        if (!$receiverId || !User::whereKey($receiverId)->exists()) {
            return false;
        }

        Reply::create([
            'email_id'    => $email->id,
            'sender_id'   => $senderId,
            'receiver_id' => $receiverId,
            'content'     => $content,
            'file_path'   => $filePath,
            'file_name'   => $fileName,
        ]);

        // keep root email's receiver filled to the *service* user when missing (nice for lists)
        if (is_null($email->receiver_user_id)) {
            $email->receiver_user_id = User::whereNull('company_id')->orderBy('id')->value('id');
            $email->save();
        }

        return true;
    }
}