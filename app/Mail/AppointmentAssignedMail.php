<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        $caseOrder = $this->appointment->caseOrder;
        $subject = 'Work Scheduled for Your Order - CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT);

        return $this->subject($subject)
            ->view('emails.appointment-assigned')
            ->with([
                'appointment' => $this->appointment,
                'caseOrder' => $caseOrder,
            ]);
    }
}
