<?php
header('Content-Type: application/json');

$texto = isset($_GET['text']) ? $_GET['text'] : '';
$accion = isset($_GET['action']) ? $_GET['action'] : '';

switch ($accion) {
    case 'mayus':
        $resultado = strtoupper($texto);
        break;
    case 'minus':
        $resultado = strtolower($texto);
        break;
    case 'reverse':
        $resultado = strrev($texto);
        break;
    case 'mejor':
        $resultado = mejorarTexto($texto);
        break;
    case 'correccion':
        $resultado = corregirTextoAvanzado($texto);
        break;
    case 'fuente_alt':
        $resultado = convertirAFuenteAlternativa($texto);
        break;
    default:
        $resultado = "Acción no válida, usa ?action=ACCIÓN&text=TEXTO.
        Acciones disponibles:
        mayus(Convierte letras a todas mayusculas)
        minus(Convierte letras a todas minusculas)
        reverse(Voltea a todo el texto)
        mejor(Mejora los espacios y mayusculas despues de cada punto)
        correccion(Corrige el texto mediante IA)
        fuente_alt(Convierte el texto a doble lineas, ejemplo b a 𝕓)";
}

echo json_encode(['resultado' => $resultado], JSON_UNESCAPED_UNICODE);

function mejorarTexto($texto) {
    $textoMejorado = preg_replace('/(\.|\?|\!)(\w)/', '$1 $2', $texto);
    $textoMejorado = preg_replace_callback('/(?:^|\.\s+)([a-z])/', function($matches) {
        return strtoupper($matches[0]);
    }, $textoMejorado);
    $textoMejorado = preg_replace('/(\w)([\.!?]+)(\s|$)/', '$1$2 ', $textoMejorado);
    $textoMejorado = preg_replace('/(\w)(,)(\w)/', '$1, $3', $textoMejorado);
    return $textoMejorado;
}

function corregirTextoAvanzado($texto) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://api.languagetool.org/v2/check");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'text=' . urlencode($texto) . '&language=es&enabledOnly=false');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    $textoCorregido = $texto;
    if (isset($data['matches']) && count($data['matches']) > 0) {
        foreach ($data['matches'] as $match) {
            $textoCorregido = substr_replace($textoCorregido, $match['replacements'][0]['value'], $match['offset'], $match['length']);
        }
    }
    return $textoCorregido;
}

function convertirAFuenteAlternativa($texto) {
    $caracteresAlternativos = [
        '1' => '𝟙',
        '2' => '𝟚',
        '3' => '𝟛',
        '4' => '𝟜',
        '5' => '𝟝',
        '6' => '𝟞',
        '7' => '𝟟',
        '8' => '𝟠',
        '9' => '𝟡',
        '0' => '𝟘',
        'a' => '𝕒',
        'b' => '𝕓',
        'c' => '𝕔',
        'd' => '𝕕',
        'e' => '𝕖',
        'f' => '𝕗',
        'g' => '𝕘',
        'h' => '𝕙',
        'i' => '𝕚',
        'j' => '𝕛',
        'k' => '𝕜',
        'l' => '𝕝',
        'm' => '𝕞',
        'n' => '𝕟',
        'o' => '𝕠',
        'p' => '𝕡',
        'q' => '𝕢',
        'r' => '𝕣',
        's' => '𝕤',
        't' => '𝕥',
        'u' => '𝕦',
        'v' => '𝕧',
        'w' => '𝕨',
        'x' => '𝕩',
        'y' => '𝕪',
        'z' => '𝕫'
    ];

    $textoConvertido = '';
    for ($i = 0; $i < strlen($texto); $i++) {
        $caracter = strtolower($texto[$i]);
        $textoConvertido .= isset($caracteresAlternativos[$caracter]) ? $caracteresAlternativos[$caracter] : $texto[$i];
    }

    return $textoConvertido;
}
?>