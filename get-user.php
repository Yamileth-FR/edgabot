<?php
header('Content-Type: application/json');

// Cargar el archivo JSON
$json = file_get_contents('AkioUsers/users.json');
$users = json_decode($json, true);

// Obtener la IP del usuario actual
$user_ip = $_SERVER['REMOTE_ADDR']; // Para IPv4
// Para IPv6, puedes usar $_SERVER['HTTP_X_FORWARDED_FOR'] o $_SERVER['HTTP_CLIENT_IP']

// Buscar al usuario correspondiente a la IP
$user_data = null;
foreach ($users as $username => $data) {
    foreach ($data['ips'] as $ip) {
        if ($ip['ip'] === $user_ip) {
            $user_data = [
                'username' => $username,
                'avatar' => $data['avatar']
            ];
            break 2; // Salir del bucle después de encontrar el usuario
        }
    }
}

// Devolver los datos del usuario como JSON
echo json_encode($user_data);
?>
