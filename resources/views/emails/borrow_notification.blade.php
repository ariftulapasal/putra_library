<!DOCTYPE html>
<html>
<head>
    <title>Book Borrowed Notification</title>
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
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
        }
        .book-list {
            list-style-type: none;
            padding: 0;
        }
        .book-list li {
            background-color: #f3f3f3;
            margin: 10px 0;
            padding: 10px;
            border-radius: 6px;
        }
        .book-list strong {
            color: #007BFF;
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
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        a.button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Book Borrowed Notification</h1>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $user->name }}</strong>,</p>
            <p>You have successfully borrowed the following books:</p>

            <ul class="book-list">
                @foreach($books as $book)
                    <li>
                        <strong>{{ $book->title }}</strong>  
                        (Barcode: {{ $book->barcode }})
                    </li>
                @endforeach
            </ul>

            <p>
                Please return the books by: <strong>{{ now()->addDays(14)->toFormattedDateString() }}</strong>.
            </p>

            <a href="#" class="button">View Borrowing History</a>

            <p>Thank you for using our library system!</p>
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
    <title>Book Borrowed Notification</title>
</head>
<body>
    <h1>Dear {{ $user->name }},</h1>
    <p>You have successfully borrowed the following books:</p>

    <ul>
        @foreach($books as $book)
            <li>
                <strong>{{ $book->title }}</strong>  
                (Barcode: {{ $book->barcode }})
            </li>
        @endforeach
    </ul>

    <p>
        Please return the books by: <strong>{{ now()->addDays(14)->toFormattedDateString() }}</strong>.
    </p>

    <p>Thank you for using our library system!</p>
</body>
</html> --}}
