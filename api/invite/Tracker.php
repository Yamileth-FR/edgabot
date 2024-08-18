<?php
if (!isset($_GET['ServerId']) || !isset($_GET['TokenBot'])) {
    echo json_encode(['error' => 'Faltan parámetros ServerId o TokenBot.']);
    exit;
}

$serverId = $_GET['ServerId'];
$tokenBot = $_GET['TokenBot'];

$apiUrl = "https://discord.com/api/v10/guilds/$serverId/invites";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bot $tokenBot"
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => curl_error($ch)]);
    exit;
}

curl_close($ch);

$invites = json_decode($response, true);

if (isset($invites['code'])) {
    echo json_encode(['error' => $invites['message']]);
    exit;
}

if (empty($invites)) {
    echo json_encode(['error' => 'No hay invitaciones disponibles.']);
    exit;
}

$recentInvite = null;
foreach ($invites as $invite) {
    if ($recentInvite === null || $invite['uses'] > $recentInvite['uses']) {
        $recentInvite = $invite;
    }
}

header('Content-Type: application/json');
echo json_encode($recentInvite);
?>
