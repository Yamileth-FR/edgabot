<?php
$imageUrl = isset($_GET['url']) ? $_GET['url'] : '';
$key = isset($_GET['key']) ? $_GET['key'] : '';

if (!$key) {
    echo json_encode(['error' => 'falta el parametro "key", para conseguir tu clave key unete al servidor de soporte https://discord.gg/9RHbuSVk5C']);
    exit;
}

if ($key !== 'akiomae.comAPI100299190239010') {
    echo json_encode(['error' => 'Clave api invalida, para conseguir tu clave key unete al servidor de soporte https://discord.gg/9RHbuSVk5C']);
    exit;
}

if (!$imageUrl) {
    echo json_encode(['error' => 'No image URL provided']);
    exit;
}

function makeCurlRequest($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return ['error' => 'Request Error: ' . curl_error($ch)];
    }

    curl_close($ch);

    return json_decode($response, true);
}

$descriptionUrl = "https://api.kastg.xyz/api/ai/blip?url=" . urlencode($imageUrl);
$data = makeCurlRequest($descriptionUrl);

if (isset($data['error'])) {
    echo json_encode($data);
    exit;
}

if (!isset($data['status']) || $data['status'] !== 'true' || !isset($data['result']) || count($data['result']) == 0) {
    echo json_encode(['error' => 'No description could be generated']);
    exit;
}

$description = $data['result'][0]['response'];

$translateUrl = "https://edgabot.akiomae.com/api/others/traductor.php?input=" . urlencode($description);
$translateResponse = file_get_contents($translateUrl);
$translateData = json_decode($translateResponse, true);

if (isset($translateData['translate'])) {
    echo json_encode(['description' => $translateData['translate']]);
} else {
    echo json_encode(['error' => 'Translation Error']);
}
?>
