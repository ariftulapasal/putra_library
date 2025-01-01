<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BorrowNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $books;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($books, $user)
    {
        $this->books = $books; // Array of borrowed books
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Book Borrowed Notification')
                    ->view('emails.borrow_notification') // Create this view
                    ->with([
                        'books' => $this->books,
                        'user' => $this->user,
                    ]);
    }
}
