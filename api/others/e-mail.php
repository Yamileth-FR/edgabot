<?php
// Obtener la URL completa desde la que se ejecuta el script
$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// Parsear la URL y obtener los parámetros de la consulta
$parts = parse_url($url);
parse_str($parts['query'], $queryParams);

// Verificar si los parámetros necesarios existen
if(isset($queryParams['email']) && isset($queryParams['msg']) && isset($queryParams['asunt'])) {
    // Obtener el destinatario del correo, el mensaje y el asunto
    $to = $queryParams['email'];
    $message = $queryParams['msg'];
    $subject = $queryParams['asunt'];

    // Obtener el nombre si está presente, de lo contrario usar "akiomae.com"
    $name = isset($queryParams['name']) ? $queryParams['name'] : 'akiomae.com';

    // Definir los encabezados del correo
    $headers = 'From: ' . $name . "\r\n" .
               'Reply-To: ' . $name . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    // Enviar el correo
    if(mail($to, $subject, $message, $headers)) {
        $response = array(
            "status" => "success",
            "message" => "El mensaje ha sido enviado"
        );
    } else {
        $response = array(
            "status" => "error",
            "message" => "El mensaje no pudo ser enviado"
        );
    }
} else {
    // Parámetros faltantes
    $response = array(
        "status" => "error",
        "message" => "Faltan parámetros en la URL"
    );
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>