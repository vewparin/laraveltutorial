<!-- resources/views/upload.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Upload CSV File</title>
</head>
<body>
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form action="{{ route('csv.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="csv_file">Choose CSV File</label>
            <input type="file" name="csv_file" id="csv_file" required>
        </div>
        <button type="submit">Upload</button>
    </form>

    <h2>Uploaded Files</h2>
    <ul>
        @foreach($uploads as $upload)
            <li>{{ $upload->file_name }}</li>
        @endforeach
    </ul>

    <h2>CSV Data</h2>
    <table>
        <thead>
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
                <th>Column 3</th>
                <!-- เพิ่มหัวข้อคอลัมน์อื่น ๆ ตามโครงสร้างของตาราง csv_data -->
            </tr>
        </thead>
        <tbody>
            @foreach($csvData as $data)
                <tr>
                    <td>{{ $data->column1 }}</td>
                    <td>{{ $data->column2 }}</td>
                    <td>{{ $data->column3 }}</td>
                    <!-- เพิ่มคอลัมน์อื่น ๆ ตามโครงสร้างของตาราง csv_data -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
