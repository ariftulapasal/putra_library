<!DOCTYPE html>
<html lang="en">
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Face Recognition Login</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/face-api.js"></script>
    <script src="{{ url("js/face-api.min.js") }}"></script>
    {{-- <style>
        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .camera-container {
            position: relative;
            width: 640px;
            margin: 20px auto;
        }
        #video {
            width: 100%;
        }
        #canvas {
            position: absolute;
            top: 0;
            left: 0;
        }
        .error-message {
            color: red;
            margin: 10px 0;
            display: none;
        }
        .success-message {
            color: green;
            margin: 10px 0;
            display: none;
        }
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
        }
        .fallback-login {
            display: none;
            margin-top: 20px;
        }
        .camera-error {
            text-align: center;
            color: red;
            margin: 20px 0;
            display: none;
        }
    </style> --}}
    <style>
        body {
            background: linear-gradient(to bottom right, #f5f7fa, #c3cfe2);
            font-family: 'Source Sans Pro', sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .logo {
            display: block;
            margin: 0 auto 20px auto;
            width: 120px;
            height: auto;
        }
        h1 {
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .camera-container {
            position: relative;
            width: 640px;
            margin: 0 auto 20px auto;
        }
        #video {
            width: 100%;
            border-radius: 5px;
            border: 3px solid #444;
        }
        #canvas {
            position: absolute;
            top: 0;
            left: 0;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #0056b3;
        }
        .error-message, .success-message {
            text-align: center;
            margin: 10px 0;
            font-weight: bold;
        }
        .error-message {
            color: red;
        }
        .success-message {
            color: green;
        }
        .fallback-login {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    {{-- <div class="container">
        <h1>Face Recognition Login</h1>
        
        <div class="loading">Processing... Please wait...</div>
        
        <div class="camera-error" id="cameraError">
            Camera access error. Please check your camera permissions or use email login.
        </div>

        <div class="camera-container">
            <video id="video" autoplay muted></video>
            <canvas id="canvas"></canvas>
        </div>

        <button type="button" id="switchCamera">Switch Camera</button>
        
        <div id="errorMessage" class="error-message"></div>
        <div id="successMessage" class="success-message"></div>

        <button type="button" id="showFallback">Login with Email Instead</button>

        <!-- Fallback Login Form -->
        <div id="fallbackLogin" class="fallback-login">
            <h2>Login with Email</h2>
            <form id="fallbackForm">
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit">Login</button>
            </form>
        </div>
    </div> --}}
    <div class="container">
        <!-- Updated Logo Path -->
        <img src="{{ url('dist/img/UPM.png') }}" alt="Logo" class="logo">
        <h1>Face Recognition Login</h1>

        <div id="cameraError" class="error-message" style="display: none;">
            Camera access error. Please check your camera permissions or use email login.
        </div>

        <div class="camera-container">
            <video id="video" autoplay muted></video>
            <canvas id="canvas"></canvas>
        </div>

        <button id="switchCamera" class="btn">Switch Camera</button>

        <div id="errorMessage" class="error-message" style="display: none;"></div>
        <div id="successMessage" class="success-message" style="display: none;"></div>

        <button id="showFallback" class="btn">Login with Email Instead</button>

        <!-- Fallback Login Form -->
        <div id="fallbackLogin" class="fallback-login" style="display: none;">
            <h2>Login with Email</h2>
            <form id="fallbackForm">
                <input type="email" id="email" name="email" placeholder="Email" class="form-control" required>
                <input type="password" id="password" name="password" placeholder="Password" class="form-control mt-3" required>
                <button type="submit" class="btn mt-3">Login</button>
            </form>
        </div>
    </div>

    <div class="loading" id="loading">
        Processing... Please wait...
    </div>

    <script>
        let currentStream;
        let availableDevices = [];
        let currentDeviceIndex = 0;
        let isProcessing = false;

        // Load face-api models
        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/models')
        ]).then(startVideo).catch(handleCameraError);

        async function startVideo() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                availableDevices = devices.filter(device => device.kind === 'videoinput');

                if (availableDevices.length === 0) {
                    throw new Error('No camera devices found');
                }

                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                }

                currentStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        deviceId: availableDevices[currentDeviceIndex].deviceId
                    }
                });
                
                const video = document.getElementById('video');
                video.srcObject = currentStream;
                startFaceDetection();
                
                document.getElementById('cameraError').style.display = 'none';
                document.getElementById('switchCamera').style.display = 
                    availableDevices.length > 1 ? 'block' : 'none';
                
            } catch (err) {
                handleCameraError(err);
            }
        }

        async function startFaceDetection() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            canvas.width = video.width;
            canvas.height = video.height;
            const ctx = canvas.getContext('2d');

            setInterval(async () => {
                if (isProcessing) return;

                try {
                    const detections = await faceapi.detectAllFaces(video, 
                        new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptors();

                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    if (detections.length > 0) {
                        // Draw detections
                        faceapi.draw.drawDetections(canvas, detections);

                        // Only attempt login if we detect exactly one face
                        if (detections.length === 1 && !isProcessing) {
                            isProcessing = true;
                            await verifyFace(Array.from(detections[0].descriptor));
                            isProcessing = false;
                        } else if (detections.length > 1) {
                            handleError('Multiple faces detected. Please ensure only one face is visible.');
                        }
                    }
                } catch (error) {
                    console.error('Face detection error:', error);
                }
            }, 100);
        }

        async function verifyFace(faceDescriptor) {
            try {
                showLoading();
                const response = await fetch('/face-login/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        face_descriptor: JSON.stringify(faceDescriptor)
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    showSuccess(data.message);
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                handleError(error.message);
            } finally {
                hideLoading();
            }
        }

        // Event Listeners
        document.getElementById('switchCamera').addEventListener('click', () => {
            currentDeviceIndex = (currentDeviceIndex + 1) % availableDevices.length;
            startVideo();
        });

        document.getElementById('showFallback').addEventListener('click', () => {
            document.getElementById('fallbackLogin').style.display = 'block';
        });

        document.getElementById('fallbackForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            try {
                showLoading();
                const response = await fetch('/face-login/fallback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                handleError(error.message);
            } finally {
                hideLoading();
            }
        });

        // Utility Functions
        function handleCameraError(error) {
            console.error('Camera error:', error);
            document.getElementById('cameraError').style.display = 'block';
            document.getElementById('video').style.display = 'none';
            document.getElementById('canvas').style.display = 'none';
            document.getElementById('switchCamera').style.display = 'none';
            document.getElementById('fallbackLogin').style.display = 'block';
        }

        function handleError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 0);
        }

        function showSuccess(message) {
            const successDiv = document.getElementById('successMessage');
            successDiv.textContent = message;
            successDiv.style.display = 'block';
        }

        function showLoading() {
            document.querySelector('.loading').style.display = 'flex';
        }

        function hideLoading() {
            document.querySelector('.loading').style.display = 'none';
        }
    </script>
    {{-- <script>
        let currentStream;
        let availableDevices = [];
        let currentDeviceIndex = 0;
        let isProcessing = false;

        // Load face-api models
        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/models')
        ]).then(startVideo).catch(handleCameraError);

        async function startVideo() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                availableDevices = devices.filter(device => device.kind === 'videoinput');

                if (availableDevices.length === 0) {
                    throw new Error('No camera devices found');
                }

                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                }

                currentStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        deviceId: availableDevices[currentDeviceIndex]?.deviceId || undefined
                    }
                });

                const video = document.getElementById('video');
                video.srcObject = currentStream;
                startFaceDetection();

                document.getElementById('cameraError').style.display = 'none';
                document.getElementById('switchCamera').style.display =
                    availableDevices.length > 1 ? 'block' : 'none';
            } catch (err) {
                handleCameraError(err);
            }
        }

        async function startFaceDetection() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            canvas.width = video.width;
            canvas.height = video.height;
            const ctx = canvas.getContext('2d');

            setInterval(async () => {
                if (isProcessing) return;

                try {
                    const detections = await faceapi.detectAllFaces(video,
                        new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptors();

                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    if (detections.length > 0) {
                        faceapi.draw.drawDetections(canvas, detections);

                        if (detections.length === 1 && !isProcessing) {
                            isProcessing = true;
                            await verifyFace(Array.from(detections[0].descriptor));
                            isProcessing = false;
                        }
                    }
                } catch (error) {
                    console.error('Face detection error:', error);
                }
            }, 100);
        }

        async function verifyFace(faceDescriptor) {
            try {
                showLoading();
                const response = await fetch('/face-login/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ face_descriptor: JSON.stringify(faceDescriptor) })
                });

                const data = await response.json();

                if (data.success) {
                    showSuccess(data.message);
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                handleError(error.message);
            } finally {
                hideLoading();
            }
        }

        function handleCameraError(error) {
            console.error('Camera error:', error);
            document.getElementById('cameraError').textContent = error.message || 'Camera access error. Please check your permissions.';
            document.getElementById('cameraError').style.display = 'block';
            document.getElementById('video').style.display = 'none';
            document.getElementById('canvas').style.display = 'none';
            document.getElementById('switchCamera').style.display = 'none';
            document.getElementById('fallbackLogin').style.display = 'block';
        }

        function showSuccess(message) {
            const successDiv = document.getElementById('successMessage');
            successDiv.textContent = message;
            successDiv.style.display = 'block';
        }

        function showLoading() {
            const loadingDiv = document.getElementById('loading');
            if (loadingDiv) loadingDiv.style.display = 'flex';
        }

        function hideLoading() {
            const loadingDiv = document.getElementById('loading');
            if (loadingDiv) loadingDiv.style.display = 'none';
        }
    </script> --}}

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/face-api.js"></script> --}}

    {{-- <script>
        let currentStream;
        let availableDevices = [];
        let currentDeviceIndex = 0;
        let isProcessing = false;

        // Load face-api models
        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/models')
        ]).then(startVideo).catch(handleCameraError);

        async function startVideo() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                availableDevices = devices.filter(device => device.kind === 'videoinput');

                if (availableDevices.length === 0) {
                    throw new Error('No camera devices found');
                }

                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                }

                currentStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        deviceId: availableDevices[currentDeviceIndex].deviceId
                    }
                });
                
                const video = document.getElementById('video');
                video.srcObject = currentStream;
                startFaceDetection();
                
                document.getElementById('cameraError').style.display = 'none';
                document.getElementById('switchCamera').style.display = 
                    availableDevices.length > 1 ? 'block' : 'none';
                
            } catch (err) {
                handleCameraError(err);
            }
        }

        async function startFaceDetection() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            canvas.width = video.width;
            canvas.height = video.height;
            const ctx = canvas.getContext('2d');

            setInterval(async () => {
                if (isProcessing) return;

                try {
                    const detections = await faceapi.detectAllFaces(video, 
                        new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptors();

                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    if (detections.length > 0) {
                        // Draw detections
                        faceapi.draw.drawDetections(canvas, detections);

                        // Only attempt login if we detect exactly one face
                        if (detections.length === 1 && !isProcessing) {
                            isProcessing = true;
                            await verifyFace(Array.from(detections[0].descriptor));
                            isProcessing = false;
                        } else if (detections.length > 1) {
                            handleError('Multiple faces detected. Please ensure only one face is visible.');
                        }
                    }
                } catch (error) {
                    console.error('Face detection error:', error);
                }
            }, 100);
        }

        async function verifyFace(faceDescriptor) {
            try {
                showLoading();
                const response = await fetch('/face-login/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        face_descriptor: JSON.stringify(faceDescriptor)
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    showSuccess(data.message);
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                handleError(error.message);
            } finally {
                hideLoading();
            }
        }

        // Event Listeners
        document.getElementById('switchCamera').addEventListener('click', () => {
            currentDeviceIndex = (currentDeviceIndex + 1) % availableDevices.length;
            startVideo();
        });

        document.getElementById('showFallback').addEventListener('click', () => {
            document.getElementById('fallbackLogin').style.display = 'block';
        });

        document.getElementById('fallbackForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            try {
                showLoading();
                const response = await fetch('/face-login/fallback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                handleError(error.message);
            } finally {
                hideLoading();
            }
        });

        // Utility Functions
        function handleCameraError(error) {
            console.error('Camera error:', error);
            document.getElementById('cameraError').style.display = 'block';
            document.getElementById('video').style.display = 'none';
            document.getElementById('canvas').style.display = 'none';
            document.getElementById('switchCamera').style.display = 'none';
            document.getElementById('fallbackLogin').style.display = 'block';
        }

        function handleError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        }

        function showSuccess(message) {
            const successDiv = document.getElementById('successMessage');
            successDiv.textContent = message;
            successDiv.style.display = 'block';
        }

        function showLoading() {
            document.querySelector('.loading').style.display = 'flex';
        }

        function hideLoading() {
            document.querySelector('.loading').style.display = 'none';
        }
    </script> --}}

    <!-- jQuery -->
    <script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('dist/js/adminlte.min.js') }}"></script>
    
    @include('layouts.footer')

</body>

</html>