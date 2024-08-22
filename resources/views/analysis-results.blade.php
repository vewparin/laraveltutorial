<!DOCTYPE html>
<html>

<head>
    <title>Analysis Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        
        #dashboard-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
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

        .filter-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        /*======================================*/
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
        <div class="d-flex p-2 justify-content-between">

            <div>
                <a href="{{ route('csv.upload.form') }}" class="btn btn-primary mt-3">Back to Upload</a>
            </div>
            <!-- แก้ไขปุ่ม Go to Dashboard -->
            <div>
                <button id="show-dashboard-btn" class="btn btn-primary mt-3">Show Dashboard</button>
            </div>
            <div id="save-container" class="mt-3">
                <button id="save-button" class="btn btn-success">Save to Database</button>
            </div>

            <!-- <div>
                <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">Go to Dashboard</a>
            </div> -->

            <form id="delete-all-form" action="{{ url('delete-all-results') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-danger">Delete All Results</button>
            </form>
            <div>
                <button id="download-csv-button" class="btn btn-info mt-3">Download as CSV</button>
            </div>
            <div>
                <a href="{{ route('comments.previous.results') }}" class="btn btn-secondary mt-3">Load Previous Results</a>
            </div>
        </div>
    </div>

    <div id="dashboard-container" style="display: none; margin-top:10">
        <h2>Dashboard</h2>
        <canvas id="chart-container" width="400" height="400"></canvas>
        <div id="polarity-info"></div>
        <div id="filter-buttons"></div>
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

        document.getElementById('download-csv-button').addEventListener('click', function() {
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

        //๋JS FOR DashBoard Webpage link is DashboardController.php and Web.php
        document.getElementById('show-dashboard-btn').addEventListener('click', function() {
            const dashboardContainer = document.getElementById('dashboard-container');
            if (dashboardContainer.style.display === 'none' || dashboardContainer.style.display === '') {
                fetch('{{ route("dashboard.data") }}')
                    .then(response => response.json())
                    .then(data => {
                        dashboardContainer.style.display = 'block';
                        renderDashboard(data);
                    })
                    .catch(error => {
                        console.error('Error fetching dashboard data:', error);
                        alert('An error occurred while fetching dashboard data. Please try again.');
                    });
            } else {
                dashboardContainer.style.display = 'none';
            }
        });

        function renderDashboard(data) {
            // Clear previous content
            const dashboardContainer = document.getElementById('dashboard-container');
            dashboardContainer.innerHTML = `
        <h2>Dashboard</h2>
        <canvas id="chart-container" width="400" height="400"></canvas>
        <div id="polarity-info"></div>
        <div id="filter-buttons"></div>
    `;

            // Render chart
            renderChart(data.polarityCounts);

            // Render polarity info
            renderPolarityInfo(data.polarityCounts, data.total);

            // Render filter buttons
            renderFilterButtons();
        }

        function renderChart(polarityCounts) {
            const canvas = document.getElementById('chart-container');
            if (!canvas) {
                console.error('Canvas element not found');
                return;
            }
            const ctx = canvas.getContext('2d');
            if (window.myChart) {
                window.myChart.destroy();
            }
            window.myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Positive', 'Neutral', 'Negative'],
                    datasets: [{
                        data: [polarityCounts.positive, polarityCounts.neutral, polarityCounts.negative],
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins:{
                        legend:{
                            display:true,
                            labels:{
                                font:{
                                    size: 20
                                },
                                padding: 20
                            }
                        }
                    }
                }
            });
        }

        function renderPolarityInfo(polarityCounts, total) {
            const infoContainer = document.getElementById('polarity-info');
            infoContainer.innerHTML = `
        <div class="polarity-info">
            <div>
                <h4>Positive</h4>
                <p>Count: ${polarityCounts.positive}</p>
                <p>Percentage: ${((polarityCounts.positive / total) * 100).toFixed(2)}%</p>
            </div>
            <div>
                <h4>Neutral</h4>
                <p>Count: ${polarityCounts.neutral}</p>
                <p>Percentage: ${((polarityCounts.neutral / total) * 100).toFixed(2)}%</p>
            </div>
            <div>
                <h4>Negative</h4>
                <p>Count: ${polarityCounts.negative}</p>
                <p>Percentage: ${((polarityCounts.negative / total) * 100).toFixed(2)}%</p>
            </div>
        </div>
    `;
        }

        function renderFilterButtons() {
            const buttonContainer = document.getElementById('filter-buttons');
            buttonContainer.innerHTML = `
        <div class="filter-buttons">
            <button class="btn btn-success" onclick="filterComments('positive')">Positive Comments</button>
            <button class="btn btn-warning" onclick="filterComments('neutral')">Neutral Comments</button>
            <button class="btn btn-danger" onclick="filterComments('negative')">Negative Comments</button>
            <button class="btn btn-secondary" onclick="filterComments('all')">All Comments</button>
        </div>
    `;
        }

        function filterComments(polarity) {
            const rows = document.querySelectorAll('.table tbody tr');
            rows.forEach(row => {
                if (polarity === 'all' || row.querySelector('td:last-child').textContent.trim().toLowerCase() === polarity) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>