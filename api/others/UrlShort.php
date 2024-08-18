<?php
if(isset($_GET['url'])) {
    $url = $_GET['url'];
    $apikey = "14cf47b7f8971c0833354e1d93a9e2815b856b3f";

    // Crear el cuerpo de la solicitud POST
    $data = array(
        'url' => $url,
        'apikey' => $apikey
    );

    // Convertir los datos a formato JSON
    $payload = json_encode($data);

    // Inicializar cURL
    $ch = curl_init('https://n9.cl/en/api/short');

    // Configurar opciones para la solicitud POST
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
    );

    // Ejecutar la solicitud y obtener la respuesta
    $result = curl_exec($ch);

    // Cerrar la conexión cURL
    curl_close($ch);

    // Devolver la respuesta JSON
    header('Content-Type: application/json');
    echo $result;
} else {
    // Si no se proporciona la URL, devolver un mensaje de error
    echo json_encode(array('error' => 'No se proporcionó la URL.'));
}
?>
