<?php

function getYesterdayWeather($lat, $lon) {
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $url = "https://api.open-meteo.com/v1/forecast?latitude=$lat&longitude=$lon&start_date=$yesterday&end_date=$yesterday&hourly=temperature_2m";
    // html_build_query();

    $response = @file_get_contents($url);
    if ($response === false) return false;

    $weather = json_decode($response, true);
    if (!isset($weather['hourly']['temperature_2m'], $weather['hourly']['time'])) return false;

    $temps = $weather['hourly']['temperature_2m'];
    $hours = array_map(function($t) {
        return date('H:i', strtotime($t));
    }, $weather['hourly']['time']);

    if (count($temps) === 0) return false;

    return [
        'avg' => array_sum($temps) / count($temps),
        'temps' => $temps,
        'hours' => $hours
    ];
}

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['lat'], $data['lon'])) {
    echo json_encode(['success' => false, 'error' => 'Coordinate mancanti.']);
    exit;
}

$result = getYesterdayWeather($data['lat'], $data['lon']);

if ($result !== false) {
    echo json_encode([
        'success' => true,
        'avg_temp' => round($result['avg'], 1),
        'temps' => $result['temps'],
        'hours' => $result['hours']
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Impossibile recuperare i dati meteo.']);
}