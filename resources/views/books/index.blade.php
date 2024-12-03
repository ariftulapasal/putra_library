@extends('layouts.app')

@section('content')
<div class="wrapper">
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>List of Books</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Book List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Card -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-book"></i> List of Books</h3>
                                <a href="{{ route('books.create') }}" class="btn btn-primary float-right">
                                    <i class="fas fa-plus"></i> Add New Book
                                </a>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body p-0">
                                <table class="table table-hover table-bordered">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>ISBN</th>
                                            <th>Published Year</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($books as $index => $book)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $book->title }}</td>
                                                <td>{{ $book->author }}</td>
                                                <td>{{ $book->isbn }}</td>
                                                <td>{{ $book->published_year }}</td>
                                                <td>{{ $book->category }}</td>
                                                <td>
                                                    <span class="badge 
                                                        @if($book->status == 'available') bg-success 
                                                        @elseif($book->status == 'borrowed') bg-warning 
                                                        @else bg-danger @endif">
                                                        {{ ucfirst($book->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination -->
                            <div class="card-footer clearfix">
                                {{ $books->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
