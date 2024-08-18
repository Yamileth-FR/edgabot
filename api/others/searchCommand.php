<?php
header('Content-Type: application/json');

if (!isset($_GET['input']) || empty($_GET['input']) || !isset($_GET['search']) || empty($_GET['search'])) {
    $error_response = array(
        'error' => 'Uno o más parámetros faltantes o vacíos.',
        'input' => isset($_GET['input']) ? ($_GET['input'] ? $_GET['input'] : 'El parámetro "input" está vacío.') : 'No se proporcionó el parámetro "input".',
        'search' => isset($_GET['search']) ? ($_GET['search'] ? $_GET['search'] : 'El parámetro "search" está vacío.') : 'No se proporcionó el parámetro "search".'
    );
    echo json_encode($error_response);
} else {
    $input = $_GET['input'];
    $search = $_GET['search'];

    // Modificamos la expresión regular para que solo divida las palabras por puntos o comas
    $wordsArray = preg_split("/[\s.,]+/", $input);

    $response = array();
    $response['similar'] = ""; // Inicializamos el campo similar

    // Buscar palabras similares
    foreach ($wordsArray as $word) {
        if (levenshtein($search, $word) <= 1) { // Permitir una distancia de Levenshtein de hasta 1
            $response['similar'] .= ($response['similar'] == "") ? $word : (", " . $word); // Agregar palabras similares
        }
    }

    if (in_array($search, $wordsArray)) {
        $response['message'] = 'Se encontró la palabra: ' . $search;
        $response['found'] = true;
    } else {
        $response['message'] = empty($response['similar']) ? ('No se encontró la palabra: ' . $search) : ('No se encontró la palabra exacta, pero hay palabras similares.');
        $response['found'] = false;
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
?>
