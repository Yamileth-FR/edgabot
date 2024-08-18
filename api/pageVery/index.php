<?php
header('Content-Type: application/json');

$url = isset($_GET['url']) ? $_GET['url'] : '';

if ($url == '') {
    $response = array('error' => 'No se proporcionó ninguna URL. Por favor, agregue el enlace a la página web utilizando el parámetro "?url=". Por ejemplo: ?url=https://www.ejemplo.com/');
    echo json_encode($response);
    exit;
}

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    $response = array('error' => 'La URL proporcionada no es válida.');
    echo json_encode($response);
    exit;
}

$excepciones = ['youtube.com', 'instagram.com', 'twitter.com', 'Akiomae', 'EdgaBot', 'Couldy', 'black', 'tiktok', 'facebook', 'couldy'];

foreach ($excepciones as $excepcion) {
    if (strpos($url, $excepcion) !== false) {
        $response = array('url' => $url, 'es_para_adultos' => false);
        echo json_encode($response);
        exit;
    }
}

$palabrasClave = ["xxx", "porn", "adult", "rule34", "hentai", "erotic", "xnxx", "xvideos", 
"redtube", "youporn", "pornhub", "tube8", "spankbang", "brazzers", "naughtyamerica", 
"empflix", "sex", "nude", "nudity", "explicit", "mature", "nsfw", "bdsm", "fetish", 
"blowjob", "cock", "pussy", "vagina", "penis", "boobs", "tits", "ass", "anal", 
"cumshot", "masturbate", "orgasm", "lesbian", "gay", "trans", "milf", "voyeur", "upskirt", 
"gangbang", "threesome", "hardcore", "softcore", "erotic", "escort", "kinky", "dirty", 
"slut", "whore", "prostitute", "sensual", "sexual", "fuck", "dick", "suck", "handjob", 
"footjob", "rimjob", "swinger", "cuckold", "bbw", "busty", "petite", "teen", "amateur", 
"homemade", "reality", "pov", "solo", "toys", "lingerie", "bondage", "granny", "pregnant", 
"shemale", "ladyboy", "femdom", "mistress", "domina", "dominatrix", "submissive", 
"latex", "leather", "nylon", "pantyhose", "stockings", "heels", "booty", "cheating", 
"wife", "husband", "neighbor", "teacher", "milf", "stepmom", "stepdad", "stepsister", 
"stepbrother", "uncle", "aunt", "niece", "nephew", "boss", "secretary", "maid", 
"nurse", "cop", "prisoner", "jail", "prison", "strip", "striptease", "playboy", 
"penthouse", "playmate"];

$esParaAdultos = false;

// Verificar excepciones antes de intentar acceder al contenido de la URL
foreach ($excepciones as $excepcion) {
    if (strpos($url, $excepcion) !== false) {
        $response = array('url' => $url, 'es_para_adultos' => false);
        echo json_encode($response);
        exit;
    }
}

$content = @file_get_contents($url); // Suprime los errores si no se puede obtener el contenido

if ($content !== false) {
    foreach ($palabrasClave as $palabra) {
        if (strpos($content, $palabra) !== false) {
            $esParaAdultos = true;
            break;
        }
    }
}

$response = array('url' => $url, 'es_para_adultos' => $esParaAdultos);
echo json_encode($response);
?>