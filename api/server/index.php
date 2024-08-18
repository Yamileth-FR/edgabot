<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$uploadDir = 'uploads/';
$maxFileAge = 86400; // 24 horas en segundos

// Función para limpiar archivos antiguos
function cleanOldFiles($dir, $maxAge) {
    $files = glob($dir . '*.html');
    $now = time();

    foreach ($files as $file) {
        if (is_file($file)) {
            $fileModifiedTime = filemtime($file);
            if ($now - $fileModifiedTime > $maxAge) {
                unlink($file);
            }
        }
    }
}

// Limpia los archivos antiguos antes de manejar el archivo nuevo
cleanOldFiles($uploadDir, $maxFileAge);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileType = mime_content_type($_FILES['file']['tmp_name']);
        if ($fileType !== 'text/html') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only HTML files are allowed.']);
            exit;
        }

        $uploadFile = $uploadDir . basename($_FILES['file']['name']);

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            $fileUrl = 'https://edgabot.akiomae.com/api/server/uploads/' . $uploadFile;
            echo json_encode(['status' => 'success', 'message' => 'File uploaded successfully.', 'url' => $fileUrl]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No file uploaded or there was an upload error.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
<script>

function updateVisitCount() {
  fetch('../visit_counter.php')
    .then(response => response.json())
    .then(data => {
      document.getElementById('visit-count').textContent = data.count;
    })
    .catch(error => {
      console.error('Error al obtener el conteo de visitas:', error);
      document.getElementById('visit-count').textContent = 'Error';
    });
}

document.addEventListener('DOMContentLoaded', updateVisitCount);
</script>
