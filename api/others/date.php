<?php
// Verificar si se ha proporcionado el parámetro 'opciones'
if (isset($_GET['opciones'])) {
    // Mostrar la lista de ciudades disponibles
    $ciudades = [
        "America/Argentina/Buenos_Aires",
        "America/Bogota",
        "America/Caracas",
        "America/Chicago",
        "America/Mexico_City",
        "America/Lima",
        "America/Los_Angeles",
        "America/Montevideo",
        "America/New_York",
        "America/Panama",
        "America/Santiago",
        "Asia/Bangkok",
        "Asia/Dubai",
        "Asia/Hong_Kong",
        "Asia/Jakarta",
        "Asia/Kolkata",
        "Asia/Riyadh",
        "Asia/Seoul",
        "Asia/Shanghai",
        "Asia/Singapore",
        "Asia/Tokyo",
        "Europe/Amsterdam",
        "Europe/Athens",
        "Europe/Berlin",
        "Europe/Istanbul",
        "Europe/London",
        "Europe/Madrid",
        "Europe/Moscow",
        "Europe/Paris",
        "Europe/Rome",
        "Australia/Sydney"
    ];

    $response = [
        "ciudades" => $ciudades
    ];

    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
}

// Verificar si se ha proporcionado el parámetro 'ciudad'
if (!isset($_GET['ciudad'])) {
    $response = [
        "error" => "Por favor, proporciona el parámetro 'ciudad' en la URL."
    ];

    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
}

// Obtener el nombre de la ciudad o la capital del país desde el parámetro de la URL
$ciudad = $_GET['ciudad'];

// Verificar si la ciudad es válida
$ciudadesValidas = [
    "America/Argentina/Buenos_Aires",
    "America/Bogota",
    "America/Caracas",
    "America/Chicago",
    "America/Mexico_City",
    "America/Lima",
    "America/Los_Angeles",
    "America/Montevideo",
    "America/New_York",
    "America/Panama",
    "America/Santiago",
    "Asia/Bangkok",
    "Asia/Dubai",
    "Asia/Hong_Kong",
    "Asia/Jakarta",
    "Asia/Kolkata",
    "Asia/Riyadh",
    "Asia/Seoul",
    "Asia/Shanghai",
    "Asia/Singapore",
    "Asia/Tokyo",
    "Europe/Amsterdam",
    "Europe/Athens",
    "Europe/Berlin",
    "Europe/Istanbul",
    "Europe/London",
    "Europe/Madrid",
    "Europe/Moscow",
    "Europe/Paris",
    "Europe/Rome",
    "Australia/Sydney"
];

if (!in_array($ciudad, $ciudadesValidas)) {
    $response = [
        "error" => "La ciudad proporcionada no es válida."
    ];

    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
}

// Obtener la fecha y hora actual en la ciudad especificada
$datetime = new DateTime(null, new DateTimeZone($ciudad));
$fecha_actual = $datetime->format('Y-m-d');
$hora_actual_24hrs = $datetime->format('H:i:s');
$hora_actual_12hrs = $datetime->format('h:i:s A');
$hora_actual_unix = $datetime->getTimestamp();

// Construir la respuesta en formato JSON
$response = [
    "ciudad" => $ciudad,
    "fecha_actual" => $fecha_actual,
    "hora_actual_24hrs" => $hora_actual_24hrs,
    "hora_actual_12hrs" => $hora_actual_12hrs,
    "hora_actual_unix" => $hora_actual_unix
];

header("Content-Type: application/json");
echo json_encode($response);
?>