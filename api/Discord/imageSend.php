<?php
if (!isset($_GET['TokenBot']) || !isset($_GET['ChannelId']) || !isset($_GET['ImageUrl'])) {
    echo json_encode(['error' => 'Faltan parámetros TokenBot, ChannelId o ImageUrl.']);
    exit;
}

$tokenBot = $_GET['TokenBot'];
$channelId = $_GET['ChannelId'];
$imageUrl = $_GET['ImageUrl'];

$apiUrl = "https://discord.com/api/v10/channels/$channelId/messages";

$imageFile = file_get_contents($imageUrl);
if ($imageFile === false) {
    echo json_encode(['error' => 'No se pudo descargar la imagen desde la URL proporcionada.']);
    exit;
}

$tempFilePath = tempnam(sys_get_temp_dir(), 'image') . '.jpg';
file_put_contents($tempFilePath, $imageFile);

$boundary = uniqid();
$delimiter = '-------------' . $boundary;

$postFields = "--$delimiter\r\n"
            . "Content-Disposition: form-data; name=\"file\"; filename=\"AkioFiles.jpg\"\r\n"
            . "Content-Type: image/jpeg\r\n\r\n"
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
    echo json_encode(['error' => 'Error al enviar la imagen: ' . curl_error($ch)]);
    exit;
}

curl_close($ch);
unlink($tempFilePath);

$responseData = json_decode($response, true);
if (isset($responseData['attachments'][0]['url'])) {
    echo json_encode(['url' => $responseData['attachments'][0]['url']]);
} else {
    echo json_encode(['error' => 'Imagen enviada, pero no se pudo obtener la URL.']);
}
?>
