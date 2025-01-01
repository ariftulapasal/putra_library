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
                            <h1>All Borrow Records</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Borrow Records</li>
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
                            <!-- Borrow Records Table -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-book"></i> Borrow Records</h3>
                                </div>

                                {{-- <div class="d-flex justify-content-start mb-3">
                                    <a href="{{ route('admin.borrow-records', ['filter' => 'borrowed']) }}" 
                                       class="btn btn-primary {{ $filter === 'borrowed' ? 'active' : '' }}">
                                        Currently Borrowed
                                    </a>
                                    <a href="{{ route('admin.borrow-records', ['filter' => 'returned']) }}" 
                                       class="btn btn-success ml-2 {{ $filter === 'returned' ? 'active' : '' }}">
                                        Returned
                                    </a>
                                    <a href="{{ route('admin.borrow-records', ['filter' => 'overdue']) }}" 
                                       class="btn btn-danger ml-2 {{ $filter === 'overdue' ? 'active' : '' }}">
                                        Overdue
                                    </a>
                                </div> --}}
                                
                                <div class="card-body p-0">
                                    <table class="table table-hover table-bordered">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>User</th>
                                                <th>Book Title</th>
                                                <th>Author</th>
                                                <th>Borrow Date</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($borrowRecords as $index => $record)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $record->user->name }}</td>
                                                    <td>{{ $record->book->title }}</td>
                                                    <td>{{ $record->book->author }}</td>
                                                    <td>{{ $record->borrow_date }}</td>
                                                    <td>{{ $record->due_date }}</td>
                                                    <td>
                                                        <span
                                                            class="badge 
                                                        @if ($record->status == 'borrowed') bg-warning
                                                        @elseif($record->status == 'returned') bg-success
                                                        @elseif($record->status == 'overdue') bg-danger @endif">
                                                            {{ ucfirst($record->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if ($record->status == 'borrowed')
                                                            <button class="btn btn-success btn-sm return-book-btn"
                                                                data-id="{{ $record->id }}">
                                                                <i class="fas fa-undo"></i> Mark Returned
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{-- <table class="table table-hover table-bordered">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>User</th>
                                                <th>Book Title</th>
                                                <th>Author</th>
                                                <th>Borrow Date</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($borrowRecords as $index => $record)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $record->user->name }}</td>
                                                    <td>{{ $record->book->title }}</td>
                                                    <td>{{ $record->book->author }}</td>
                                                    <td>{{ $record->borrow_date }}</td>
                                                    <td>{{ $record->due_date }}</td>
                                                    <td>
                                                        <span class="badge 
                                                            @if($record->status == 'borrowed') bg-warning
                                                            @elseif($record->status == 'returned') bg-success
                                                            @elseif($record->status == 'overdue') bg-danger
                                                            @endif">
                                                            {{ ucfirst($record->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($record->status == 'borrowed')
                                                            <button class="btn btn-success btn-sm return-book-btn" data-id="{{ $record->id }}">
                                                                <i class="fas fa-undo"></i> Mark Returned
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table> --}}
                                    
                                </div>
                                <!-- Pagination -->
                                <div class="card-footer clearfix">
                                    {{ $borrowRecords->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        // Handle "Mark Returned" button click
        // $(document).on('click', '.return-book-btn', function() {
        //     const recordId = $(this).data('id');

        //     if (confirm('Are you sure you want to mark this book as returned?')) {
        //         $.ajax({
        //             url: `/admin/borrow-records/return/${recordId}`,
        //             method: 'POST',
        //             data: {
        //                 _token: "{{ csrf_token() }}"
        //             },
        //             success: function(response) {
        //                 alert(response.message);
        //                 location.reload();
        //             },
        //             error: function(xhr) {
        //                 alert('Error: ' + xhr.responseJSON.message);
        //             }
        //         });
        //     }
        // });

        $(document).on('click', '.return-book-btn', function() {
            const recordId = $(this).data('id'); // Get the record ID from the button's data attribute

            if (confirm('Are you sure you want to mark this book as returned?')) {
                $.ajax({
                    url: `/admin/borrow-records/return/${recordId}`, // Adjust the URL to match your route
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}" // Include CSRF token for security
                    },
                    success: function(response) {
                        alert(response.message); // Show success message
                        location.reload(); // Reload the page to reflect changes
                    },
                    error: function(xhr) {
                        alert('Error: ' + (xhr.responseJSON.message ||
                        'Failed to mark as returned.')); // Show error message
                    }
                });
            }
        });
    </script>
@endsection
