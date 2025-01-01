@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <body>
        <div class="form-container">
            <h1>Add New Book</h1>
            <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-layout">
                    <!-- Left Column - Book Details -->
                    <div class="form-column">
                        <div class="section-title">Book Information</div>
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" id="title" name="title" required value="{{ old('title') }}">
                            @error('title')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="author">Author:</label>
                            <input type="text" id="author" name="author" required value="{{ old('author') }}">
                            @error('author')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group half">
                                <label for="isbn">ISBN:</label>
                                <input type="text" id="isbn" name="isbn" required value="{{ old('isbn') }}">
                                @error('isbn')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group half">
                                <label for="barcode">Barcode:</label>
                                <input type="text" id="barcode" name="barcode" required value="{{ old('barcode') }}">
                                @error('barcode')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group half">
                                <label for="published_year">Published Year:</label>
                                <input type="number" id="published_year" name="published_year" required value="{{ old('published_year') }}">
                                @error('published_year')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group half">
                                <label for="status">Status:</label>
                                <select id="status" name="status" required>
                                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="borrowed" {{ old('status') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                                    <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                </select>
                                @error('status')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="category">Category:</label>
                            <input type="text" id="category" name="category" required value="{{ old('category') }}">
                            @error('category')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" rows="4" placeholder="Enter book description...">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column - Image Upload -->
                    <div class="form-column">
                        <div class="section-title">Book Cover</div>
                        <div class="image-upload-container">
                            <input type="file" 
                                   id="cover_image" 
                                   name="cover_image" 
                                   accept="image/jpeg,image/png,image/jpg"
                                   class="file-input"
                                   onchange="previewImage(this)"
                                   style="display: none;">
                            <div class="image-preview" id="imagePreview">
                                <img src="" alt="Image preview" class="preview-img" style="display: none;">
                                <span class="preview-text">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <br>
                                    Click or drag image here
                                </span>
                            </div>
                        </div>
                        <small class="help-text">Accepted formats: JPEG, PNG, JPG (max. 2MB)</small>
                        @error('cover_image')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">Add Book</button>
                    <a href="{{ route('books.index') }}" class="cancel-btn">Cancel</a>
                </div>
            </form>
        </div>

        <script>
            function previewImage(input) {
                const preview = document.querySelector('.preview-img');
                const previewText = document.querySelector('.preview-text');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                        previewText.style.display = 'none';
                    }

                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.style.display = 'none';
                    previewText.style.display = 'block';
                }
            }

            const imageUploadContainer = document.querySelector('.image-upload-container');
            const fileInput = document.querySelector('#cover_image');

            imageUploadContainer.addEventListener('click', () => {
                fileInput.click();
            });

            imageUploadContainer.addEventListener('dragover', (event) => {
                event.preventDefault();
                imageUploadContainer.style.borderColor = '#007bff';
            });

            imageUploadContainer.addEventListener('dragleave', () => {
                imageUploadContainer.style.borderColor = '#ddd';
            });

            imageUploadContainer.addEventListener('drop', (event) => {
                event.preventDefault();
                imageUploadContainer.style.borderColor = '#ddd';

                const files = event.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files; // Assign dropped file to the input
                    previewImage(fileInput);
                }
            });
        </script>
    </body>
</div>

<style>
    .content-wrapper {
        padding: 20px;
        min-height: 100vh;
        background: #f8f9fa;
    }

    .form-container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 25px;
    }

    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
        font-size: 24px;
    }

    .form-layout {
        display: flex;
        gap: 30px;
        margin-bottom: 20px;
    }

    .form-column {
        flex: 1;
    }

    .section-title {
        font-size: 18px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #eee;
    }

    .form-row {
        display: flex;
        gap: 15px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group.half {
        flex: 1;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
        color: #555;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: #007bff;
        outline: none;
    }

    .image-upload-container {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.3s;
    }

    .image-upload-container:hover {
        border-color: #007bff;
    }

    .image-preview {
        min-height: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 15px;
        background: #f8f9fa;
        border-radius: 4px;
    }

    .preview-img {
        max-width: 100%;
        max-height: 300px;
        object-fit: contain;
    }

    .preview-text {
        color: #666;
        font-size: 16px;
    }

    .preview-text i {
        font-size: 48px;
        margin-bottom: 10px;
        color: #007bff;
    }

    .help-text {
        display: block;
        color: #666;
        font-size: 12px;
        margin-top: 8px;
    }

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .submit-btn,
    .cancel-btn {
        padding: 10px 20px;
        font-size: 14px;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
    }

    .submit-btn {
        background: #007bff;
        color: white;
        border: none;
    }

    .submit-btn:hover {
        background: #0056b3;
    }

    .cancel-btn {
        background: #6c757d;
        color: white;
        border: none;
    }

    .cancel-btn:hover {
        background: #5a6268;
    }

    @media (max-width: 768px) {
        .form-layout {
            flex-direction: column;
        }

        .form-row {
            flex-direction: column;
        }

        .form-group.half {
            width: 100%;
        }
    }
</style>
@endsection
