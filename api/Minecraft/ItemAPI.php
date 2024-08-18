<?php
if (isset($_GET['nombreItem'])) {
  $nombreItem = $_GET['nombreItem'];

  $urlItems = 'https://minecraft-api.vercel.app/api/items';
  $responseItems = file_get_contents($urlItems);
  $items = json_decode($responseItems, true);

  // Busca el item por nombre
  $itemEncontrado = null;
  foreach ($items as $item) {
    if ($item['name'] === $nombreItem) {
      $itemEncontrado = $item;
      break;
    }
  }

  // Si no se encontró el item, devuelve una respuesta de error en formato JSON
  if (!$itemEncontrado) {
    $error = array(
      'error' => 'No se encontró el item'
    );
    header('Content-Type: application/json');
    echo json_encode($error);
    exit;
  }

  // Si se encontró el item, realiza la solicitud GET a la API de traducción
  $descripcion = $itemEncontrado['description'];
  $urlTraduccion = 'https://api.popcat.xyz/translate?to=es&text=' . urlencode($descripcion);
  $responseTraduccion = file_get_contents($urlTraduccion);
  $traduccion = json_decode($responseTraduccion, true)['translated'];

  $traduccionDecodificada = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
  }, $traduccion);

  $itemEncontrado['spanish'] = $traduccionDecodificada;

  header('Content-Type: application/json');
  echo json_encode($itemEncontrado);
} else {

  $error = array(
    'error' => 'No se ingresó un nombre o falta el parametro, usa ?nombreItem=NOMBRE DE ITEM'
  );
  header('Content-Type: application/json');
  echo json_encode($error);
}
?>