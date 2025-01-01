<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Due Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #FFC107;
            color: white;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
        }
        .book-details {
            list-style-type: none;
            padding: 0;
        }
        .book-details li {
            background-color: #f3f3f3;
            margin: 10px 0;
            padding: 10px;
            border-radius: 6px;
        }
        .book-details strong {
            color: #FFC107;
        }
        .footer {
            text-align: center;
            font-size: 0.9em;
            color: #777;
            margin-top: 20px;
        }
        a.button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #FFC107;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        a.button:hover {
            background-color: #e0a800;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Book Due Reminder</h1>
        </div>
        <div class="content">
            <p>Hi <strong>{{ $user->name }}</strong>,</p>
            <p>This is a friendly reminder that the following book is due in <strong>{{ $days }}</strong> day(s):</p>

            <ul class="book-details">
                <li><strong>Book Title:</strong> {{ $book->title }}</li>
                <li><strong>Barcode:</strong> {{ $book->barcode }}</li>
                <li><strong>Due Date:</strong> {{ $book->borrowRecord->due_date ? $book->borrowRecord->due_date->format('d M, Y') : 'N/A' }}</li>
            </ul>

            <p>Please return or renew the book before the due date to avoid any penalties.</p>

            <a href="#" class="button">Renew Book</a>

            <p>Thank you,<br>Your Library Team</p>
        </div>
        <div class="footer">
            <p>Best regards,</p>
            <p><strong>Library Management Team</strong></p>
        </div>
    </div>
</body>
</html>




{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Due Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #444;
            font-size: 22px;
        }
        .book-details {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }
        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 20px;
            text-align: center;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dear {{ $user->name }},</h1>
        <p>We hope you're enjoying your borrowed book. This is a friendly reminder that the following book is due in <strong>{{ $daysRemaining }} days</strong>:</p>

        <div class="book-details">
            <p><strong>Title:</strong> {{ $book->title }}</p>
            <p><strong>Author:</strong> {{ $book->author }}</p>
            <p><strong>Barcode:</strong> {{ $book->barcode }}</p>
            <p><strong>Due Date:</strong> {{ $book->borrowRecord->due_date->format('d M, Y') }}</p>
        </div>

        <p>Please ensure to return or renew the book before the due date to avoid any penalties.</p>

        <p>If you have any questions or need assistance, feel free to contact us.</p>

        <p>Best regards,<br>
        <strong>Library Management Team</strong></p>

        <div class="footer">
            <p>This is an auto-generated email. Please do not reply to this message.</p>
            <p>Contact us: <a href="mailto:library@example.com">library@example.com</a> | Phone: 03-9769 8642</p>
        </div>
    </div>
</body>
</html> --}}
