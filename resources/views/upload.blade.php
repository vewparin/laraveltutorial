<!DOCTYPE html>
<html>
<head>
    <title>Upload CSV File</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-wrapper {
            max-height: 400px; /* ความสูงของแถบเลื่อน */
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
        <h1 class="mb-4">Upload CSV File</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('csv.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="csv_file" class="form-label">Choose CSV File</label>
                <input type="file" class="form-control" name="csv_file" id="csv_file" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        <form action="{{ route('csv.delete.all') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-danger">Delete All</button>
        </form>

        <h2 class="mt-5">Uploaded Files</h2>
        <ul class="list-group">
            @foreach($uploads as $upload)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $upload->file_name }}
                    <form action="{{ route('csv.delete', $upload->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </li>
            @endforeach
        </ul>

        <h2 class="mt-5">CSV Data</h2>
        <form action="{{ route('comments.analyze') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success mb-3">Analyze Comments</button>
        </form>
        <div class="table-wrapper">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Comment</th>
                        <th>DATE</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($csvData as $data)
                        <tr>
                            <td>{{ $data->column1 }}</td>
                            <td>{{ $data->column2 }}</td>
                            <td>{{ $data->column3 }}</td>
                            <td>
                                <form action="{{ route('csv.delete', $data->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
