<?php
header('Content-Type: application/json');

$min = isset($_GET['min']) ? intval($_GET['min']) : null;
$max = isset($_GET['max']) ? intval($_GET['max']) : null;

$response = array();

if (!is_numeric($min) || !is_numeric($max)) {
    $response['error'] = "Por favor proporciona valores numéricos para 'min' y 'max'.";
} elseif ($min >= $max) {
    $response['error'] = "El valor mínimo debe ser menor que el valor máximo.";
} else {
    $randomNumber = mt_rand($min, $max);
    $response['random_number'] = $randomNumber;
}

echo json_encode($response);
?>
