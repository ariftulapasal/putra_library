<!DOCTYPE html>
<html>
<head>
    <title>Borrow Book</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/@zxing/library@latest"></script>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .scanner-container {
            position: relative;
            width: 640px;
            margin: 20px auto;
        }
        #video {
            width: 100%;
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
        .result-container {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: none;
        }
        .book-info {
            margin: 10px 0;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Borrow Book</h1>
        
        <div class="loading">Processing... Please wait...</div>

        <div class="scanner-container">
            <video id="video"></video>
        </div>

        <div class="manual-entry">
            <h3>Or Enter Barcode Manually:</h3>
            <input type="text" id="manualBarcode" placeholder="Enter barcode">
            <button onclick="processManualBarcode()">Submit</button>
        </div>

        <div id="errorMessage" class="error-message"></div>
        <div id="successMessage" class="success-message"></div>

        <div id="resultContainer" class="result-container">
            <h2>Borrow Details</h2>
            <div id="bookInfo" class="book-info"></div>
        </div>
    </div>

    <script>
        let selectedDeviceId;
        const codeReader = new ZXing.BrowserMultiFormatReader();

        // Start the barcode scanner
        async function startScanner() {
            try {
                const videoInputDevices = await ZXing.BrowserMultiFormatReader.listVideoInputDevices();
                
                if (videoInputDevices.length === 0) {
                    throw new Error('No camera devices found');
                }

                // Use the first device by default
                selectedDeviceId = videoInputDevices[0].deviceId;

                // Start continuous scanning
                codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', (result, err) => {
                    if (result) {
                        processBarcode(result.text);
                    }
                    if (err && !(err instanceof ZXing.NotFoundException)) {
                        console.error(err);
                    }
                });

            } catch (err) {
                handleError('Error accessing camera: ' + err.message);
            }
        }

        async function processBarcode(barcode) {
            // Temporarily stop scanning
            codeReader.reset();

            try {
                showLoading();
                const response = await fetch('/borrow/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        barcode: barcode,
                        user_id: 1 // Since we're not verifying users, using a default user_id
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    showSuccess(data.message);
                    displayBookInfo(data.book);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                handleError(error.message);
            } finally {
                hideLoading();
                // Restart scanning after 3 seconds
                setTimeout(() => {
                    startScanner();
                }, 3000);
            }
        }

        function processManualBarcode() {
            const barcode = document.getElementById('manualBarcode').value;
            if (barcode) {
                processBarcode(barcode);
            } else {
                handleError('Please enter a barcode');
            }
        }

        function displayBookInfo(book) {
            const bookInfo = document.getElementById('bookInfo');
            bookInfo.innerHTML = `
                <p><strong>Title:</strong> ${book.title}</p>
                <p><strong>Due Date:</strong> ${book.due_date}</p>
            `;
            document.getElementById('resultContainer').style.display = 'block';
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

        // Start scanner when page loads
        document.addEventListener('DOMContentLoaded', startScanner);

        // Cleanup when page is closed
        window.addEventListener('beforeunload', () => {
            codeReader.reset();
        });
    </script>
</body>
</html>