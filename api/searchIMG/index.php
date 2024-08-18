<?php

if (isset($_GET['search'])) {
    $searchQuery = urlencode($_GET['search']);
    $apiKey = '43007499-1d0cdeedb81309d6018e39157';
    $searchUrl = "https://pixabay.com/api/?key=$apiKey&q=$searchQuery&per_page=5";

    $response = file_get_contents($searchUrl);
    $responseData = json_decode($response, true);

    if (isset($responseData['hits']) && count($responseData['hits']) > 0) {
        $images = [];
        foreach ($responseData['hits'] as $key => $hit) {
            $images["image_" . ($key + 1) . "_url"] = $hit['webformatURL'];
        }
        echo json_encode($images);
    } else {
        echo json_encode(['error' => 'No se encontraron imágenes para esta búsqueda']);
    }
} else {
    echo json_encode(['error' => 'Parámetro de búsqueda no proporcionado']);
}

?>