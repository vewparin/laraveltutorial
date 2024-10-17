<!DOCTYPE html>
<html>

<head>
    <title>Analysis Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .full-height {
            height: 100vh;
        }

        #chart-container {
            width: 100% !important;
            height: 300px !important;
        }

        .polarity-info {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

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
    <div class="container-fluid mt-5 ">
        <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column border border-1">
            <header class="mb-auto">
                <div>
                    <h3 class="float-md-start mb-0">Semantic Analyze</h3>
                    <nav class="nav nav-masthead justify-content-center float-md-end">
                        <a class="nav-link text-dark" aria-current="page" href="{{ route('welcome') }}">Home</a>
                        <a class="nav-link text-dark" href="{{ route('input.text.form') }}" class="btn btn-secondary mt-3">ประมวลผลการวิเคราะห์ทีละข้อความ</a>
                        <a class="nav-link text-dark" href="{{ route('csv.upload.form') }}">อัพโหลดไฟล์ CSV</a>

                        <a class="nav-link active" href="{{ route('comments.analysis.results') }}">ผลลัพธ์การประมวลผล</a>

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

        <div class="row">
            <!-- Left Column: Comments Table -->
            <div class="col-md-6 full-height border-end mt-5">
                <h1 class="mb-4">ผลลัพธ์การประมวลผล</h1>

                @if (session('results'))
                <div class="alert alert-success">
                    Analysis completed successfully!
                </div>
                @endif

                <div class="form-group mb-3">
                    <label for="polarityFilter">Filter Comments by Polarity:</label>
                    <select class="form-select" id="polarityFilter">
                        <option value="all">All</option>
                        <option value="positive">Positive</option>
                        <option value="neutral">Neutral</option>
                        <option value="negative">Negative</option>
                    </select>
                </div>

                @if (is_array($results) && !empty($results))
                <div class="table-wrapper">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Comment</th>
                                <th>Score</th>
                                <th>Polarity</th>
                                <th>Time spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $result)
                            <tr>
                                <td>{{ htmlspecialchars($result['comment']) }}</td>
                                <td>{{ htmlspecialchars($result['score']) }}</td>
                                <td>{{ htmlspecialchars($result['polarity']) }}</td>
                                <td>{{ number_format($result['processing_time'], 4) }} seconds</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p>No analysis results to display.</p>
                @endif

                <!-- Display Total Processing Time -->
                <div class="mt-4 border border-1" style="font-size: large;">
                    <strong>Total Processing Time: </strong> {{ number_format(session('totalProcessingTime'), 4) }} seconds
                </div>
                <!-- Display Average Processing Time per Comment -->
                <div class="mt-1 border border-1" style="font-size: large;">
                    <strong>Average Processing Time per Comment: </strong> {{ number_format(session('averageProcessingTime'), 4) }} seconds
                </div>

                <!-- Actions Dropdown -->
                <div class="dropdown mt-4">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Actions
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a href="{{ route('csv.upload.form') }}" class="dropdown-item">Back to Upload</a></li>
                        <li>
                            <button id="save-button" class="dropdown-item">Save to Database</button>
                        </li>
                        <li>
                            <form id="delete-all-form" action="{{ url('delete-all-results') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item">Delete All Results</button>
                            </form>
                        </li>
                        <li><a href="{{ route('download.excel') }}" class="dropdown-item">Download as Excel</a></li>
                        <li><a href="{{ route('comments.previous.results') }}" class="dropdown-item">Load Previous Results</a></li>
                    </ul>
                </div>
            </div>

            <!-- Right Column: Dashboard -->
            <div class="col-md-6 full-height mt-5">
                <div id="dashboard-container" style="margin-top:10">
                    <h2>Dashboard</h2>
                    <canvas id="chart-container" width="400" height="400"></canvas>
                    <div id="polarity-info"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Event listener for comment filtering
        document.getElementById('polarityFilter').addEventListener('change', function() {
            const selectedPolarity = this.value;
            filterComments(selectedPolarity);
        });

        function filterComments(polarity) {
            const rows = document.querySelectorAll('.table tbody tr');
            rows.forEach(row => {
                const polarityCell = row.querySelectorAll('td')[2].textContent.trim().toLowerCase();

                if (polarity === 'all' || polarityCell === polarity) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Dashboard rendering
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route("dashboard.data") }}')
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
            const canvas = document.getElementById('chart-container');
            const ctx = canvas.getContext('2d');
            if (window.myChart) {
                window.myChart.destroy();
            }
            window.myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Positive', 'Neutral', 'Negative'],
                    datasets: [{
                        data: [data.polarityCounts.positive, data.polarityCounts.neutral, data.polarityCounts.negative],
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545']
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
                                    size: 20
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
            <div class="polarity-info">
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
            </div>
        `;
        }
    </script>
</body>

</html>