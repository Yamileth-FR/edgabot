<?php
header('Content-Type: application/json');

if(isset($_GET['text']) && isset($_GET['carac']) && isset($_GET['number'])) {
    $textoGrande = $_GET['text'];
    $textoCorto = $_GET['carac'];
    $numero = $_GET['number'];

    if (!empty($textoGrande) && !empty($textoCorto) && !empty($numero)) {
        $secciones = explode($textoCorto, $textoGrande);
        $total_secciones = count($secciones);

        if ($numero >= 1 && $numero <= $total_secciones) {
            unset($secciones[$numero - 1]);
            $respuesta = implode($textoCorto, $secciones);
            $cantidad_partes = count($secciones);
            echo json_encode(["response" => $respuesta, "parts_remaining" => $cantidad_partes]);
        } else {
            echo json_encode(["response" => "El número de sección proporcionado es inválido."]);
        }
    } else {
        echo json_encode(["response" => "Por favor completa todos los campos."]);
    }
} else {
    echo json_encode(["response" => "Por favor proporciona los parámetros 'text', 'carac' y 'number' en la URL."]);
}
?>