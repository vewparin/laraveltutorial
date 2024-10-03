<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>


        .container {
            max-width: 75%;
            padding: 30px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        h1 {
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: bold;
            color: #f8f9fa;
        }

        .btn-danger {
            background-color: #d9534f;
            border: none;
            padding: 10px;
            font-size: 1.2rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c9302c;
        }

        .nav-masthead .nav-link {
            font-weight: 700;
            color: black;
            background-color: transparent;
            border-bottom: .25rem solid transparent;
        }

        .nav-masthead .nav-link:hover,
        .nav-masthead .nav-link:focus {
            border-bottom-color: rgba(0, 123, 255, .5);
        }

        .nav-masthead .active {
            color: black;
            border-bottom-color: aqua;
        }

        .nav-link:hover {
            background-color: burlywood;
        }
    </style>
</head>

<body class="h-100 bg-light">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column mb-4">
        <header class="mb-auto">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Semantic Analyze</h3>
                <nav class="nav nav-masthead">
                    <a class="nav-link" aria-current="page" href="{{ route('welcome') }}">Home</a>
                    <a class="nav-link" href="{{ route('csv.upload.form') }}">อัพโหลดไฟล์ CSV</a>
                    <a class="nav-link active" href="{{ route('input.text.form') }}">ประมวลผลการวิเคราะห์ทีละข้อความ</a>
                    <a class="nav-link" href="{{ route('comments.analysis.results') }}">ผลลัพธ์การประมวลผล</a>

                    <!-- @guest
                    <a href="{{ route('google-auth') }}" class="btn btn-primary ms-3">Login with Google</a>
                    @else
                    <span class="nav-link text-primary bg-white rounded-pill">Your name: {{ Auth::user()->name }}</span>
                    <a class="nav-link bg-danger text-white rounded-pill ms-3" href="{{ route('logout') }}">Logout</a>
                    @endguest -->
                </nav>
            </div>
        </header>
    </div>
    <div class="container mt-5 text-center">
        <h1>โปรดเข้าสู่ระบบก่อนเข้าใช้งานระบบ</h1>
        <a href="{{ route('google-auth') }}" class="btn btn-danger w-100">Login with Google</a>
    </div>
</body>

</html>