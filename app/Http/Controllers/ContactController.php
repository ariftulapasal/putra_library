<?php
// app/Http/Controllers/ContactController.php
namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        try {
            // Send email to admin
            Mail::to(config('mail.admin.address'))->send(new ContactFormMail($validated));

            return redirect()->back()->with('success', 'Thank you for your message. We will contact you soon!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sorry, there was an error sending your message.')->withInput();
        }
    }
}
