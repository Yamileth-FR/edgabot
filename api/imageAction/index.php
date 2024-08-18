<?php

function applyEffect($imagePath, $action, $value = 0) {
    $imageData = file_get_contents($imagePath);
    $image = imagecreatefromstring($imageData);

    if ($image === false) {
        return false;
    }

    switch ($action) {
        case 'B/N':
            imagefilter($image, IMG_FILTER_GRAYSCALE);
            break;
        case 'Sepia':
            imagefilter($image, IMG_FILTER_GRAYSCALE);
            imagefilter($image, IMG_FILTER_COLORIZE, 112, 66, 20);
            break;
        case 'Negative':
            imagefilter($image, IMG_FILTER_NEGATE);
            break;
        case 'RGB':
            imagefilter($image, IMG_FILTER_COLORIZE, 100, 0, 100);
            break;
        case 'Blur':
            for ($i = 0; $i < $value; $i++) {
                imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
            }
            break;
        case 'Gay':
            $rainbow = imagecreatefrompng('path_to_rainbow.png');
            $rainbowResized = imagescale($rainbow, imagesx($image), imagesy($image));
            imagecopymerge($image, $rainbowResized, 0, 0, 0, 0, imagesx($image), imagesy($image), 30);
            imagedestroy($rainbow);
            imagedestroy($rainbowResized);
            break;
        case 'Glitch':
            // Aplicar un efecto glitch básico
            for ($i = 0; $i < imagesy($image); $i++) {
                if ($i % 2 == 0) {
                    imagecopy($image, $image, rand(-5, 5), $i, 0, $i, imagesx($image), 1);
                }
            }
            break;
            case 'Circle':
                $width = imagesx($image);
                $height = imagesy($image);
                $size = min($width, $height);
            
                // Crear una nueva imagen cuadrada con transparencia
                $newImage = imagecreatetruecolor($size, $size);
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
                imagefill($newImage, 0, 0, $transparent);
            
                // Crear la máscara circular
                $mask = imagecreatetruecolor($size, $size);
                imagealphablending($mask, false);
                imagesavealpha($mask, true);
                $transparentMask = imagecolorallocatealpha($mask, 0, 0, 0, 127);
                imagefill($mask, 0, 0, $transparentMask);
                $white = imagecolorallocate($mask, 255, 255, 255);
                imagefilledellipse($mask, $size / 2, $size / 2, $size, $size, $white);
            
                // Redimensionar la imagen original a las dimensiones de la nueva imagen cuadrada
                imagecopyresampled($newImage, $image, 0, 0, ($width - $size) / 2, ($height - $size) / 2, $size, $size, $size, $size);
            
                // Aplicar la máscara circular
                for ($y = 0; $y < $size; $y++) {
                    for ($x = 0; $x < $size; $x++) {
                        $alpha = (imagecolorat($mask, $x, $y) >> 24) & 0x7F;
                        $color = imagecolorat($newImage, $x, $y);
                        $color = ($color & 0xFFFFFF) | ($alpha << 24);
                        imagesetpixel($newImage, $x, $y, $color);
                    }
                }
            
                imagedestroy($mask);
                imagedestroy($image);  // Liberar la imagen original
            
                $image = $newImage;  // Asignar la nueva imagen circular a la variable $image
                break;            
        case 'Reverse':
            imageflip($image, IMG_FLIP_HORIZONTAL);
            break;
        case 'Up':
            imageflip($image, IMG_FLIP_VERTICAL);
            break;
            case 'Remove':
                $apiKey = 'VbFJF9kKji1UAVc4WbACiQyt';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.remove.bg/v1.0/removebg');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, [
                    'image_url' => $imagePath,
                    'size' => 'auto',
                ]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Api-Key: ' . $apiKey]);
                $response = curl_exec($ch);
                curl_close($ch);
    
                if ($response) {
                    $image = imagecreatefromstring($response);
    
                    // Aquí es donde aseguras que la transparencia se maneja correctamente
                    imagesavealpha($image, true); // Activa el guardado de la transparencia
                    $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
                    imagefill($image, 0, 0, $transparent);
                    imagecolortransparent($image, $transparent);
                }
                break;
        default:
            return false;
    }
    return $image;
}

function saveImage($image) {
    $fileName = 'uploads/' . uniqid() . '.png';
    imagepng($image, $fileName);
    imagedestroy($image);
    return $fileName;
}

function deleteImage($filePath) {
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

if (isset($_GET['url']) && isset($_GET['action'])) {
    $url = $_GET['url'];
    $action = $_GET['action'];
    $value = isset($_GET['value']) ? intval($_GET['value']) : 0;

    if ($action === 'Blur' && ($value < 1 || $value > 100)) {
        echo json_encode(['error' => 'Value for blur must be between 1 and 100']);
        exit;
    }

    $image = applyEffect($url, $action, $value);

    if ($image) {
        $savedImagePath = saveImage($image);
        echo json_encode(['image_url' => $savedImagePath]);

        // Use a background process to delete the image after 20 seconds
        exec('php -r "sleep(20); unlink(\'' . $savedImagePath . '\');" > /dev/null 2>&1 &');
    } else {
        echo json_encode(['error' => 'Invalid action or image type']);
    }
} else {
    echo json_encode(['error' => 'Missing parameters']);
}
?>
