<?php
// Obtén el valor del parámetro "spo" desde la URL
$track_name = $_GET['spo'];
if (empty($track_name)) {
    // Si no se proporciona un nombre de canción válido, muestra un mensaje
    echo json_encode(array('error' => 'Por favor, ingresa un nombre de canción válido.'), JSON_UNESCAPED_UNICODE);
} else {
    // Datos de autenticación de Spotify
    $client_id = '060dff5aedcb4bbd8e3b140405a44f87';
    $client_secret = '9ca719661a43411f9138c2d3ba187fb6';
    // Codifica las credenciales en base64
    $auth_string = base64_encode($client_id . ':' . $client_secret);

    // Solicita el token de acceso a la API de Spotify
    $token_url = 'https://accounts.spotify.com/api/token';
    $token_body = http_build_query(array('grant_type' => 'client_credentials'));
    $token_headers = array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Basic ' . $auth_string
    );

    $token_options = array(
        'http' => array(
            'method' => 'POST',
            'header' => implode("\r\n", $token_headers),
            'content' => $token_body
        )
    );

    // Realiza la solicitud para obtener el token
    $token_response = file_get_contents($token_url, false, stream_context_create($token_options));
    $token_data = json_decode($token_response, true);
    $access_token = $token_data['access_token'];

    // Realiza la búsqueda de la canción en Spotify
    $search_url = 'https://api.spotify.com/v1/search?type=track&q=' . urlencode($track_name);
    $search_headers = array('Authorization: Bearer ' . $access_token);
    $search_options = array(
        'http' => array(
            'method' => 'GET',
            'header' => implode("\r\n", $search_headers)
        )
    );

    // Realiza la solicitud para buscar la canción
    $search_response = file_get_contents($search_url, false, stream_context_create($search_options));
    $search_data = json_decode($search_response, true);

    // Prepara la respuesta
    $results = array();
    if (isset($search_data['tracks']) && count($search_data['tracks']['items']) > 0) {
        $top_song = $search_data['tracks']['items'][0];
        $uri_parts = explode(':', $top_song['uri']);
        $uri = end($uri_parts);
        $uri = str_replace(':', '/', $uri);

        // Obtener información adicional sobre el artista
        $artist_id = $top_song['artists'][0]['id'];
        $artist_url = 'https://api.spotify.com/v1/artists/' . $artist_id;
        $artist_response = file_get_contents($artist_url, false, stream_context_create($search_options));
        $artist_data = json_decode($artist_response, true);

        // URL de la imagen del artista
        $artist_image_url = isset($artist_data['images'][0]['url']) ? $artist_data['images'][0]['url'] : '';

        $results = array(
            'title' => $top_song['name'],
            'artist' => $top_song['artists'][0]['name'],
            'duration' => $top_song['duration_ms'] / 1000, // Duración en segundos
            'spotify_uri' => $uri,
            'thumbnail_url' => $top_song['album']['images'][0]['url'], // URL de la miniatura
            'artist_image_url' => $artist_image_url // URL de la imagen del artista
        );
    } else {
        $results['error'] = 'No se encontraron resultados.';
    }

    // Imprime la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($results, JSON_UNESCAPED_UNICODE);
}
?>