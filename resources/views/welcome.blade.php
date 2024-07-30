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
            color: #fff;
            border-bottom-color: #fff;
        }

        main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-height: 100vh;
        }
    </style>
</head>

<body class="d-flex h-100 text-center text-white bg-dark">

    <div class="cover-container d-flex w-500 h-100 p-3 mx-auto flex-column">
        <header class="mb-auto">
            <div>
                <h3 class="float-md-start mb-0">Semantic Analyze</h3>
                <nav class="nav nav-masthead justify-content-center float-md-end">
                    <a class="nav-link active" aria-current="page" href="{{ route('welcome') }}">Home</a>
                    <a class="nav-link" href="{{ route('csv.upload.form') }}">Upload</a>
                    @guest
                    <a href="{{ route('google-auth') }}" class="btn btn-primary">Login with Google</a>
                    @else
                    <span class="nav-link text-primary bg-white rounded-pill"> your name : {{ Auth::user()->name }}</span>
                    <a class="nav-link bg-danger text-white rounded-pill" href="{{ route('logout') }}">Logout</a>
                    @endguest
                </nav>
            </div>
        </header>

        <main class="px-3">
            <h1>Welcome to Semantic Analyze</h1>
            <p class="lead">Our platform provides comprehensive tools for analyzing the sentiment of text data. Upload your files, and get detailed insights into the emotional tone of the content.</p>
            <p class="lead">
                <a href="{{ route('csv.upload.form') }}" class="btn btn-lg btn-secondary fw-bold border-white bg-black">Get Started</a>
            </p>
            <div class="mt-4">
                <h2>About Our Work</h2>
                <p>We specialize in semantic analysis, focusing on extracting meaningful information from text. Our services include sentiment analysis, entity recognition, and more. Our mission is to help you understand your data better.</p>
            </div>
        </main>

        <footer class="mt-auto text-white-50">
            <p>Developed with <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a>, by <a href="https://twitter.com/mdo" class="text-white">@mdo</a>.</p>
        </footer>
    </div>

</body>

</html>