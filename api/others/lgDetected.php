<?php

function detectLanguage($texto) {
    $detect_language_url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=es&dt=t&q=" . urlencode($texto);
    $curl_detect = curl_init();

    curl_setopt($curl_detect, CURLOPT_URL, $detect_language_url);
    curl_setopt($curl_detect, CURLOPT_RETURNTRANSFER, true);

    $response_detect = curl_exec($curl_detect);

    if ($response_detect === false) {
        return ["error" => curl_error($curl_detect)];
    }

    $language_data = json_decode($response_detect, true);
    curl_close($curl_detect);

    return $language_data[2] ?? null;
}

$names = [
    "af" => "Willem", "sq" => "Ilir", "ar" => "Hamdan", "bn" => "Bashkar",
    "bs" => "Goran", "ca" => "Enric", "hr" => "Srecko", "cs" => "Antonin",
    "da" => "Jeppe", "nl" => "Arnaud", "en" => "Christopher", "et" => "Kert",
    "fi" => "Harri", "fr" => "Henri", "de" => "Jonas", "el" => "Nestoras",
    "gu" => "Niranjan", "hi" => "Madhur", "hu" => "Tamas", "is" => "Gudrun",
    "id" => "Ardi", "it" => "Diego", "ja" => "Keita", "jw" => "Dimas",
    "kn" => "Sapna", "km" => "Sreymom", "ko" => "InJoon", "lv" => "Nils",
    "lt" => "Ona", "mk" => "Aleksandar", "ml" => "Midhun", "mr" => "Manohar",
    "ne" => "Sagar", "no" => "Finn", "pl" => "Marek", "pt" => "Antonio",
    "ro" => "Emil", "ru" => "Dmitry", "sr" => "Nicholas", "si" => "Thilini",
    "sk" => "Lukas", "sl" => "Rok", "es" => "Tomas", "su" => "Jajang",
    "sw" => "Rafiki", "sv" => "Mattias", "ta" => "Kumar", "te" => "Mohan",
    "th" => "Niwat", "tr" => "Emel", "uk" => "Ostap", "ur" => "Salman",
    "vi" => "NamMinh", "cy" => "Aled", "zu" => "Thando"
];

if (isset($_GET['input'])) {
    $texto = $_GET['input'];

    $detected_language = detectLanguage($texto);

    if (isset($detected_language['error'])) {
        $respuesta = ["error" => $detected_language['error']];
    } elseif ($detected_language === null) {
        $respuesta = ["error" => "No se pudo detectar el idioma del texto."];
    } else {
        $nombre_asociado = $names[$detected_language] ?? "Nombre no encontrado";
        $respuesta = [
            "idioma_detectado" => $detected_language,
            "nombre_asociado" => $nombre_asociado
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
} else {
    $error = ["error" => "Se requiere un parámetro 'input' en la URL."];
    header('Content-Type: application/json');
    echo json_encode($error);
}
?>
