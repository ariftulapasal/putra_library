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
                        <h1>Your Borrowed Books</h1>
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
                        <!-- Card -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-book"></i> Your Borrowed Books</h3>
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
                                            <th>Borrow Date</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($borrowRecords as $index => $record)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $record->book->title ?? 'N/A' }}</td>
                                                <td>{{ $record->book->author ?? 'N/A' }}</td>
                                                <td>{{ $record->book->isbn ?? 'N/A' }}</td>
                                                <td>{{ $record->borrow_date->format('Y-m-d') }}</td>
                                                <td>{{ $record->due_date->format('Y-m-d') }}</td>
                                                <td>
                                                    <span class="badge 
                                                        @if($record->status == 'borrowed') bg-warning
                                                        @elseif($record->status == 'returned') bg-success
                                                        @elseif($record->status == 'overdue') bg-danger
                                                        @endif">
                                                        {{ ucfirst($record->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No borrow records found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination -->
                            <div class="card-footer clearfix">
                                {{ $borrowRecords->links('pagination::bootstrap-4') }}
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
