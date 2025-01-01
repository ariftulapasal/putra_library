@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Page Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Scan Book Barcode</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Scan Book</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Scanner Section -->
                    <div class="col-md-6">
                        <div class="card card-primary shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title">Book Barcode Scanner</h3>
                            </div>
                            <div class="card-body text-center">
                                <video id="video" width="100%" height="300" class="border rounded mb-3"></video>
                                <h5 class="font-weight-bold">Scanned Barcode:</h5>
                                <pre><code id="result" class="text-primary"></code></pre>
                                <button id="addMoreButton" class="btn btn-outline-info mt-3">
                                    <i class="fas fa-plus"></i> Add Another Book
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="col-md-6">
                        <div class="card card-secondary shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title">Scanned Book Details</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Availability</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bookDetails">
                                        <!-- Book details will be dynamically loaded -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            <button id="confirmBorrowButton" class="btn btn-success">
                                <i class="fas fa-check"></i> Confirm Borrow
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Loading Spinner -->
        <div class="loading">
            <div>Loading...Please wait.</div>
        </div>
    </div>

    <style>
        .loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 1.5rem;
        }
    </style>

    <script src="https://unpkg.com/@zxing/library@latest"></script>
    <script>
        window.addEventListener('load', function() {
            let selectedDeviceId;
            const codeReader = new ZXing.BrowserBarcodeReader();
            codeReader.getVideoInputDevices()
                .then((devices) => {
                    selectedDeviceId = devices[0].deviceId;
                    startScanning(codeReader, selectedDeviceId);
                })
                .catch((err) => console.error('Error accessing camera:', err));

            function startScanning(codeReader, deviceId) {
                codeReader.decodeOnceFromVideoDevice(deviceId, 'video').then((result) => {
                    document.getElementById('result').textContent = result.text;
                    fetchBookDetails(result.text);
                }).catch((err) => console.error('Scan Error:', err));
            }

            // function fetchBookDetails(barcode) {
            //     $.ajax({
            //         url: "{{ route('books.fetch') }}",
            //         method: 'POST',
            //         data: {
            //             _token: "{{ csrf_token() }}",
            //             barcode: barcode
            //         },
            //         success: function(book) {
            //             const availability = book.is_available ?
            //                 '<span class="text-success">Available</span>' :
            //                 '<span class="text-danger">Not Available</span>';
            //             const row = `
        //                 <tr>
        //                     <td>${book.id}</td>
        //                     <td>${book.title}</td>
        //                     <td>${book.author}</td>
        //                     <td>${availability}</td>
        //                 </tr>
        //             `;
            //             $('#bookDetails').append(row);
            //         },
            //         error: function() {
            //             $('#bookDetails').append(`
        //                 <tr><td colspan="4" class="text-center text-danger">Book not found.</td></tr>
        //             `);
            //         }
            //     });
            // }

            function fetchBookDetails(barcode) {
                $.ajax({
                    url: "{{ route('books.fetch') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        barcode: barcode
                    },
                    success: function(book) {
                        const isAvailable = book.is_available;
                        const availability = isAvailable ?
                            '<span class="text-success">Available</span>' :
                            '<span class="text-danger">Not Available</span>';
                        const row = `
                <tr data-book-id="${book.id}">
                    <td>${book.id}</td>
                    <td>${book.title}</td>
                    <td>${book.author}</td>
                    <td>${availability}</td>
                    <td>
                        <button class="btn btn-danger btn-sm remove-book-btn">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </td>
                </tr>
            `;
                        $('#bookDetails').append(row);
                    },
                    error: function() {
                        $('#bookDetails').append(`
                <tr><td colspan="5" class="text-center text-danger">Book not found.</td></tr>
            `);
                    }
                });
            }



            $('#addMoreButton').on('click', function() {
                $('#result').text('');
                startScanning(codeReader, selectedDeviceId);
            });

            // $('#confirmBorrowButton').on('click', function() {
            //     const bookRows = document.querySelectorAll('#bookDetails tr');
            //     const borrowedBooks = [];

            //     bookRows.forEach(row => {
            //         const columns = row.querySelectorAll('td');
            //         if (columns.length > 0) {
            //             borrowedBooks.push({
            //                 id: columns[0].textContent.trim()
            //             });
            //         }
            //     });

            //     if (borrowedBooks.length === 0) {
            //         alert('No books selected for borrowing.');
            //         return;
            //     }

            //     const userId = 1; // Replace with actual logged-in user ID
            //     showLoading();

            //     $.ajax({
            //         url: "{{ route('borrow.store') }}",
            //         method: 'POST',
            //         data: {
            //             _token: "{{ csrf_token() }}",
            //             user_id: userId,
            //             books: borrowedBooks
            //         },
            //         success: function(response) {
            //             alert(response.message);
            //             location.reload();
            //         },
            //         error: function(xhr) {
            //             alert('Error borrowing books: ' + xhr.responseJSON.message);
            //         },
            //         complete: function() {
            //             hideLoading();
            //         }
            //     });
            // });

            $('#confirmBorrowButton').on('click', function() {
                const bookRows = document.querySelectorAll('#bookDetails tr');
                const borrowedBooks = [];
                let hasUnavailableBooks = false;

                bookRows.forEach(row => {
                    const columns = row.querySelectorAll('td');
                    if (columns.length > 0) {
                        const availabilityText = columns[3].textContent.trim();
                        if (availabilityText === 'Not Available') {
                            hasUnavailableBooks = true;
                        } else {
                            borrowedBooks.push({
                                id: columns[0].textContent.trim()
                            });
                        }
                    }
                });

                $(document).on('click', '.remove-book-btn', function() {
                    const row = $(this).closest('tr'); // Find the closest row to the clicked button
                    row.remove(); // Remove the row from the table
                });


                if (hasUnavailableBooks) {
                    alert(
                        'Some books are not available for borrowing. Please remove them before proceeding.'
                        );
                    return;
                }

                if (borrowedBooks.length === 0) {
                    alert('No books selected for borrowing.');
                    return;
                }

                const userId = 1; // Replace with actual logged-in user ID
                showLoading();

                $.ajax({
                    url: "{{ route('borrow.store') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        user_id: userId,
                        books: borrowedBooks
                    },
                    success: function(response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error borrowing books: ' + xhr.responseJSON.message);
                    },
                    complete: function() {
                        hideLoading();
                    }
                });
            });


            function showLoading() {
                document.querySelector('.loading').style.display = 'flex';
            }

            function hideLoading() {
                document.querySelector('.loading').style.display = 'none';
            }
        });
    </script>
@endsection
