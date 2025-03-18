document.addEventListener('DOMContentLoaded', function () {
    const weatherForm = document.getElementById('weatherForm');

    weatherForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const city = document.getElementById('city').value;
        const date = document.getElementById('date').value;

        const response = await fetch('get_weather.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({city, date})
        });

        const data = await response.json();
        console.log(data);
        if (response.ok) {
            const resultElement = document.getElementById('result');
            if (!data.status) {
                showError(data, resultElement);
                return;
            }
            showWeather(data, resultElement);
        } else {
            document.getElementById('result').textContent = "Error";
        }
    });

    function showError(data, weatherElement) {
        weatherElement.innerHTML = `<div class="alert alert-danger text-center" role="alert">
            ❌ ${data.error.message}
        </div>`;
    }

    function showWeather(data, weatherElement) {
        weatherElement.innerHTML = "";

        function getWindDirection(degrees) {
            const directions = ["North", "North-Northeast", "Northeast", "East-Northeast", "East", "East-Southeast", "Southeast", "South-Southeast", "South", "South-Southwest", "Southwest", "West-Southwest", "West", "West-Northwest", "Northwest", "North-Northwest"];
            const index = Math.round(degrees / 22.5) % 16;
            return directions[index];
        }

        for (let day of data.data.days) {
            let windDir = getWindDirection(day.winddir);

            let hourlyWeatherBlock = ``;
            for (let hour of day.hours) {
                hourlyWeatherBlock += `<tr>
                    <td><strong>${hour.datetime}</strong></td>
                    <td class="text-primary fw-bold">${hour.feelslike}°C</td>
                    <td class="text-danger fw-bold">${hour.precip}%</td>
                </tr>`;
            }

            let date = new Date(Date.parse(day.datetime));
            let options = {weekday: 'long', day: 'numeric', month: 'long'};
            let formattedDate = date.toLocaleDateString('en-GB', options);
            let uniqueId = `hourly-weather-${day.datetime.replace(/[^a-zA-Z0-9]/g, '')}`;

            let weatherBlock = `<div class="container mt-5">
    <h2 class="text-center mb-4">🌤️ Weather Forecast ${formattedDate}</h2>

    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body text-center">
            <h4 id="location" class="card-title fw-bold">${data.data.resolvedAddress}</h4>
            <p id="current-temp" class="fs-1 fw-bold text-primary">${day.temp}°C</p>
            <p id="weather-desc" class="fs-5 text-muted">${day.description}</p>
            <div class="d-flex justify-content-center gap-4 mt-3">
                <span class="badge bg-info fs-6">💧 ${day.humidity}%</span>
                <span class="badge bg-warning fs-6">🌬️ Wind: ${day.windspeed} m/s, ${windDir}</span>
            </div>
        </div>
    </div>

    <h4 class="mb-3">
        🕒 Hourly Forecast
        <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#${uniqueId}">
            🔼 Toggle Hourly Forecast
        </button>
    </h4>

    <div id="${uniqueId}" class="collapse">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">Time</th>
                        <th class="text-center">🌡️ Temperature (°C)</th>
                        <th class="text-center">☔ Precipitation (%)</th>
                    </tr>
                </thead>
                <tbody id="hourly-weather" class="text-center">
                    ${hourlyWeatherBlock}
                </tbody>
            </table>
        </div>
    </div>
</div>`;

            weatherElement.innerHTML += weatherBlock;
        }
    }
});
