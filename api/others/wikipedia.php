<?php

header('Content-Type: application/json');

if(isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $searchTermEncoded = rawurlencode($searchTerm);
    
    $url = "https://es.wikipedia.org/w/api.php";
    $url .= "?action=query";
    $url .= "&format=json";
    $url .= "&prop=extracts|pageimages";
    $url .= "&exintro"; 
    $url .= "&explaintext";
    $url .= "&generator=search";
    $url .= "&gsrsearch=" . $searchTermEncoded;
    $url .= "&gsrlimit=5";

    $data = file_get_contents($url);
    $results = json_decode($data, true);

    $pages = $results['query']['pages'];

    $titlesArray = [];
    $summaries = [];
    $imagesArray = [];

    foreach ($pages as $page) {
        $title = $page['title'];
        $titlesArray[] = $title;
        $summary = $page['extract'];
        $summary = html_entity_decode($summary); 
        $summary = strip_tags($summary); 
        $summaries[] = $summary;
        if (isset($page['thumbnail']['source'])) {
            $imageSource = $page['thumbnail']['source'];
            $imageSource = preg_replace('/\/\d+px-/', '/500px-', $imageSource); 
            $imagesArray[] = $imageSource;
        } else {
            $imagesArray[] = "";
        }
    }

    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : null;
    if ($limit !== null && $limit > 0) {
        $response = [
            "title" => $titlesArray[0],
            "response" => mb_strimwidth(implode("\n\n", $summaries), 0, $limit, '...'),
            "image" => $imagesArray[0] 
        ];
    } else {
        $response = [
            "title" => $titlesArray[0],
            "response" => implode("\n\n", $summaries),
            "image" => $imagesArray[0] 
        ];
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "No se proporcionó un término de búsqueda"]);
}

?>
