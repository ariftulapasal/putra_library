@component('mail::message')
# New Contact Form Submission

You have received a new message from your website's contact form.

**Name:** {{ $contactData['name'] }}  
**Email:** {{ $contactData['email'] }}  
**Phone:** {{ $contactData['phone'] }}

**Message:**  
{{ $contactData['message'] }}

Thanks,  
{{ config('app.name') }}
@endcomponent