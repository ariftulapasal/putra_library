// Select video element and set up canvas
let video = document.getElementById("video");
let canvas = document.body.appendChild(document.createElement("canvas"));
let ctx = canvas.getContext("2d");
const width = 720;
const height = 560;
let displaySize = { width, height };

// Start video stream function
const startStream = async () => {
    try {
        console.log("----- START STREAM ------");
        const stream = await navigator.mediaDevices.getUserMedia({
            video: { width, height },
            audio: false
        });
        video.srcObject = stream;
    } catch (error) {
        console.error("Error accessing the camera:", error);
        alert("Please allow camera access for face recognition.");
    }
};

// Load models and start video streaming
const loadModelsAndStartStream = async () => {
    try {
        console.log("----- START LOAD MODEL ------");
        await Promise.all([
            faceapi.nets.ageGenderNet.loadFromUri('/models'),
            faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
            faceapi.nets.faceExpressionNet.loadFromUri('/models')
        ]);
        await startStream();
    } catch (error) {
        console.error("Error loading models:", error);
    }
};

// Detect face attributes function
async function detect() {
    const detections = await faceapi
        .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
        .withFaceLandmarks()
        .withFaceExpressions()
        .withAgeAndGender();

    // Clear canvas and match display size
    ctx.clearRect(0, 0, width, height);
    const resizedDetections = faceapi.resizeResults(detections, displaySize);

    // Draw detections on canvas
    faceapi.draw.drawDetections(canvas, resizedDetections);
    faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
    faceapi.draw.drawFaceExpressions(canvas, resizedDetections);

    // Display age and gender data
    resizedDetections.forEach(result => {
        const { age, gender, genderProbability } = result;
        new faceapi.draw.DrawTextField(
            [
                `${Math.round(age)} years`,
                `${gender} (${(genderProbability * 100).toFixed(1)}%)`
            ],
            result.detection.box.bottomRight
        ).draw(canvas);
    });
}

// Start detection when video is playing
video.addEventListener('play', () => {
    console.log("----- VIDEO PLAYING ------");
    faceapi.matchDimensions(canvas, displaySize);
    setInterval(detect, 100);
});

// Load models and start streaming
loadModelsAndStartStream();


// let video = document.getElementById("video");
// let canvas = document.body.appendChild(document.createElement("canvas"));
// let ctx = canvas.getContext("2d");
// let displaySize;

// let width = 720;
// let height = 560;

// const startSteam = () => {
//     console.log("----- START STEAM ------");
//     navigator.mediaDevices.getUserMedia({
//         video: {width, height},
//         audio : false
//     }).then((steam) => {video.srcObject = steam});
// }

// console.log(faceapi.nets);

// console.log("----- START LOAD MODEL ------");
// Promise.all([
//     faceapi.nets.ageGenderNet.loadFromUri('models'),
//     faceapi.nets.ssdMobilenetv1.loadFromUri('models'),
//     faceapi.nets.tinyFaceDetector.loadFromUri('models'),
//     faceapi.nets.faceLandmark68Net.loadFromUri('models'),
//     faceapi.nets.faceRecognitionNet.loadFromUri('models'),
//     faceapi.nets.faceExpressionNet.loadFromUri('models')
// ]).then(startSteam);


// async function detect() {
//     const detections = await faceapi.detectAllFaces(video)
//                                 .withFaceLandmarks()
//                                 .withFaceExpressions()
//                                 .withAgeAndGender();
//     //console.log(detections);
    
//     ctx.clearRect(0,0, width, height);
//     const resizedDetections = faceapi.resizeResults(detections, displaySize)
//     faceapi.draw.drawDetections(canvas, resizedDetections);
//     faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
//     faceapi.draw.drawFaceExpressions(canvas, resizedDetections);

//     console.log(resizedDetections);
//     resizedDetections.forEach(result => {
//         const {age, gender, genderProbability} = result;
//         new faceapi.draw.DrawTextField ([
//             `${Math.round(age,0)} Tahun`,
//             `${gender} ${Math.round(genderProbability)}`
//         ],
//         result.detection.box.bottomRight
//         ).draw(canvas);
//     });
// }

// video.addEventListener('play', ()=> {
//     displaySize = {width, height};
//     faceapi.matchDimensions(canvas, displaySize);

//     setInterval(detect, 100);
// })