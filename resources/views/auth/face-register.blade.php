<!DOCTYPE html>
<html>
<head>
    <title>Face Recognition Registration</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/face-api.js"></script>
    <script src="{{ url("js/face-api.min.js") }}"></script>
    <style>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Face Recognition Registration</h1>
        
        <div class="loading">Processing... Please wait...</div>
        
        <form id="registrationForm">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="camera-container">
                <video id="video" autoplay muted></video>
                <canvas id="canvas"></canvas>
            </div>

            <button type="button" id="switchCamera">Switch Camera</button>
            <button type="button" id="captureFace">Capture Face</button>
            <button type="submit" id="submitButton" disabled>Register</button>
        </form>

        <div id="errorMessage" class="error-message"></div>
        <div id="successMessage" class="success-message"></div>
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
                // Get available video devices
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