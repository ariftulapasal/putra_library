@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <body>
            <h1>Add New Book</h1>
            <form method="POST" action="{{ route('books.store') }}">
                @csrf
                <label>Title:</label>
                <input type="text" name="title" required><br>

                <label>Author:</label>
                <input type="text" name="author" required><br>

                <label>ISBN:</label>
                <input type="text" name="isbn" required><br>

                <label>Published Year:</label>
                <input type="number" name="published_year" required><br>

                <label>Category:</label>
                <input type="text" name="category" required><br>

                <button type="submit">Add Book</button>
            </form>
        </body>
    </div>
@endsection


{{-- <!DOCTYPE html>
<html>
<head>
    <title>Add New Book</title>
</head>
<body>
    <h1>Add New Book</h1>
    <form method="POST" action="{{ route('books.store') }}">
        @csrf
        <label>Title:</label>
        <input type="text" name="title" required><br>

        <label>Author:</label>
        <input type="text" name="author" required><br>

        <label>ISBN:</label>
        <input type="text" name="isbn" required><br>

        <label>Published Year:</label>
        <input type="number" name="published_year" required><br>

        <label>Category:</label>
        <input type="text" name="category" required><br>

        <button type="submit">Add Book</button>
    </form>
</body>
</html> --}}
