<?php

// Obtén los parámetros de la solicitud GET
$userID = isset($_GET['userID']) ? $_GET['userID'] : null;
$botToken = isset($_GET['botToken']) ? $_GET['botToken'] : null;

if ($userID && $botToken) {
    // URL de la API de Discord para obtener información de usuario
    $url = "https://discord.com/api/v10/users/$userID";

    // Inicia la sesión cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Bot $botToken"
    ));

    // Ejecuta la solicitud cURL
    $response = curl_exec($ch);

    // Verifica si hubo un error
    if ($response === false) {
        echo json_encode(array('error' => 'Error al obtener la información del usuario de Discord.'));
    } else {
        // Decodifica la respuesta JSON
        $userData = json_decode($response, true);

        // Verifica si la respuesta contiene datos de usuario
        if (isset($userData['id'])) {
            // Organiza la información
            $formattedData = array(
                'id' => $userData['id'],
                'username' => $userData['username'],
                'discriminator' => $userData['discriminator'],
                'avatar' => isset($userData['avatar']) ? "https://cdn.discordapp.com/avatars/{$userData['id']}/{$userData['avatar']}.png" : null,
                'banner' => isset($userData['banner']) ? "https://cdn.discordapp.com/banners/{$userData['id']}/{$userData['banner']}.png" : null,
                'global_name' => $userData['global_name'],
                'public_flags' => $userData['public_flags'],
                'flags' => $userData['flags'],
                'accent_color' => $userData['accent_color'],
                'banner_color' => $userData['banner_color'],
                'bio' => isset($userData['bio']) ? $userData['bio'] : null,
            );

            // Devuelve los datos del usuario en formato JSON
            echo json_encode($formattedData);
        } else {
            echo json_encode(array('error' => 'No se encontró ningún usuario con el ID proporcionado.'));
        }
    }

    // Cierra la sesión cURL
    curl_close($ch);
} else {
    // Si no se proporcionó userID o botToken, devuelve un error
    echo json_encode(array('error' => 'No se proporcionó userID o botToken.'));
}

?>
