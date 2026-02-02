<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewPresenceSignal extends Notification
{
    use Queueable;

    public $userName;
    public $occurredAt;
    public $action; // 'signaled' or 'canceled'
    public $logId;
    public $userId;

    public function __construct(string $userName, string $occurredAt, int $logId, int $userId, string $action = 'signaled')
    {
        $this->userName = $userName;
        $this->occurredAt = $occurredAt;
        $this->action = $action;
        $this->logId = $logId;
        $this->userId = $userId;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'user' => $this->userName,
            'user_id' => $this->userId,
            'log_id' => $this->logId,
            'occurred_at' => $this->occurredAt,
            'action' => $this->action,
            // pointer vers la liste admin (email, action, heure) — pas de page "reçu"
            'link' => route('admin.presences.index'),
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
