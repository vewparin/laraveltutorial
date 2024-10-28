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
            font-size: 3rem;
            /* ปรับขนาดฟอนต์ให้ใหญ่ขึ้น */
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
            font-size: 1.2rem;
            /* เพิ่มขนาดฟอนต์ของลิงก์ */
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

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: auto;
        }

        .login-container h1 {
            font-size: 2.25rem;
            /* เพิ่มขนาดฟอนต์ของหัวข้อ */
            font-weight: bold;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .form-control {
            margin-bottom: 1rem;
            border-radius: 5px;
            height: 45px;
            font-size: 1.1rem;
            /* เพิ่มขนาดฟอนต์ใน input */
        }

        .btn-continue {
            background-color: #28a745;
            color: #fff;
            font-size: 1.2rem;
            /* เพิ่มขนาดฟอนต์ในปุ่ม Continue */
            padding: 0.75rem;
            border-radius: 5px;
            width: 100%;
            margin-top: 1rem;
        }

        .btn-continue:hover {
            background-color: #218838;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            font-size: 1.1rem;
            /* เพิ่มขนาดฟอนต์ของ divider */
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #ddd;
        }

        .divider:not(:empty)::before {
            margin-right: .5em;
        }

        .divider:not(:empty)::after {
            margin-left: .5em;
        }

        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color:#c9302c;
            color:#fff;
            font-size: 1.2rem;
            /* เพิ่มขนาดฟอนต์ในปุ่ม Google */
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-google svg {
            width: 24px;
            /* ปรับขนาดโลโก้ให้ใหญ่ขึ้น */
            margin-right: 0.5rem;
        }

        .btn-google:hover {
            background-color: #f7f7f7;
        }

        .sign-up-link {
            margin-top: 1rem;
            font-size: 1.1rem;
            /* เพิ่มขนาดฟอนต์ในลิงก์สมัคร */
        }

        .sign-up-link a {
            color: #28a745;
            text-decoration: none;
        }

        .sign-up-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body class="h-100 bg-light">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column mb-4 border border-1">
        <header class="mb-auto">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Semantic Analyze</h3>
                <nav class="nav nav-masthead">
                    <a class="nav-link" aria-current="page" href="{{ route('welcome') }}">Home</a>
                    <a class="nav-link active" href="{{ route('input.text.form') }}">ประมวลผลการวิเคราะห์ทีละข้อความ</a>
                    <a class="nav-link" href="{{ route('csv.upload.form') }}">อัพโหลดไฟล์ CSV</a>
                    <a class="nav-link" href="{{ route('comments.analysis.results') }}">ผลลัพธ์การประมวลผล</a>
                </nav>
            </div>
        </header>
    </div>

    <div class="login-container">
        <h1>เข้าสู่ระบบ</h1>
        <input type="email" class="form-control" placeholder="Email address*" required>
        <button class="btn btn-continue">Continue</button>
        <div class="sign-up-link">
            Don't have an account? <a href="#">Sign Up</a>
        </div>
        <div class="divider">OR</div>
        <a href="{{ route('google-auth') }}" class="btn-google">

            Continue with Google
        </a>
    </div>
</body>

</html>