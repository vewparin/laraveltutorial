<!DOCTYPE html>
<html>

<head>
    <title>Upload CSV File</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-wrapper {
            max-height: 400px;
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
                    <a class="nav-link active" href="{{ route('csv.upload.form') }}">อัพโหลดไฟล์ CSV</a>
                    <!-- ลิงก์ไปยังการประมวลผลทีละข้อความ -->
                    <a class="nav-link" href="{{ route('input.text.form') }}" class="btn btn-secondary mt-3">ประมวลผลการวิเคราะห์ทีละข้อความ</a>
                    <a class="nav-link" href="{{ route('comments.analysis.results') }}">ผลลัพธ์การประมวลผล</a>

                    @guest
                    <a href="{{ route('google-auth') }}" class="btn btn-primary ms-3">Login with Google</a>
                    @else
                    <span class="nav-link text-primary bg-white rounded-pill">Your name: {{ Auth::user()->name }}</span>
                    <a class="nav-link bg-danger text-white rounded-pill ms-3" href="{{ route('logout') }}">Logout</a>
                    @endguest
                </nav>
            </div>
        </header>
    </div>

    <div class="container mt-5">
        <!-- Upload CSV Card -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h4 class="card-title">อัพโหลดไฟล์ CSV</h4>
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                <form action="{{ route('csv.upload') }}" method="POST" enctype="multipart/form-data" class="mt-3">
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
            </div>
        </div>

        <!-- Uploaded Files Card -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h4 class="card-title">รายชื่อไฟล์ที่อัพโหลด</h4>
                <ul class="list-group mt-3">
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
            </div>
        </div>

        <!-- CSV Data Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">ข้อมูลในไฟล์</h4>
                <form action="{{ route('comments.analyze') }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-success mb-3">เริ่มการประมวลผล</button>
                </form>
                <div class="table-wrapper">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Comment</th>
                                <th>Date</th>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>