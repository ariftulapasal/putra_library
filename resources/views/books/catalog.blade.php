@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<div class="container mt-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 font-weight-bold">Book Catalog</h1>
        <form method="GET" action="{{ route('books.catalog') }}" class="d-flex">
            <input type="text" name="search" placeholder="Search books..." class="form-control mr-2" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <!-- Filter by Categories -->
    <div class="mb-4">
        <form method="GET" action="{{ route('books.catalog') }}">
            <select name="category" class="form-control w-25 d-inline-block">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                        {{ ucfirst($category) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-success">Filter</button>
        </form>
    </div>

    <!-- Books Display -->
    <div class="row">
        @foreach($books as $book)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ $book->cover_image ?? 'https://placehold.co/200x300' }}" 
                         alt="{{ $book->title }}" class="card-img-top" style="height: 300px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $book->title }}</h5>
                        <p class="card-text text-muted">{{ $book->author }}</p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#bookModal{{ $book->id }}">
                            View Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modal for Book Details -->
            <div class="modal fade" id="bookModal{{ $book->id }}" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="bookModalLabel">{{ $book->title }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{ $book->cover_image ?? 'https://placehold.co/200x300' }}" 
                                         alt="{{ $book->title }}" class="img-fluid">
                                </div>
                                <div class="col-md-8">
                                    <p><strong>Author:</strong> {{ $book->author }}</p>
                                    <p><strong>ISBN:</strong> {{ $book->isbn }}</p>
                                    <p><strong>Published Year:</strong> {{ $book->published_year }}</p>
                                    <p><strong>Category:</strong> {{ ucfirst($book->category) }}</p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge badge-{{ $book->status == 'available' ? 'success' : ($book->status == 'borrowed' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($book->status) }}
                                        </span>
                                    </p>
                                    <p>{{ $book->description ?? 'No description available.' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $books->links() }}
    </div>
</div>
@endsection
