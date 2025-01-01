<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DueReminderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $book;
    public $user;
    public $days;

    public function __construct($book, $user, $days)
    {
        $this->book = $book;
        $this->user = $user;
        $this->days = $days;
    }

    public function build()
    {
        return $this->subject("Reminder: Book Due in {$this->days} Day(s)")
            ->view('emails.due_reminder_notification');
    }
}
