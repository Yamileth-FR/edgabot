<?php
// Obtener el valor del parámetro "message"
$message = $_GET['message'];

// Función para detectar los enlaces en el mensaje
function detectarEnlaces($mensaje) {
  $regex = '/https?:\/\/[^\s]+/';
  preg_match_all($regex, $mensaje, $matches);
  return $matches[0];
}

// Detectar los enlaces en el mensaje
$enlaces = detectarEnlaces($message);
$cantidadEnlaces = count($enlaces);

// Crear un array con la información de los enlaces
$linksDetectados = [];
foreach ($enlaces as $enlace) {
  $nombreWeb = parse_url($enlace, PHP_URL_HOST);
  $linksDetectados[] = [
    "nombre web" => $nombreWeb,
    "link" => $enlace
  ];
}

// Verificar si se encontraron enlaces
$encontrado = ($cantidadEnlaces > 0) ? true : false;

// Crear el array de respuesta en formato JSON
$respuesta = [
  "encontrado" => $encontrado,
  "links detectados" => $linksDetectados,
  "mensaje" => $message,
  "cantidad de links" => $cantidadEnlaces
];

// Convertir el array a JSON
$jsonRespuesta = json_encode($respuesta);

// Establecer la cabecera de respuesta como JSON
header('Content-Type: application/json');

// Imprimir la respuesta JSON
echo $jsonRespuesta;
?>