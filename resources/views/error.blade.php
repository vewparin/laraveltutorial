<!-- resources/views/error.blade.php -->
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อผิดพลาดในการเข้าสู่ระบบ</title>
    <style>
        body{
            text-align: center;
            font-size: large;
        }
    </style>
</head>

<body class="h-100 bg-light ">
    <h1>ข้อผิดพลาดในการเข้าสู่ระบบ</h1>
    <p>{{ session('error_message') }}</p>
    <p>กรุณาลองใหม่อีกครั้ง หรือติดต่อผู้ดูแลระบบหากปัญหานี้ยังคงเกิดขึ้น</p>
    <a href="{{ route('login') }}">กลับไปยังหน้าล็อกอิน</a>
</body>

</html>