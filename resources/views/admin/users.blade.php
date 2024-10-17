<!DOCTYPE html>
<html>

<head>
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        table th,
        table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f8f8f8;
            color: #555;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        input[type="text"] {
            padding: 8px;
            width: 70%;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 8px 12px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .success-message {
            text-align: center;
            color: green;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h1>จัดการผู้ใช้ที่เข้าสู่ระบบด้วย Google</h1>

    @if (session('success'))
    <p class="success-message">{{ session('success') }}</p>
    @endif

    <!-- ฟอร์มการค้นหา -->
    <form action="{{ route('admin.users') }}" method="GET" style="text-align: center;">
        <input type="text" name="search" placeholder="ค้นหาชื่อหรืออีเมล" value="{{ request()->input('search') }}">
        <button type="submit">ค้นหา</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="text" name="role" value="{{ $user->role }}">
                </td>
                <td>
                    <select name="status">
                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </td>
                <td>
                    <button type="submit">Update</button>
                    </form>
                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>