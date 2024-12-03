<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Recognition Login</title>
    <style>
      body { display: flex; justify-content: center; align-items: center; height: 100vh; background-color: darkslategray; color: white; }
      .video-container { position: relative; width: 600px; height: 450px; }
      video { width: 100%; height: 100%; }
      canvas { position: absolute; top: 0; left: 0; }
    </style>
    <script defer src="{{ asset('js/face-api.min.js') }}"></script>
    <script defer src="{{ asset('js/script.js') }}"></script>
</head>
<body>
    <h1>Face Recognition Login</h1>
    <div class="video-container">
        <video id="video" autoplay playsinline></video>
        <canvas id="canvas"></canvas>
    </div>
    <button onclick="startRecognition()">Start Recognition</button>
</body>
</html>
