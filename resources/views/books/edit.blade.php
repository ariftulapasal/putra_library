@extends('layouts.app')

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Edit Book</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Books</a></li>
                                <li class="breadcrumb-item active">Edit Book</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Edit Book Details</h3>
                                </div>
                                <form action="{{ route('books.update', $book->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <!-- Existing form fields -->
                                                <div class="form-group">
                                                    <label for="title">Title</label>
                                                    <input type="text"
                                                        class="form-control @error('title') is-invalid @enderror"
                                                        id="title" name="title"
                                                        value="{{ old('title', $book->title) }}" required>
                                                    @error('title')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- ... other existing form fields ... -->
                                                <div class="form-group">
                                                    <label for="author">Author</label>
                                                    <input type="text"
                                                        class="form-control @error('author') is-invalid @enderror"
                                                        id="author" name="author"
                                                        value="{{ old('author', $book->author) }}" required>
                                                    @error('author')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="isbn">ISBN</label>
                                                    <input type="text"
                                                        class="form-control @error('isbn') is-invalid @enderror"
                                                        id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}"
                                                        required>
                                                    @error('isbn')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="barcode">Barcode</label>
                                                    <input type="text"
                                                        class="form-control @error('barcode') is-invalid @enderror"
                                                        id="barcode" name="barcode"
                                                        value="{{ old('barcode', $book->barcode) }}" required>
                                                    @error('barcode')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="published_year">Published Year</label>
                                                    <input type="number"
                                                        class="form-control @error('published_year') is-invalid @enderror"
                                                        id="published_year" name="published_year"
                                                        value="{{ old('published_year', $book->published_year) }}"
                                                        required>
                                                    @error('published_year')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="category">Category</label>
                                                    <input type="text"
                                                        class="form-control @error('category') is-invalid @enderror"
                                                        id="category" name="category"
                                                        value="{{ old('category', $book->category) }}" required>
                                                    @error('category')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select class="form-control @error('status') is-invalid @enderror"
                                                        id="status" name="status" required>
                                                        <option value="available"
                                                            {{ old('status', $book->status) == 'available' ? 'selected' : '' }}>
                                                            Available</option>
                                                        <option value="borrowed"
                                                            {{ old('status', $book->status) == 'borrowed' ? 'selected' : '' }}>
                                                            Borrowed</option>
                                                        <option value="reserved"
                                                            {{ old('status', $book->status) == 'reserved' ? 'selected' : '' }}>
                                                            Reserved</option>
                                                    </select>
                                                    @error('status')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                                        rows="3">{{ old('description', $book->description) }}</textarea>
                                                    @error('description')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                <!-- Image upload section -->
                                                <div class="form-group">
                                                    <label for="cover_image">Book Cover</label>
                                                    <div class="mb-2">
                                                        @if ($book->cover_image)
                                                            <img src="{{ asset($book->cover_image) }}"
                                                                alt="Current book cover" class="img-thumbnail mb-2"
                                                                style="max-height: 200px;">
                                                        @else
                                                            <div class="text-muted">No cover image</div>
                                                        @endif
                                                    </div>
                                                    <input type="file"
                                                        class="form-control-file @error('cover_image') is-invalid @enderror"
                                                        id="cover_image" name="cover_image"
                                                        accept="image/jpeg,image/png,image/jpg">
                                                    <small class="form-text text-muted">
                                                        Upload a new image to change the book cover (JPEG, PNG, JPG, max
                                                        2MB)
                                                    </small>
                                                    @error('cover_image')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Update Book</button>
                                        <a href="{{ route('books.index') }}" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
