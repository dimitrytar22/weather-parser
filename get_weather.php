<?php
require_once __DIR__ . '/vendor/autoload.php';
$data = json_decode(file_get_contents("php://input"), true);
$city = htmlspecialchars(trim($data['city']));
$date = htmlspecialchars(trim($data['date']));
if (!$city) {
    echo json_encode([
        'status' => false,
        'error' => [
            'code' => 400,
            'message' => 'Bad request'
        ],
    ]);
    return;
}

$parser = new \App\WeatherParser($city, $date);
$response = json_decode($parser->execute());
echo json_encode([
    'status' => (bool)$response,
    'error' => (bool)$response ? null : [
        'code' => 500,
        'message' => "Couldn't get data"
    ],
    'data' => (bool)$response ? $response : null,
]);
