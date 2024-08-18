<?php

function translateToEnglish($text) {
    $translate_url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=es&tl=en&dt=t&q=" . urlencode($text);
    $curl_translate = curl_init();
    curl_setopt($curl_translate, CURLOPT_URL, $translate_url);
    curl_setopt($curl_translate, CURLOPT_RETURNTRANSFER, true);
    $response_translate = curl_exec($curl_translate);
    $translation_data = json_decode($response_translate);
    curl_close($curl_translate);
    return $translation_data[0][0][0];
}

function translateToLanguage($text, $language) {
    $translate_url = "https://api.popcat.xyz/translate?to=" . $language . "&text=" . urlencode($text);
    $curl_translate = curl_init();
    curl_setopt($curl_translate, CURLOPT_URL, $translate_url);
    curl_setopt($curl_translate, CURLOPT_RETURNTRANSFER, true);
    $response_translate = curl_exec($curl_translate);
    $translation_data = json_decode($response_translate, true);
    curl_close($curl_translate);
    if(isset($translation_data['translated'])) {
        return str_replace('\n', "\n", htmlspecialchars_decode($translation_data['translated'], ENT_QUOTES));
    } else {
        return 'No se pudo traducir';
    }
}

if(isset($_GET['book']) && isset($_GET['chapter'])) {
    $book = $_GET['book'];
    $chapter = $_GET['chapter'];
    $verse = isset($_GET['verse']) ? $_GET['verse'] : '';
    $language = isset($_GET['lg']) ? $_GET['lg'] : 'es'; // Idioma predeterminado: español

    $translatedBook = translateToEnglish($book);

    if($translatedBook) {
        $url = 'https://bible-api.com/' . urlencode($translatedBook) . '+' . $chapter . ($verse ? ':' . $verse : '') . '?format=json';
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        if(isset($data['text'])) {
            $englishText = $data['text'];
            $translatedText = ($language != 'en') ? translateToLanguage($englishText, $language) : $englishText;
            $response = [
                'response' => $englishText,
                'translate' => $translatedText
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            echo json_encode(['error' => 'No se encontró ningún versículo para los parámetros proporcionados.']);
        }
    } else {
        echo json_encode(['error' => 'No se pudo traducir el nombre del libro.']);
    }
} else {
    echo json_encode(['error' => 'Debes proporcionar los parámetros "book" y "chapter".']);
}

?>
