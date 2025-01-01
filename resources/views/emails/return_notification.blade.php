<!DOCTYPE html>
<html>
<head>
    <title>Book Return Confirmation</title>
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
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
        }
        .book-details {
            background-color: #f3f3f3;
            padding: 15px;
            border-radius: 6px;
            margin: 10px 0;
        }
        .book-details strong {
            color: #4CAF50;
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
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        a.button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Book Return Confirmation</h1>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $user->name }}</strong>,</p>
            <p>You have successfully returned the following book:</p>

            <div class="book-details">
                <p><strong>Title:</strong> {{ $book->title }}</p>
                <p><strong>Barcode:</strong> {{ $book->barcode }}</p>
                <p><strong>Author:</strong> {{ $book->author }}</p>
            </div>

            <p>Thank you for using our library system!</p>
            <a href="#" class="button">Explore More Books</a>

            <p>If you need further assistance, feel free to contact us.</p>
        </div>
        <div class="footer">
            <p>Best regards,</p>
            <p><strong>Library Management Team</strong></p>
        </div>
    </div>
</body>
</html>


{{-- <!DOCTYPE html>
<html>
<head>
    <title>Book Return Confirmation</title>
</head>
<body>
    <h1>Dear {{ $user->name }},</h1>
    <p>You have successfully returned the following book:</p>

    <ul>
        <li>
            <strong>Title:</strong> {{ $book->title }} <br>
            <strong>Barcode:</strong> {{ $book->barcode }} <br>
            <strong>Author:</strong> {{ $book->author }}
        </li>
    </ul>

    <p>Thank you for using our library system!</p>
    <p>If you need further assistance, feel free to contact us.</p>

    <p>Best regards,<br>
    Library Management Team</p>
</body>
</html> --}}
