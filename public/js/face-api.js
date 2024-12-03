import * as faceapi from '@vladmandic/face-api';

async function startFaceRecognition() {
    await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
    await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
    await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
    await faceapi.nets.faceExpressionNet.loadFromUri('/models');

    const video = document.getElementById('video');
    navigator.mediaDevices.getUserMedia({ video: {} })
        .then(stream => { video.srcObject = stream; })
        .catch(console.error);
}

document.addEventListener("DOMContentLoaded", startFaceRecognition);

const captureFace = async () => {
    const video = document.getElementById('video');
    const canvas = document.createElement('canvas');
    const displaySize = { width: video.width, height: video.height };

    faceapi.matchDimensions(canvas, displaySize);
    const detections = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();

    if (!detections) {
        alert("Face not detected. Please try again.");
        return;
    }

    const faceDescriptor = detections.descriptor;
    return faceDescriptor;
};

const sendFaceDescriptor = async (faceDescriptor) => {
    const response = await fetch('/api/register-face', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ faceDescriptor })
    });

    const result = await response.json();
    if (result.success) {
        alert('Face registered successfully!');
    } else {
        alert('Registration failed');
    }
};

document.getElementById('register').addEventListener('click', async () => {
    const faceDescriptor = await captureFace();
    if (faceDescriptor) {
        sendFaceDescriptor(faceDescriptor);
    }
});
