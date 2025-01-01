<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BorrowRecord;
use App\Mail\DueReminderNotification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDueReminderEmails extends Command
{
    protected $signature = 'send:due-reminders';
    protected $description = 'Send notification emails for books due in 3, 2, and 1 days';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Days before due date for reminders
        $reminderDays = [3, 2, 1];

        foreach ($reminderDays as $days) {
            $dueDate = Carbon::now()->addDays($days)->startOfDay();

            // Fetch borrow records where the due date matches the condition
            $borrowRecords = BorrowRecord::whereDate('due_date', $dueDate)
                ->where('status', 'borrowed')
                ->with('book', 'user') // Eager load relationships
                ->get();

            foreach ($borrowRecords as $record) {
                $user = $record->user;
                $book = $record->book;

                // Send email
                Mail::to($user->email)->send(new DueReminderNotification($book, $user, $days));

                $this->info("Reminder sent for book '{$book->title}' to {$user->email} (Due in {$days} day(s))");
            }
        }

        $this->info('Due reminder emails sent successfully.');
    }
}
