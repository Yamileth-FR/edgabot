<?php
header('Content-Type: application/json');

// Verifica si se ha proporcionado el parámetro 'username' en la URL
if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $url = "https://gdbrowser.com/u/" . urlencode($username);
    $apiUrl = "https://gdbrowser.com/api/profile/" . urlencode($username);

    // Inicializa cURL para hacer la solicitud GET a la página HTML
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Ejecuta la solicitud cURL y obtiene la respuesta
    $htmlData = curl_exec($ch);
    // Cierra cURL
    curl_close($ch);

    // Realiza una solicitud GET a la API de perfil del usuario
    $apiCh = curl_init();
    curl_setopt($apiCh, CURLOPT_URL, $apiUrl);
    curl_setopt($apiCh, CURLOPT_RETURNTRANSFER, true);
    $apiData = curl_exec($apiCh);
    curl_close($apiCh);

    // Verifica si la solicitud a la API fue exitosa
    if ($apiData) {
        // Decodifica la respuesta JSON
        $apiResponse = json_decode($apiData, true);
        
        // Obtiene los datos de la API
        $userName = $apiResponse['username'] ?? '';
        $rank = $apiResponse['rank'] ?? null;
        $playerID = $apiResponse['playerID'] ?? null;
        $accountID = $apiResponse['accountID'] ?? null;
        $youtube = $apiResponse['youtube'] ?? null;
        $twitter = $apiResponse['twitter'] ?? null;
        $twitch = $apiResponse['twitch'] ?? null;

        // Crea un DOMDocument para analizar el HTML
        $dom = new DOMDocument();
        // Supresor de errores para evitar advertencias de carga HTML
        @$dom->loadHTML($htmlData);
        
        // Obtiene la URL de la imagen del usuario
        $metaTags = $dom->getElementsByTagName('meta');
        $iconUrl = '';
        foreach ($metaTags as $meta) {
            if ($meta->getAttribute('name') === 'og:image') {
                $iconUrl = $meta->getAttribute('content');
                break;
            }
        }

        // Obtiene las estadísticas del usuario
        $stats = [];
        $statsElements = $dom->getElementsByTagName('span');
        foreach ($statsElements as $element) {
            $id = $element->getAttribute('id');
            // Verifica que el ID sea uno de los que necesitas
            if (strpos($id, 'stars') === 0 || strpos($id, 'diamonds') === 0 || strpos($id, 'moons') === 0 || strpos($id, 'coins') === 0 || strpos($id, 'demons') === 0 || strpos($id, 'creatorpoints') === 0) {
                $stats[$id] = trim($element->textContent);
            }
        }

        // Prepara los datos de usuario en formato JSON
        $userInfo = [
            'nombreUsuario' => $userName,
            'iconUrl' => $iconUrl,
            'stats' => $stats,
            'rank' => $rank,
            'playerID' => $playerID,
            'accountID' => $accountID,
            'youtube' => $youtube,
            'twitter' => $twitter,
            'twitch' => $twitch
        ];

        // Devuelve la información en formato JSON
        echo json_encode($userInfo);
    } else {
        // Si no se pudo obtener la información del usuario desde la API, devuelve un error
        echo json_encode(['error' => 'No se pudo obtener la información del usuario desde la API.']);
    }
} else {
    // Si no se proporcionó el parámetro 'username', devuelve un error
    echo json_encode(['error' => 'Falta el parámetro "username" en la URL.']);
}
?>
