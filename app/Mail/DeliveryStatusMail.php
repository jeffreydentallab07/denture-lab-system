<?php

namespace App\Mail;

use App\Models\Delivery;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeliveryStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $delivery;
    public $status;
    public $recipientType; // 'clinic' or 'patient'

    public function __construct(Delivery $delivery, string $status, string $recipientType = 'clinic')
    {
        $this->delivery = $delivery;
        $this->status = $status;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        $caseOrder = $this->delivery->appointment->caseOrder;
        $subject = '';

        switch ($this->status) {
            case 'in transit':
                $subject = 'Your Order is On the Way - CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT);
                break;
            case 'delivered':
                $subject = 'Your Order Has Been Delivered - CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT);
                break;
            default:
                $subject = 'Delivery Update - CASE-' . str_pad($caseOrder->co_id, 5, '0', STR_PAD_LEFT);
        }

        return $this->subject($subject)
            ->view('emails.delivery-status')
            ->with([
                'caseOrder' => $caseOrder,
                'delivery' => $this->delivery,
                'status' => $this->status,
                'recipientType' => $this->recipientType,
            ]);
    }
}
