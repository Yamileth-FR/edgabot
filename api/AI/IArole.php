<?php
// Obtiene los parámetros 'prompt' y 'name'
$prompt = $_GET['prompt'] ?? null;
$name = $_GET['name'] ?? 'EdgaBot';
$owner = $_GET['owner'] ?? 'Edgajuman';

// Verifica si se proporcionó el parámetro 'prompt'; si no, muestra un mensaje de error
if ($prompt === null) {
    $error_message = "Hace falta el parámetro 'prompt'.";
    echo json_encode(['error' => $error_message]);
    exit;
}

// Traduce el texto ingresado al inglés
$translated_prompt = translateToEnglish($prompt);

// Construye la URL de la API externa (con el texto traducido)
$api_url = "https://api.popcat.xyz/chatbot?msg=$translated_prompt&botname=$name&owner=$owner";

// Realiza la solicitud GET a la API de Popcat
$response = file_get_contents($api_url);

// Decodifica la respuesta JSON de Popcat
$data = json_decode($response, true);

// Traduce la respuesta de Popcat al español
$translated_response = translateToSpanish($data['response']);

// Devuelve la respuesta en formato JSON con caracteres correctamente codificados
header("Content-Type: application/json; charset=utf-8");
echo json_encode(['translated_response' => $translated_response], JSON_UNESCAPED_UNICODE);
exit;

// Función para traducir texto al inglés
function translateToEnglish($text) {
    // Realiza la solicitud a la API de traducción
    $translation_url = "https://edgabot.akiomae.com/api/others/traductor.php?input=" . urlencode($text) . "&lenguaje=en";
    $translation_response = file_get_contents($translation_url);
    $translation_data = json_decode($translation_response, true);
    return $translation_data['translate'];
}

// Función para traducir texto al español
function translateToSpanish($text) {
    // Realiza la solicitud a la misma API de traducción
    $translation_url = "https://edgabot.akiomae.com/api/others/traductor.php?input=" . urlencode($text) . "&lenguaje=es";
    $translation_response = file_get_contents($translation_url);
    $translation_data = json_decode($translation_response, true);
    return $translation_data['translate'];
}
?>