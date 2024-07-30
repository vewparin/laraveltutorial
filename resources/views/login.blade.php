<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #343a40;
            color: white;
        }
        .container {
            max-width: 400px;
            margin-top: 100px;
        }
    </style>
</head>

<body>
    <div class="container text-center">
        <h1 class="mb-4">Login</h1>
        <a href="{{ route('google-auth') }}" class="btn btn-danger w-100">Login with Google</a>
    </div>
</body>

</html>
