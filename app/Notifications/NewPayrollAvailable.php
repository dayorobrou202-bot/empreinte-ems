<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewPayrollAvailable extends Notification
{
    use Queueable;

    public $payrollId;
    public $month;
    public $amount;

    public function __construct(int $payrollId, string $month, float $amount)
    {
        $this->payrollId = $payrollId;
        $this->month = $month;
        $this->amount = $amount;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Nouveau bulletin de paie disponible',
            'payroll_id' => $this->payrollId,
            'month' => $this->month,
            'amount' => $this->amount,
            'link' => route('paie'),
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
