<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Парсер погоды</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center mb-4">Получить погоду</h2>
        <form id="weatherForm">
            <div class="mb-3">
                <label for="city" class="form-label">Город</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Дата (необязательно)</label>
                <input type="date" class="form-control" id="date" name="date">
            </div>
            <button type="submit" class="btn btn-primary w-100">Получить погоду</button>
        </form>
    </div>
    <div class="mt-4">
        <pre id="result" class="p-3 bg-white border rounded"></pre>
    </div>
</div>

<script>
    document.getElementById('weatherForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const city = document.getElementById('city').value;
        const date = document.getElementById('date').value;

        const response = await fetch('get_weather.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ city, date })
        });

        const data = await response.json();
        console.log(data);
        document.getElementById('result').textContent = JSON.stringify(data, null, 2);
    });
</script>
<div class="container mt-5">
    <h2 class="text-center mb-4">Прогноз погоды</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h4 id="location" class="card-title"></h4>
            <p id="current-temp" class="fs-3 fw-bold"></p>
            <p id="weather-desc" class="fs-5"></p>
            <p id="humidity" class="mb-1"></p>
            <p id="wind"></p>
        </div>
    </div>

    <h4 class="mb-3">Почасовой прогноз</h4>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Время</th>
                <th>Температура (°C)</th>
                <th>Осадки (%)</th>
            </tr>
            </thead>
            <tbody id="hourly-weather"></tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
