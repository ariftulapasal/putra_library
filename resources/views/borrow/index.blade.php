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
                        <h1>Borrow Book</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Borrow Book</li>
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
                        <!-- Borrow Book Card -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-qrcode"></i> Scan Book QR Code</h3>
                            </div>
                            <div class="card-body">
                                <!-- Display Messages -->
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if(session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                <!-- QR Scanner -->
                                <div class="camera-container text-center mb-4">
                                    <video id="video" autoplay muted class="border"></video>
                                    <canvas id="canvas" class="d-none"></canvas>
                                </div>

                                <form id="borrowForm" action="{{ route('borrow.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="isbn" name="isbn" required>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Borrow Book
                                    </button>
                                </form>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@zxing/library@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Sparkline
        const sparklineElement = document.getElementById('sparkline-1');
        if (sparklineElement) {
            $('#sparkline-1').sparkline([10, 12, 9, 7, 5], {
                type: 'line',
                height: '40',
                width: '100%',
                lineColor: '#3c8dbc',
                fillColor: '#f3f3f3',
                spotColor: '#3c8dbc'
            });
        }
    
        // Initialize Vector Map
        const worldMapElement = document.getElementById('world-map');
        if (worldMapElement) {
            $('#world-map').vectorMap({
                map: 'world_en',
                backgroundColor: 'transparent',
                color: '#f4f4f4',
                hoverOpacity: 0.7,
                selectedColor: '#666666',
                enableZoom: true,
                showTooltip: true,
                scaleColors: ['#C8EEFF', '#006491'],
                normalizeFunction: 'polynomial'
            });
        }
    });
    </script>
    
<script>
    const codeReader = new ZXing.BrowserQRCodeReader();
    const videoElement = document.getElementById('video');
    const isbnInput = document.getElementById('isbn');

    // Start scanning
    codeReader.decodeFromVideoDevice(null, videoElement, (result, error) => {
        if (result) {
            isbnInput.value = result.text;
            alert('QR Code Detected: ' + result.text);
        }
        if (error && !(error instanceof ZXing.NotFoundException)) {
            console.error(error);
        }
    });
</script>
@endsection
