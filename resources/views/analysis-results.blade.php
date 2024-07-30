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
    </style>
</head>

<body>
    <div class="container mt-5">
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
    </script>
</body>

</html>
