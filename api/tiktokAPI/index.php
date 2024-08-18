<?php

require __DIR__ . '/src/Users.php';

$usuario = isset($_GET['user']) ? $_GET['user'] : '';

if (!empty($usuario)) {
    $getTiktokUser = new TikTok\Users();
    
    echo $getTiktokUser->details('@' . $usuario);
} else {
    echo 'Por favor, proporciona un nombre de usuario utilizando "?user=USUARIO" al final de la URL.';
}
