<?php

$apiUrl = 'https://api.kastg.xyz/api/ai/chatgptV4';

if (isset($_GET['name']) && isset($_GET['context']) && isset($_GET['prompt']) && isset($_GET['user'])) {
    $name = $_GET['name'];
    $context = $_GET['context'];
    $prompt = $_GET['prompt'];
    $user = $_GET['user'];

    // Construir la URL con los parámetros de consulta
    $queryParams = http_build_query([
        'prompt' => "Mi nombre de usuario es $user, actua y responde conforme a este contexto, Tu nombre es $name, $context. ahora responde está solicitud: $prompt"
    ]);

    // Construir la URL completa
    $fullUrl = "$apiUrl?$queryParams";

    // Realizar la solicitud GET
    $result = file_get_contents($fullUrl);

    if ($result === FALSE) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al realizar la solicitud a la API de Kastg']);
    } else {
        $response = json_decode($result, true);

        if ($response['status'] === "true") {
            $assistantMessage = $response['result'][0]['response'];
            header('Content-Type: application/json');
            echo json_encode(['response' => $assistantMessage]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error en la respuesta de la API de Kastg']);
        }
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan parámetros GET: name, context, prompt, user']);
}

?>
