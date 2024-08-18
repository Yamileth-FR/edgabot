<?php

// Función para obtener la información del servidor Discord
function obtenerInfoServidor($serverID, $botToken) {
    $url = "https://discord.com/api/v9/guilds/{$serverID}";
    $opciones = [
        'http' => [
            'header' => "Authorization: Bot {$botToken}"
        ]
    ];
    $contexto = stream_context_create($opciones);
    $datos = file_get_contents($url, false, $contexto);
    return $datos;
}

// Verificamos si se ha proporcionado un ID de servidor y un token de bot
if (isset($_GET['serverID']) && isset($_GET['botToken'])) {
    $serverID = $_GET['serverID'];
    $botToken = $_GET['botToken'];

    // Obtenemos la información del servidor
    $info_servidor = obtenerInfoServidor($serverID, $botToken);

    // Devolvemos la información del servidor en formato JSON
    echo $info_servidor;
} else {
    // Si no se proporciona un ID de servidor o un token de bot, devolvemos un mensaje de error
    echo json_encode(array("error" => "Debe proporcionar un ID de servidor y un token de bot."));
}

?>
