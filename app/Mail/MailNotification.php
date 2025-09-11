<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;

class MailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        // $this->ticket = $ticket;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from('itsupport@plnbatam.com', 'Admin PLN Batam')
                    ->subject('Contoh Notifikasi')
                    ->view('mails.email-template');
                    // ->with([
                    //     'ticket' => $this->ticket,
                    // ]);
    }
}
