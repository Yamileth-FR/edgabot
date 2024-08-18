<?php
if (!isset($_GET['TokenBot']) || !isset($_GET['ChannelId']) || !isset($_GET['VideoUrl'])) {
    echo json_encode(['error' => 'Faltan parámetros TokenBot, ChannelId o VideoUrl.']);
    exit;
}

$tokenBot = $_GET['TokenBot'];
$channelId = $_GET['ChannelId'];
$videoUrl = $_GET['VideoUrl'];

$apiUrl = "https://discord.com/api/v10/channels/$channelId/messages";

$videoFile = file_get_contents($videoUrl);
if ($videoFile === false) {
    echo json_encode(['error' => 'No se pudo descargar el video desde la URL proporcionada.']);
    exit;
}

$tempFilePath = tempnam(sys_get_temp_dir(), 'video') . '.mp4';
file_put_contents($tempFilePath, $videoFile);

$boundary = uniqid();
$delimiter = '-------------' . $boundary;

$postFields = "--$delimiter\r\n"
            . "Content-Disposition: form-data; name=\"file\"; filename=\"AkioFiles.mp4\"\r\n"
            . "Content-Type: video/mp4\r\n\r\n"
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
    echo json_encode(['error' => 'Error al enviar el video: ' . curl_error($ch)]);
    exit;
}

curl_close($ch);
unlink($tempFilePath);

$responseData = json_decode($response, true);
if (isset($responseData['attachments'][0]['url'])) {
    echo json_encode(['url' => $responseData['attachments'][0]['url']]);
} else {
    echo json_encode(['error' => 'Video enviado, pero no se pudo obtener la URL.']);
}
?>
