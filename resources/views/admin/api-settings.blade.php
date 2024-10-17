<!DOCTYPE html>
<html>

<head>
    <title>Upload Model File</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .settings-form {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .success-message {
            text-align: center;
            color: green;
            margin-bottom: 20px;
        }

        .error-message {
            text-align: center;
            color: red;
            margin-bottom: 20px;
        }

        @media (max-width: 600px) {
            .settings-form {
                width: 90%;
            }
        }
    </style>
</head>

<body>
    <h1>อัปโหลดโมเดล</h1>

    <div class="settings-form">
        @if (session('success'))
        <p class="success-message">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
        <p class="error-message">{{ $errors->first() }}</p>
        @endif

        <!-- ฟอร์มอัปโหลดโมเดล -->
        <form action="{{ route('api.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="model_file">Upload Model File:</label>
            <input type="file" name="model_file" required>

            <button type="submit">อัปโหลด</button>
        </form>
    </div>
</body>

</html>