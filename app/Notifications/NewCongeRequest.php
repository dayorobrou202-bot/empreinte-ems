<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Conge;

class NewCongeRequest extends Notification
{
    use Queueable;

    protected $conge;

    public function __construct(Conge $conge)
    {
        $this->conge = $conge;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Nouvelle demande de congé')
                    ->line("Collaborateur: {$this->conge->user->name}")
                    ->line("Période: {$this->conge->start_date} → {$this->conge->end_date}")
                    ->line('Connectez-vous à l’administration pour approuver ou refuser la demande.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'conge_id' => $this->conge->id,
            'user_id' => $this->conge->user_id,
            'start_date' => $this->conge->start_date,
            'end_date' => $this->conge->end_date,
            'message' => "Nouvelle demande de congé de {$this->conge->user->name}",
        ];
    }
}
