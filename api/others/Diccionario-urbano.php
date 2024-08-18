<?php
if(isset($_GET['search'])) {
    $palabra = $_GET['search'];

    // Función para obtener la definición en inglés y su traducción al español
    function obtenerDefiniciones($palabra) {
        // Obtener la definición en inglés desde Urban Dictionary
        $url_ingles = 'https://api.urbandictionary.com/v0/define?term=' . urlencode($palabra);
        $respuesta_ingles = file_get_contents($url_ingles);
        $data_ingles = json_decode($respuesta_ingles, true);

        // Verificar si se encontraron definiciones en inglés
        if (!empty($data_ingles['list'])) {
            $definicion_ingles = $data_ingles['list'][0]['definition'];

            // Traducir la definición al español utilizando Google Translate
            $url_traduccion = 'https://api.mymemory.translated.net/get?q=' . urlencode($definicion_ingles) . '&langpair=en|es';
            $respuesta_traduccion = file_get_contents($url_traduccion);
            $data_traduccion = json_decode($respuesta_traduccion, true);

            // Verificar si se obtuvo una traducción
            if (!empty($data_traduccion['responseData']['translatedText'])) {
                $traduccion = $data_traduccion['responseData']['translatedText'];
            } else {
                $traduccion = "No se pudo obtener la traducción.";
            }

            return array(
                'response' => $definicion_ingles,
                'translated' => $traduccion
            );
        } else {
            return array(
                'response' => "No se encontraron definiciones para la palabra '" . $palabra . "'.",
                'translated' => ""
            );
        }
    }

    // Obtener las definiciones y enviar la respuesta en formato JSON
    $definiciones = obtenerDefiniciones($palabra);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($definiciones, JSON_UNESCAPED_UNICODE);
} else {
    // Si no se proporcionó el parámetro 'search', mostrar un mensaje de error
    $error = array('error' => 'Parámetro "search" no encontrado en la URL.');
    header('Content-Type: application/json');
    echo json_encode($error);
}
?>
