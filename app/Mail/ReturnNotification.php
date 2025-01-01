<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReturnNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $book;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($book, $user)
    {
        $this->book = $book;
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Book Return Confirmation')
                    ->view('emails.return_notification') // Create this view
                    ->with([
                        'book' => $this->book,
                        'user' => $this->user,
                    ]);
    }
}
