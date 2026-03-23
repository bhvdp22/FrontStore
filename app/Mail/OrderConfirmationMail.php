<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $orderData;

    /**
     * @param array $orderData  All data needed for the email and invoice PDF
     */
    public function __construct(array $orderData)
    {
        $this->orderData = $orderData;
    }

    public function build()
    {
        $mail = $this->subject('Order Confirmed! 🎉 Your FrontStore Order #' . $this->orderData['order_id'])
                     ->view('emails.order-confirmation');

        // Generate invoice PDF and attach it
        $pdf = Pdf::loadView('emails.order-invoice-pdf', $this->orderData);

        $mail->attachData(
            $pdf->output(),
            'Invoice-' . $this->orderData['order_id'] . '.pdf',
            ['mime' => 'application/pdf']
        );

        return $mail;
    }
}
