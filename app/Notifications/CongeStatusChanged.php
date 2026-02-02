<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Conge;

class CongeStatusChanged extends Notification
{
    use Queueable;

    protected $conge;
    protected $status;
    protected $adminName;

    public function __construct(Conge $conge, string $status, string $adminName = null)
    {
        $this->conge = $conge;
        $this->status = $status;
        $this->adminName = $adminName;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->status === 'approuve' ? 'Demande de congé approuvée' : 'Demande de congé refusée';

        return (new MailMessage)
                    ->subject($subject)
                    ->greeting("Bonjour {$notifiable->name},")
                    ->line("Votre demande de congé ({$this->conge->start_date} → {$this->conge->end_date}) a été : " . strtoupper($this->status))
                    ->line($this->adminName ? "Réponse par : {$this->adminName}" : '')
                    ->action('Voir mes congés', url('/conges'));
    }

    public function toDatabase($notifiable)
    {
        return [
            'conge_id' => $this->conge->id,
            'status' => $this->status,
            'admin' => $this->adminName,
            'start_date' => $this->conge->start_date,
            'end_date' => $this->conge->end_date,
            'message' => $this->status === 'approuve' ? 'Votre congé a été approuvé.' : 'Votre congé a été refusé.',
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
