<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facial Recognition</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        #camera {
            max-width: 400px;
            margin: 0 auto;
        }
        #video {
            width: 100%;
            height: auto;
        }
        canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-5">Facial Recognition System</h1>
        
        <!-- Tabs for Registration and Login -->
        <ul class="nav nav-pills mb-4 justify-content-center" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="registro-tab" data-bs-toggle="tab" data-bs-target="#Registro" type="button" role="tab">
                    <i class="fas fa-user-plus me-2"></i>Register
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#Login" type="button" role="tab">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="myTabContent">
            <!-- User Registration -->
            <div class="tab-pane fade" id="Registro" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Add User</h2>
                        <form id="user-form" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">Username:</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm-password" class="form-label">Confirm Password:</label>
                                <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo:</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="submit-button">
                                <i class="fas fa-user-plus me-2"></i>Add User
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Login (Manual and Facial Recognition) -->
            <div class="tab-pane fade show active" id="Login" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Login</h2>
                        <form id="login-form" class="mb-4">
                            <div class="mb-3">
                                <label for="login-name" class="form-label">Username:</label>
                                <input type="text" class="form-control" id="login-name" required>
                            </div>
                            <div class="mb-3">
                                <label for="login-password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="login-password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </form>
                        
                        <hr>
                        
                        <h3 class="mb-3">Facial Recognition</h3>
                        <button id="start-camera" class="btn btn-secondary w-100 mb-4">
                            <i class="fas fa-camera me-2"></i>Activate Camera
                        </button>
                        <div id="camera" class="mb-4 position-relative" style="display: none;">
                            <video id="video" autoplay muted></video>
                            <canvas id="canvas"></canvas>
                        </div>
                        <div id="recognition-result" class="alert alert-success" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button onclick="requestCameraAccess()">Enable Camera</button>
    <video autoplay></video>


    <!-- Toast container -->
    <div class="toast-container position-fixed p-3" style="z-index: 11">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="toastTitle">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage"></div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js/dist/face-api.min.js"></script>
    <script defer src="script.js"></script>
    <script>
        document.getElementById('start-camera').addEventListener('click', function() {
            document.getElementById('camera').style.display = 'block';
            startFacialLogin();
        });

        // Function to show welcome message
        function showWelcomeMessage(name) {
            const resultDiv = document.getElementById('recognition-result');
            resultDiv.innerHTML = `<i class="fas fa-check-circle me-2"></i>Welcome to the system, ${name}`;
            resultDiv.style.display = 'block';
        }

        document.getElementById('login-form').addEventListener('submit', async function(event) {
            event.preventDefault();
            const username = document.getElementById('login-name').value;
            const password = document.getElementById('login-password').value;

            const response = await fetch('/login-manual', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            });

            const result = await response.json();
            if (response.ok) {
                showToast('Success', result.message, 'success');
            } else {
                showToast('Error', 'User not recognized', 'error');
            }
        });
    </script>
</body>
</html>
