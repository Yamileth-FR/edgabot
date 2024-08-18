<?php
// Obtener el parámetro "url" del enlace
$inviteLink = $_GET['url'] ?? '';

if (!empty($inviteLink)) {
    // Realizar la solicitud GET a la API de Discord para obtener información sobre el enlace de invitación
    $discordAPIUrl = 'https://discord.com/api/invite/' . urlencode($inviteLink);
    $response = file_get_contents($discordAPIUrl);

    if ($response) {
        $data = json_decode($response, true);

        // Extraer información relevante de la respuesta
        $serverName = $data['guild']['name'] ?? 'No disponible';
        $serverID = $data['guild']['id'] ?? 'No disponible';
        $serverIcon = isset($data['guild']['id']) ? 'https://cdn.discordapp.com/icons/' . $data['guild']['id'] . '/' . $data['guild']['icon'] . '.png' : '';
        $serverDescription = $data['guild']['description'] ?? 'No disponible';
        $expiresIn = isset($data['expires_at']) ? strtotime($data['expires_at']) - time() : 'Infinito';
        $creatorName = $data['inviter']['username'] ?? 'No disponible';
        $channelName = $data['channel']['name'] ?? 'No disponible';
        $channelID = $data['channel']['id'] ?? 'No disponible';
        $maxUses = $data['max_uses'] ?? 'Ilimitado';
        $uses = $data['uses'] ?? 0;

        // Formatear la duración del enlace de invitación si está disponible
        $duration = $expiresIn !== 'Infinito' ? floor($expiresIn / (60 * 60 * 24)) . " días" : $expiresIn;

        // Generar respuesta en formato JSON
        $responseData = [
            'serverName' => $serverName,
            'serverID' => $serverID,
            'serverIcon' => $serverIcon,
            'serverDescription' => $serverDescription,
            'expiresIn' => $expiresIn,
            'creatorName' => $creatorName,
            'channelName' => $channelName,
            'channelID' => $channelID,
            'maxUses' => $maxUses,
            'uses' => $uses,
            'duration' => $duration
        ];

        // Enviar respuesta JSON
        header('Content-Type: application/json');
        echo json_encode($responseData);
        exit;
    } else {
        // Manejar errores al obtener la información del enlace de invitación
        echo "Error al obtener la información del enlace de invitación. Por favor, verifica el enlace e intenta nuevamente.";
    }
} else {
    // Advertir si no se proporcionó el parámetro "url"
    echo "No escribiste el parámetro, prueba a usar '?url={invite_link}' en el link de esta página.";
}
?>