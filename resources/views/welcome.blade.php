<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
      body {
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100vh;
          margin: 0;
          background-color: darkslategray;
          color: white;
          flex-direction: column;
      }
      .video-container {
          position: relative;
          width: 600px;
          height: 450px;
      }
      video {
          width: 100%;
          height: 100%;
      }
      canvas {
          position: absolute;
          top: 10%; /* Center the smaller canvas */
          left: 30%;
          width: 40%; /* Make the canvas 80% of the video width */
          height: 80%; /* Make the canvas 80% of the video height */
      }
    </style>
    <script defer src="js/face-api.min.js"></script>
    <script defer src="js/script.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>

@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <body>
            <h1>Hello, this is a face recognition login system</h1>
            <div class="video-container">
                <video id="video" autoplay playsinline></video>
                <canvas id="canvas"></canvas>
            </div>
            <button onclick="requestCameraPermission()">Enable Camera</button>
        
            <script>
                async function requestCameraPermission() {
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                        document.querySelector('#video').srcObject = stream;
                    } catch (error) {
                        console.error("Camera permission denied or error occurred:", error);
                        alert("Camera permission is required for this feature.");
                    }
                }
            </script>
        </body>
    </div>
@endsection



</html>
