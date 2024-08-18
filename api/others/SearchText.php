<?php

if (!isset($_GET['canalID']) || !isset($_GET['texto']) || !isset($_GET['tokenBot'])) {
    http_response_code(400);
    echo json_encode(["error" => "Faltan parámetros necesarios (canalID, texto, tokenBot)."]);
    exit();
}

$canalID = $_GET['canalID'];
$texto = $_GET['texto'];
$tokenBot = $_GET['tokenBot'];
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;

if ($limit > 100) {
    http_response_code(400);
    echo json_encode(["error" => "El parámetro 'limit' no puede ser mayor a 100."]);
    exit();
}

$url = "https://discord.com/api/v10/channels/$canalID/messages?limit=$limit";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bot $tokenBot",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(["error" => "Error al realizar la solicitud a la API de Discord.", "details" => curl_error($ch)]);
    exit();
}
curl_close($ch);

if ($http_code !== 200) {
    http_response_code($http_code);
    echo json_encode(["error" => "La solicitud a la API de Discord falló con el código de estado: $http_code", "response" => $response]);
    exit();
}

$messages = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    echo json_encode(["error" => "Error al decodificar la respuesta de la API de Discord."]);
    exit();
}

$result = array_filter($messages, function ($message) use ($texto) {
    if (strpos($message['content'], $texto) !== false) {
        return true;
    }

    if (isset($message['embeds'])) {
        foreach ($message['embeds'] as $embed) {
            if ((isset($embed['title']) && strpos($embed['title'], $texto) !== false) ||
                (isset($embed['description']) && strpos($embed['description'], $texto) !== false) ||
                (isset($embed['footer']['text']) && strpos($embed['footer']['text'], $texto) !== false)) {
                return true;
            }
        }
    }

    return false;
});

$formatted_result = array_map(function ($message) use ($texto) {
    $match_details = [];
    $embed_matches = [];

    if (strpos($message['content'], $texto) !== false) {
        $match_details[] = "content";
    }

    if (isset($message['embeds'])) {
        foreach ($message['embeds'] as $embed) {
            if (isset($embed['title']) && strpos($embed['title'], $texto) !== false) {
                $match_details[] = "embed_title";
                $embed_matches['embed_title'] = $embed['title'];
            }
            if (isset($embed['description']) && strpos($embed['description'], $texto) !== false) {
                $match_details[] = "embed_description";
                $embed_matches['embed_description'] = $embed['description'];
            }
            if (isset($embed['footer']['text']) && strpos($embed['footer']['text'], $texto) !== false) {
                $match_details[] = "embed_footer";
                $embed_matches['embed_footer'] = $embed['footer']['text'];
            }
        }
    }

    return [
        "author_name" => $message['author']['username'],
        "author_id" => $message['author']['id'],
        "message_id" => $message['id'],
        "message" => $message['content'],
        "match_details" => $match_details,
        "embed_matches" => $embed_matches
    ];
}, $result);

header('Content-Type: application/json');
echo json_encode(["response" => array_values($formatted_result)]);
?>
