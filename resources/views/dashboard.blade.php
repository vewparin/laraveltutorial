<!DOCTYPE html>
<html>

<head>
    <title>Analysis Dashboard</title>
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

        .chart-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        #polarityChart {
            max-width: 500px;
            max-height: 500px;
        }

        .filter-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .polarity-info {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
        }

        .polarity-info div {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container mt-2">
        <h1 class="mb-4">Analysis Dashboard</h1>

        @if (!empty($results))
        <div class="chart-container">
            <canvas id="polarityChart"></canvas>
        </div>

        <div class="polarity-info">
            <div>
                <h4>Positive</h4>
                <p id="positiveCount"></p>
                <p id="positivePercent"></p>
            </div>
            <div>
                <h4>Neutral</h4>
                <p id="neutralCount"></p>
                <p id="neutralPercent"></p>
            </div>
            <div>
                <h4>Negative</h4>
                <p id="negativeCount"></p>
                <p id="negativePercent"></p>
            </div>
        </div>

        <div class="filter-buttons">
            <button class="btn btn-success" onclick="filterComments('positive')">Positive Comments</button>
            <button class="btn btn-warning" onclick="filterComments('neutral')">Neutral Comments</button>
            <button class="btn btn-danger" onclick="filterComments('negative')">Negative Comments</button>
            <button class="btn btn-secondary" onclick="filterComments('all')">All Comments</button>
        </div>

        <div class="table-wrapper">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Comment</th>
                        <th>Score</th>
                        <th>Polarity</th>
                    </tr>
                </thead>
                <tbody id="commentsTable">
                    @foreach($results as $result)
                    <tr data-polarity="{{ htmlspecialchars($result->polarity) }}">
                        <td>{{ htmlspecialchars($result->comment) }}</td>
                        <td>{{ htmlspecialchars($result->score) }}</td>
                        <td>{{ htmlspecialchars($result->polarity) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p>No analysis results to display.</p>
        @endif

        <a href="{{ route('csv.upload.form') }}" class="btn btn-primary mt-3">Back to Upload</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const results = @json($results);
            const polarityCounts = {
                positive: 0,
                neutral: 0,
                negative: 0
            };

            results.forEach(result => {
                polarityCounts[result.polarity]++;
            });

            const total = results.length;
            const positivePercent = ((polarityCounts.positive / total) * 100).toFixed(2);
            const neutralPercent = ((polarityCounts.neutral / total) * 100).toFixed(2);
            const negativePercent = ((polarityCounts.negative / total) * 100).toFixed(2);

            document.getElementById('positiveCount').textContent = `Count: ${polarityCounts.positive}`;
            document.getElementById('positivePercent').textContent = `Percentage: ${positivePercent}%`;
            document.getElementById('neutralCount').textContent = `Count: ${polarityCounts.neutral}`;
            document.getElementById('neutralPercent').textContent = `Percentage: ${neutralPercent}%`;
            document.getElementById('negativeCount').textContent = `Count: ${polarityCounts.negative}`;
            document.getElementById('negativePercent').textContent = `Percentage: ${negativePercent}%`;

            const ctx = document.getElementById('polarityChart').getContext('2d');
            const polarityChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Positive', 'Neutral', 'Negative'],
                    datasets: [{
                        label: 'Polarity',
                        data: [polarityCounts.positive, polarityCounts.neutral, polarityCounts.negative],
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Polarity Distribution'
                        }
                    }
                }
            });
        });

        function filterComments(polarity) {
            const rows = document.querySelectorAll('#commentsTable tr');
            rows.forEach(row => {
                if (polarity === 'all' || row.getAttribute('data-polarity') === polarity) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>
