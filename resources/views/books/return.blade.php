@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Page Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Return Borrowed Books</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Return Books</li>
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
                                <button id="returnAnotherButton" class="btn btn-outline-info mt-3">
                                    <i class="fas fa-undo"></i> Return Another Book
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="col-md-6">
                        <div class="card card-secondary shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title">Your Borrowed Books</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Barcode</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="borrowedBooks">
                                        <!-- Borrowed books will be dynamically loaded -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Loading Spinner -->
        <div class="loading">
            <div>Loading...</div>
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
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 1.5rem;
        }
    </style>

    <script src="https://unpkg.com/@zxing/library@latest"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        window.addEventListener('load', function () {
            let selectedDeviceId;
            const codeReader = new ZXing.BrowserBarcodeReader();

            // Initialize Camera
            codeReader.getVideoInputDevices()
                .then((devices) => {
                    selectedDeviceId = devices[0].deviceId;
                    startScanning(codeReader, selectedDeviceId);
                })
                .catch((err) => console.error('Camera error:', err));

            // Function to start scanning barcode
            function startScanning(codeReader, deviceId) {
                codeReader.decodeOnceFromVideoDevice(deviceId, 'video').then((result) => {
                    document.getElementById('result').textContent = result.text;
                    returnBook(result.text); // Send barcode to backend
                }).catch((err) => console.error('Scanning error:', err));
            }

            // Load Borrowed Books List
            function loadBorrowedBooks() {
                showLoading();
                $.ajax({
                    url: "{{ route('books.borrowed') }}", // Backend route
                    method: 'GET',
                    success: function (response) {
                        let rows = '';
                        response.forEach(book => {
                            rows += `
                                <tr>
                                    <td>${book.id}</td>
                                    <td>${book.title}</td>
                                    <td>${book.author}</td>
                                    <td>${book.barcode}</td>
                                    <td id="status-${book.id}">${book.status}</td>
                                </tr>
                            `;
                        });
                        $('#borrowedBooks').html(rows);
                    },
                    error: function () {
                        alert('Failed to load borrowed books.');
                    },
                    complete: function () {
                        hideLoading();
                    }
                });
            }

            // Function to Return a Book
            function returnBook(barcode) {
                showLoading();
                $.ajax({
                    url: "{{ route('books.return') }}", // Backend route
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        barcode: barcode
                    },
                    success: function (response) {
                        alert(response.message);
                        loadBorrowedBooks(); // Reload the list
                    },
                    error: function (xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                    },
                    complete: function () {
                        hideLoading();
                    }
                });
            }

            // Button to Scan Another Book
            document.getElementById('returnAnotherButton').addEventListener('click', function () {
                document.getElementById('result').textContent = ''; // Clear scanned result
                startScanning(codeReader, selectedDeviceId); // Restart scanning
            });

            function showLoading() {
                document.querySelector('.loading').style.display = 'flex';
            }

            function hideLoading() {
                document.querySelector('.loading').style.display = 'none';
            }

            loadBorrowedBooks(); // Initial load
        });
    </script>
@endsection