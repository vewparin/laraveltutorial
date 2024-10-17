<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Semantic Analyze</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .cover-container {
            max-width: 100em;
        }

        .nav-masthead .nav-link {
            font-weight: 700;
            color: rgba(255, 255, 255, .5);
            background-color: transparent;
            border-bottom: .25rem solid transparent;
        }

        .nav-masthead .nav-link:hover,
        .nav-masthead .nav-link:focus {
            border-bottom-color: rgba(255, 255, 255, .25);
        }

        .nav-masthead .nav-link+.nav-link {
            margin-left: 1rem;
        }

        .nav-masthead .active {
            color: black;
            border-bottom-color: darkblue;
        }

        main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-height: 100vh;
        }

        /* Inline hover effect for the specific links */
        .nav-link.text-dark:hover {
            color: black !important;
            background-color: antiquewhite !important;
            /* Change background color on hover */
            border-radius: 5px;
            /* Add border-radius if needed */
            padding: 5px;
            /* Add some padding for better visual effect */
        }
    </style>
</head>

<body class="d-flex h-100 text-center text-dark bg-white">
    <div class="cover-container w-100 p-3 mx-auto flex-column border border-2">
        <header class="mb-auto">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Align to the left -->
                <h3 class="mb-0">Semantic Analyze</h3>
                <!-- Navigation links -->
                <nav class="nav nav-masthead">
                    <a class="nav-link active" aria-current="page" href="{{ route('welcome') }}">Home</a>
                    <a class="nav-link text-dark" href="{{ route('input.text.form') }}">ประมวลผลการวิเคราะห์ทีละข้อความ</a>
                    <a class="nav-link text-dark" href="{{ route('csv.upload.form') }}">อัพโหลดไฟล์ CSV</a>

                    @guest
                    <a href="{{ route('google-auth') }}" class="btn btn-primary ms-3">Login with Google</a>
                    @else
                    <span class="nav-link text-primary bg-white rounded-pill"> your name : {{ Auth::user()->name }}</span>
                    <a class="nav-link bg-danger text-white rounded-pill ms-3" href="{{ route('logout') }}">Logout</a>
                    @endguest
                </nav>
            </div>
        </header>

        <main class="px-3">
            <h1>Welcome to Semantic Analyze</h1>
            <p class="lead">
                <a href="{{ route('csv.upload.form') }}" class="btn btn-lg btn-secondary fw-bold border-white bg-black">Get Started</a>
            </p>
        </main>

        <footer class="mt-auto text-white-50">
            <p>Developed with <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a>, by <a href="https://twitter.com/mdo" class="text-white">@mdo</a>.</p>
        </footer>
    </div>

</body>

</html>