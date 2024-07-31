<!DOCTYPE html>
<html>

<head>
    <title>Analysis Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-wrapper {
            max-height: 400px;
            /* ความสูงของแถบเลื่อน */
            overflow-y: auto;
            margin-top: 20px;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 2;
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
            color: darkblue;
            border-bottom-color: aqua;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column ">
            <header class="mb-auto">
                <div>
                    <h3 class="float-md-start mb-0">Semantic Analyze</h3>
                    <nav class="nav nav-masthead justify-content-center float-md-end ">
                        <a class="nav-link text-dark " aria-current="page" href="{{ route('welcome') }}">Home</a>
                        <a class="nav-link text-dark" href="{{ route('csv.upload.form') }}">Upload</a>
                        <a class="nav-link active" href="{{ route('comments.analysis.results') }}">Result</a>

                        @guest
                        <a href="{{ route('google-auth') }}" class="btn btn-primary">Login with Google</a>
                        @else
                        <span class="nav-link text-primary bg-white rounded-pill"> your name : {{ Auth::user()->name }}</span>
                        <a class="nav-link bg-danger text-white rounded-pill" href="{{ route('logout') }}">Logout</a>
                        @endguest
                    </nav>
                </div>
            </header>
        </div>
        <h1 class="mb-4">Analysis Results</h1>

        @if (session('results'))
        <div class="alert alert-success">
            Analysis completed successfully!
        </div>
        @endif

        @if (is_array($results) && !empty($results))
        <div class="table-wrapper">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Comment</th>
                        <th>Score</th>
                        <th>Polarity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                    <tr>
                        <td>{{ htmlspecialchars($result['comment']) }}</td>
                        <td>{{ htmlspecialchars($result['score']) }}</td>
                        <td>{{ htmlspecialchars($result['polarity']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p>No analysis results to display.</p>
        @endif

        <a href="{{ route('csv.upload.form') }}" class="btn btn-primary mt-3">Back to Upload</a>
        <div id="save-container" class="mt-3">
            <button id="save-button" class="btn btn-success">Save to Database</button>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">Go to Dashboard</a>

        <form id="delete-all-form" action="{{ url('delete-all-results') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-danger">Delete All Results</button>
        </form>

        <button id="download-csv-button" class="btn btn-info mt-3">Download as CSV</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('save-button').addEventListener('click', function() {
            fetch('{{ route("save.analysis.results") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    results: @json($results)
                })
            }).then(response => response.json()).then(data => {
                if (data.status === 'success') {
                    alert('Results saved successfully!');
                } else {
                    alert('An error occurred while saving the results.');
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the results.');
            });
        });

        document.getElementById('download-csv-button').addEventListener('click', function () {
            var results = @json($results);
            var csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Comment,Score,Polarity\n"; // Add header

            results.forEach(function(rowArray) {
                let row = rowArray.comment + ',' + rowArray.score + ',' + rowArray.polarity;
                csvContent += row + "\n";
            });

            var encodedUri = encodeURI(csvContent);
            var link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "analysis_results.csv");
            document.body.appendChild(link); // Required for FF

            link.click();
            document.body.removeChild(link);
        });
    </script>
</body>

</html>
