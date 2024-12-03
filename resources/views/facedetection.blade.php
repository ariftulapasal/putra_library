<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face API</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: darkslategray;
        }
        canvas {
            position: absolute;
        }
    </style>
</head>
<body>
    <video id="video" autoplay></video>

    <script src="{{ url("js/face-api.min.js") }}"></script>
    <script src="{{ url("js/script.js")}}"></script>
</body>
</html>