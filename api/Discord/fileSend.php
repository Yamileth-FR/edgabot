<?php
if (!isset($_GET['TokenBot']) || !isset($_GET['ChannelId']) || !isset($_GET['FileUrl']) || !isset($_GET['lenguaje'])) {
    echo json_encode(['error' => 'Faltan parámetros TokenBot, ChannelId, FileUrl o lenguaje.']);
    exit;
}

$tokenBot = $_GET['TokenBot'];
$channelId = $_GET['ChannelId'];
$fileUrl = $_GET['FileUrl'];
$lenguaje = $_GET['lenguaje'];

$apiUrl = "https://discord.com/api/v10/channels/$channelId/messages";

$fileContent = file_get_contents($fileUrl);
if ($fileContent === false) {
    echo json_encode(['error' => 'No se pudo descargar el archivo desde la URL proporcionada.']);
    exit;
}

// Determinar la extensión del archivo y el tipo de contenido
$extension = pathinfo($fileUrl, PATHINFO_EXTENSION);
$mimeType = '';

switch ($lenguaje) {
    case 'txt':
        $mimeType = 'text/plain';
        break;
    case 'html':
        $mimeType = 'text/html';
        break;
    case 'js':
        $mimeType = 'application/javascript';
        break;
    case 'py':
        $mimeType = 'text/x-python';
        break;
    case 'php':
        $mimeType = 'application/x-httpd-php';
        break;
    default:
        echo json_encode(['error' => 'Lenguaje no soportado.']);
        exit;
}

$tempFilePath = tempnam(sys_get_temp_dir(), 'file') . ".$extension";
file_put_contents($tempFilePath, $fileContent);

$boundary = uniqid();
$delimiter = '-------------' . $boundary;

$postFields = "--$delimiter\r\n"
            . "Content-Disposition: form-data; name=\"file\"; filename=\"AkioFile.$extension\"\r\n"
            . "Content-Type: $mimeType\r\n\r\n"
            . file_get_contents($tempFilePath) . "\r\n"
            . "--$delimiter--\r\n";

$headers = [
    "Authorization: Bot $tokenBot",
    "Content-Type: multipart/form-data; boundary=$delimiter",
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => 'Error al enviar el archivo: ' . curl_error($ch)]);
    exit;
}

curl_close($ch);
unlink($tempFilePath);

$responseData = json_decode($response, true);
if (isset($responseData['attachments'][0]['url'])) {
    echo json_encode(['url' => $responseData['attachments'][0]['url']]);
} else {
    echo json_encode(['error' => 'Archivo enviado, pero no se pudo obtener la URL.']);
}
?>
