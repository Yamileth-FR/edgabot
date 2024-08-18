<?php
$apiKey = 'acc_4db6e6530f74856';
$apiSecret = '2fadb2f24eda8314591421c2ec8fcb71';

$imageUrl = isset($_GET['url']) ? $_GET['url'] : '';

if (!$imageUrl) {
    echo json_encode(['error' => 'No image URL provided']);
    exit;
}

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.imagga.com/v2/tags?image_url=" . urlencode($imageUrl));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$apiKey:$apiSecret");

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => 'Request Error:' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

$data = json_decode($response, true);

if (isset($data['result']) && isset($data['result']['tags']) && count($data['result']['tags']) > 0) {
    $tags = array_map(function ($tag) {
        return $tag['tag']['en'];
    }, $data['result']['tags']);
    $description = implode(', ', $tags);

    $translateUrl = "https://edgabot.akiomae.com/api/others/traductor.php?input=" . urlencode($description);
    $translateResponse = file_get_contents($translateUrl);
    $translateData = json_decode($translateResponse, true);

    if (isset($translateData['translate'])) {
        echo json_encode(['description' => $translateData['translate']]);
    } else {
        echo json_encode(['error' => 'Translation Error']);
    }
} else {
    echo json_encode(['error' => 'No description could be generated']);
}

?>
