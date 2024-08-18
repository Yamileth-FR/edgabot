<?php
// Obtén el valor del parámetro "url" desde la URL
$video_url = $_GET['url'];
if (empty($video_url)) {
    // Si no se proporciona una URL válida, muestra un mensaje
    echo json_encode(array('error' => 'Por favor, ingresa una URL válida de un video de YouTube.'), JSON_UNESCAPED_UNICODE);
} else {
    // Realiza una solicitud HTTP para obtener información del video
    $api_url = "https://www.googleapis.com/youtube/v3/search";
    $api_params = array(
        'key' => 'AIzaSyDhrvd3YifzhdGGr3QOz3_0W2eP8HRT2SQ', 
        'q' => $video_url,
        'part' => 'snippet',
        'maxResults' => 1,
        'type' => 'video',
        'videodable' => true,
    );

    // Construye la URL completa con los parámetros
    $full_api_url = $api_url . '?' . http_build_query($api_params);

    // Realiza la solicitud GET
    $response = file_get_contents($full_api_url);

    // Decodifica la respuesta JSON
    $video_info = json_decode($response, true);

    // Extrae el ID del video
    $video_id = $video_info['items'][0]['id']['videoId'];

    // Realiza otra solicitud HTTP para obtener información detallada del video
    $video_details_url = "https://www.googleapis.com/youtube/v3/videos";
    $video_details_params = array(
        'key' => 'AIzaSyDhrvd3YifzhdGGr3QOz3_0W2eP8HRT2SQ', 
        'id' => $video_id,
        'part' => 'snippet,statistics',
    );

    // Construye la URL completa para los detalles del video
    $full_details_url = $video_details_url . '?' . http_build_query($video_details_params);

    // Realiza la segunda solicitud GET
    $details_response = file_get_contents($full_details_url);
    $video_details = json_decode($details_response, true);

    // Extrae los datos adicionales del video
    $video_title = html_entity_decode($video_details['items'][0]['snippet']['title']);
    $video_description = html_entity_decode($video_details['items'][0]['snippet']['description']);
    $video_channel = $video_details['items'][0]['snippet']['channelTitle'];
    $video_thumbnail = $video_details['items'][0]['snippet']['thumbnails']['default']['url'];
    $video_likes = $video_details['items'][0]['statistics']['likeCount'];
    $video_views = $video_details['items'][0]['statistics']['viewCount'];

    // Crea un arreglo asociativo con los datos del video
    $video_data = array(
        'title' => $video_title,
        'description' => $video_description,
        'channel' => $video_channel,
        'thumbnail' => $video_thumbnail,
        'likes' => $video_likes,
        'views' => $video_views,
        'url' => "https://www.youtube.com/watch?v=" . $video_id, // URL del video
    );

    // Convierte el arreglo a formato JSON sin escapar caracteres especiales
    $video_json = json_encode($video_data, JSON_UNESCAPED_UNICODE);

    // Imprime la respuesta en formato JSON
    header('Content-Type: application/json');
    echo $video_json;
}
?>
