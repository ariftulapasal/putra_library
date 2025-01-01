<!DOCTYPE html>
<html lang="en">
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Face Recognition Registration</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/face-api.js"></script>
    <script src="{{ url('js/face-api.min.js') }}"></script>
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
            height: 480px;
            margin: 0 auto 20px auto;
        }
        #video {
            width: 100%;
            height: 100%;
            border-radius: 5px;
            border: 3px solid #444;
        }
        #canvas {
            position: absolute;
            top: 0;
            left: 0;
        }
        .form-control {
            margin-bottom: 15px;
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
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ url('dist/img/UPM.png') }}" alt="Logo" class="logo">
        <h1>Face Recognition Registration</h1>

        <div class="loading">Processing... Please wait...</div>

        <form id="registrationForm">
            <input type="text" id="name" name="name" class="form-control" placeholder="Name" required>
            <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>

            <div class="camera-container">
                <video id="video" autoplay muted></video>
                <canvas id="canvas"></canvas>
            </div>

            <button type="button" id="switchCamera" class="btn">Switch Camera</button>
            <button type="button" id="captureFace" class="btn">Capture Face</button>
            <button type="submit" id="submitButton" class="btn" disabled>Register</button>
        </form>

        <div id="errorMessage" class="error-message" style="display: none;"></div>
        <div id="successMessage" class="success-message" style="display: none;"></div>
    </div>

    <script>
        let currentStream;
        let availableDevices = [];
        let currentDeviceIndex = 0;
        let capturedDescriptor = null;

        // Load face-api models
        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/models')
        ]).then(startVideo).catch(handleError);

        async function startVideo() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                availableDevices = devices.filter(device => device.kind === 'videoinput');

                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                }

                currentStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        deviceId: availableDevices[currentDeviceIndex].deviceId
                    }
                });

                document.getElementById('video').srcObject = currentStream;
            } catch (err) {
                handleError('Error accessing camera: ' + err.message);
            }
        }

        document.getElementById('switchCamera').addEventListener('click', () => {
            currentDeviceIndex = (currentDeviceIndex + 1) % availableDevices.length;
            startVideo();
        });

        document.getElementById('captureFace').addEventListener('click', async () => {
            try {
                showLoading();
                const video = document.getElementById('video');
                const detections = await faceapi.detectAllFaces(video, 
                    new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptors();

                if (detections.length === 0) {
                    throw new Error('No face detected. Please ensure your face is clearly visible.');
                }

                if (detections.length > 1) {
                    throw new Error('Multiple faces detected. Please ensure only your face is visible.');
                }

                capturedDescriptor = Array.from(detections[0].descriptor);
                document.getElementById('submitButton').disabled = false;
                showSuccess('Face captured successfully! You can now complete registration.');
            } catch (error) {
                handleError(error.message);
            } finally {
                hideLoading();
            }
        });

        document.getElementById('registrationForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!capturedDescriptor) {
                handleError('Please capture your face before registering.');
                return;
            }

            try {
                showLoading();
                const response = await fetch('/face-register/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: document.getElementById('name').value,
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value,
                        face_descriptor: JSON.stringify(capturedDescriptor)
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showSuccess(data.message);
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                handleError(error.message);
            } finally {
                hideLoading();
            }
        });

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
            setTimeout(() => {
                successDiv.style.display = 'none';
            }, 5000);
        }

        function showLoading() {
            document.querySelector('.loading').style.display = 'flex';
        }

        function hideLoading() {
            document.querySelector('.loading').style.display = 'none';
        }
    </script>
</body>
</html>
