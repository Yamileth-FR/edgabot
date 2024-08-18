<?php
if (!isset($_GET['TokenBot']) || !isset($_GET['ChannelId']) || !isset($_GET['AudioUrl'])) {
    echo 'Faltan parámetros TokenBot, ChannelId o AudioUrl.';
    exit;
}

$tokenBot = $_GET['TokenBot'];
$channelId = $_GET['ChannelId'];
$audioUrl = $_GET['AudioUrl'];

$apiUrl = "https://discord.com/api/v10/channels/$channelId/messages";

$audioFile = file_get_contents($audioUrl);
if ($audioFile === false) {
    echo 'No se pudo descargar el archivo de audio desde la URL proporcionada.';
    exit;
}

$tempFilePath = tempnam(sys_get_temp_dir(), 'audio') . '.mp3';
file_put_contents($tempFilePath, $audioFile);

$boundary = uniqid();
$delimiter = '-------------' . $boundary;

$postFields = "--$delimiter\r\n"
            . "Content-Disposition: form-data; name=\"file\"; filename=\"Akiomae.mp3\"\r\n"
            . "Content-Type: audio/mpeg\r\n\r\n"
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
    echo 'Error al enviar el archivo de audio: ' . curl_error($ch);
    exit;
}

curl_close($ch);
unlink($tempFilePath);

echo 'Archivo enviado';
?>
