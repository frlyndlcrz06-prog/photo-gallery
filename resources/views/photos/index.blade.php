<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Week 13 - Photo Gallery</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            color: #333;
        }

        header {
            background: #34495e;
            color: white;
            text-align: center;
            padding: 25px;
        }

        header h1 {
            margin: 0 0 8px;
        }

        .container {
            max-width: 1050px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .upload-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 35px;
            box-shadow: 0 2px 8px #00000010;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background: #2980b9;
            color: white;
            border: none;
            padding: 11px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #21618c;
        }

        .error {
            color: #c0392b;
            font-size: 14px;
            margin: -8px 0 15px;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .success {
            background: #d5f5e3;
            color: #196f3d;
        }

        .danger {
            background: #fadbd8;
            color: #922b21;
        }

        .gallery {
            display: grid;
            grid-template-columns:
                repeat(auto-fill, minmax(230px, 1fr));
            gap: 20px;
        }

        .photo-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px #00000010;
        }

        .photo-card img {
            width: 100%;
            height: 190px;
            object-fit: cover;
        }

        .photo-info {
            padding: 15px;
        }

        .caption {
            font-weight: bold;
            margin: 0 0 10px;
            overflow-wrap: anywhere;
        }

        .details {
            font-size: 12px;
            color: #777;
            overflow-wrap: anywhere;
        }

        .delete-btn {
            background: #e74c3c;
            margin-top: 12px;
        }

        .delete-btn:hover {
            background: #c0392b;
        }

        .empty {
            text-align: center;
            color: #777;
            padding: 35px;
            background: white;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<header>
    <h1>My Photo Gallery</h1>
    <p>Week 13 - File Uploads and Storage</p>
</header>

<div class="container">

    @if(session('success'))
        <div class="alert success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="upload-box">
        <h2>Upload a Photo</h2>

        <form action="{{ route('photos.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <label for="caption">Photo Caption</label>

            <input
                type="text"
                id="caption"
                name="caption"
                value="{{ old('caption') }}"
                placeholder="Enter a caption"
                maxlength="255"
            >

            @error('caption')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="image">Choose an Image</label>

            <input
                type="file"
                id="image"
                name="image"
                accept="image/jpeg,image/png,image/gif,image/webp"
            >

            @error('image')
                <p class="error">{{ $message }}</p>
            @enderror

            <p class="details">
                Allowed: JPG, PNG, GIF, WEBP.
                Maximum file size: 2 MB.
            </p>

            <button type="submit">
                Upload Photo
            </button>

        </form>
    </div>

    <h2>Photo Gallery</h2>

    @if($photos->isEmpty())

        <div class="empty">
            No photos uploaded yet.
        </div>

    @else

        <div class="gallery">

            @foreach($photos as $photo)

                <div class="photo-card">

                    <img
                        src="{{ Storage::disk('public')->url($photo->path) }}"
                        alt="{{ $photo->caption }}"
                    >

                    <div class="photo-info">

                        <p class="caption">
                            {{ $photo->caption }}
                        </p>

                        <p class="details">
                            Original file:
                            {{ $photo->original_name }}
                        </p>

                        <p class="details">
                            Size:
                            {{ number_format($photo->size / 1024, 2) }}
                            KB
                        </p>

                        <p class="details">
                            Uploaded:
                            {{ $photo->created_at->format('M d, Y h:i A') }}
                        </p>

                        <form
                            action="{{ route('photos.destroy', $photo) }}"
                            method="POST"
                            onsubmit="return confirm('Delete this photo?')"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="delete-btn"
                            >
                                Delete Photo
                            </button>

                        </form>

                    </div>
                </div>

            @endforeach

        </div>

    @endif

</div>

</body>
</html>
