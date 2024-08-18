<?php
header('Content-Type: application/json');

// Obtener el texto del parámetro "text" en la URL
$text = $_GET['text'];

// Contar las palabras
$wordCount = str_word_count($text);

// Contar las letras
$letterCount = strlen(preg_replace('/\s+/', '', $text));

// Crear un array con los resultados
$result = array(
    "palabras" => $wordCount,
    "letras" => $letterCount
);

// Convertir el array a formato JSON y enviarlo como respuesta
echo json_encode($result);
?>