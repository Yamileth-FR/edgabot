<?php

if (isset($_GET['input'])) {
    $texto = $_GET['input'];

    if (isset($_GET['lenguaje'])) {
        $idioma_destino = $_GET['lenguaje'];
    } else {
        $idioma_destino = "es";
    }

    $detect_language_url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=es&dt=t&q=" . urlencode($texto);

    $curl_detect = curl_init();

    curl_setopt($curl_detect, CURLOPT_URL, $detect_language_url);
    curl_setopt($curl_detect, CURLOPT_RETURNTRANSFER, true);

    $response_detect = curl_exec($curl_detect);
    $language_data = json_decode($response_detect);

    $detected_language = $language_data[2];

    curl_close($curl_detect);

    $translation_url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=" . $detected_language . "&tl=" . $idioma_destino . "&dt=t&q=" . urlencode($texto);

    $curl_translate = curl_init();

    curl_setopt($curl_translate, CURLOPT_URL, $translation_url);

    curl_setopt($curl_translate, CURLOPT_RETURNTRANSFER, true);

    $response_translate = curl_exec($curl_translate);

    $translation_data = json_decode($response_translate);

    curl_close($curl_translate);

    $respuesta = array(
        "idioma_detectado" => $detected_language,
        "input" => $texto,
        "translate" => $translation_data[0][0][0]
    );

    header('Content-Type: application/json');
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
} else {
    $error = array("error" => "Se requiere un parámetro 'input' en la URL.");
    header('Content-Type: application/json');
    echo json_encode($error);
}
?>
