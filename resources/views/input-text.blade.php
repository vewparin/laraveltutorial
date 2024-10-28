<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Text for Sentiment Analysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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

        /* Style for the pie chart container */
        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            max-height: 400px;
        }

        /* Style for polarity info */
        .polarity-info {
            display: flex;
            justify-content: space-around;
            text-align: center;
            margin-top: 20px;
        }

        /* Canvas styling */
        #sentimentPieChart {
            max-width: 600px;
            max-height: 400px;
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
                    <a class="nav-link active" href="{{ route('input.text.form') }}">ประมวลผลการวิเคราะห์ทีละข้อความ</a>
                    <a class="nav-link" href="{{ route('csv.upload.form') }}">อัพโหลดไฟล์ CSV</a>
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

    <div class="container mt-5 border border-1">
        <!-- Success Message -->
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <h1>ป้อนข้อมูลสำหรับการประมวลทีละข้อความ</h1>
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        <!-- Input Form for Text Analysis -->
        <form action="{{ route('process.text') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="text" class="form-label pt-3">ข้อเสนอแนะเกี่ยวกับการเรียนการสอน: </label>
                <input type="text" class="form-control" id="text" name="text" placeholder="Type your text here..." required>
            </div>
            <button type="submit" class="btn btn-primary">ประมวลผล</button>
        </form>

        <!-- Display Results Table -->
        @if ($results->isNotEmpty())
        <div class="mt-4">
            <h3>ผลลัพธ์การวิเคราะห์</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ข้อความ</th>
                        <th>คะแนน</th>
                        <th>ผลลัพธ์</th>
                        <th>เวลาที่ใช้ในการประมวลผล</th>
                        <th>Action</th> <!-- Delete button column -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $index => $result)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $result->comment }}</td>
                        <td>{{ $result->score }}</td>
                        <td>{{ $result->polarity }}</td>
                        <td>{{ number_format($result->processing_time, 4) }} seconds</td>
                        <td>
                            <!-- Delete Form -->
                            <form action="{{ route('delete.result', $result->id) }}" method="POST">
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
        @endif

        <!-- Delete All Button -->
        <form action="{{ route('input.text.delete.all.results') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-danger">Delete All</button>
        </form>

        <!-- Pie Chart Container -->
        <div class="chart-container mt-5">
            <canvas id="sentimentPieChart"></canvas>
        </div>

        <!-- Polarity Info Container -->
        <div id="polarity-info" class="polarity-info mb-5"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route("text.to.dashboard.data") }}')
                .then(response => response.json())
                .then(data => {
                    renderDashboard(data);
                })
                .catch(error => {
                    console.error('Error fetching dashboard data:', error);
                    alert('An error occurred while fetching dashboard data.');
                });
        });

        function renderDashboard(data) {
            const canvas = document.getElementById('sentimentPieChart');
            const ctx = canvas.getContext('2d');

            // Destroy the previous chart instance if it exists
            if (window.myChart) {
                window.myChart.destroy();
            }

            // Create the pie chart
            window.myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Positive', 'Neutral', 'Negative'],
                    datasets: [{
                        data: [data.polarityCounts.positive, data.polarityCounts.neutral, data.polarityCounts.negative],
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545'] // Colors for each sentiment
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                font: {
                                    size: 16
                                },
                                padding: 20
                            }
                        }
                    }
                }
            });

            renderPolarityInfo(data.polarityCounts, data.total);
        }

        function renderPolarityInfo(polarityCounts, total) {
            const infoContainer = document.getElementById('polarity-info');
            infoContainer.innerHTML = `
                <div>
                    <h4>Positive</h4>
                    <p>Count: ${polarityCounts.positive}</p>
                    <p>Percentage: ${(polarityCounts.positive / total * 100).toFixed(2)}%</p>
                </div>
                <div>
                    <h4>Neutral</h4>
                    <p>Count: ${polarityCounts.neutral}</p>
                    <p>Percentage: ${(polarityCounts.neutral / total * 100).toFixed(2)}%</p>
                </div>
                <div>
                    <h4>Negative</h4>
                    <p>Count: ${polarityCounts.negative}</p>
                    <p>Percentage: ${(polarityCounts.negative / total * 100).toFixed(2)}%</p>
                </div>
            `;
        }
    </script>
</body>

</html>