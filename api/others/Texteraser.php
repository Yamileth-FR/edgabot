<?php
// Establece la cabecera de contenido como JSON en UTF-8
header('Content-Type: application/json; charset=utf-8');

// Verifica que se han proporcionado los parámetros 'text' y 'amount'
if (isset($_GET['text']) && isset($_GET['amount'])) {
    // Decodifica los valores de los parámetros para manejar caracteres especiales
    $text = urldecode($_GET['text']);
    $amount = (int)$_GET['amount']; // Convertir a entero
    
    // Divide el texto en un array de palabras
    $words = explode(' ', $text);
    
    // Verifica si el amount es mayor que la cantidad de palabras
    if ($amount >= count($words)) {
        // Si es mayor, devuelve un error
        $response = [
            'response' => 'No hay esa cantidad de palabras.'
        ];
    } else {
        // Calcula la cantidad de palabras que se van a mantener
        $wordsToKeep = count($words) - $amount;
        
        // Obtiene las palabras que se deben mantener
        $result = array_slice($words, 0, $wordsToKeep);
        
        // Combina las palabras en una cadena
        $modifiedText = implode(' ', $result);
        
        // Prepara la respuesta JSON
        $response = [
            'response' => $modifiedText
        ];
    }
} else {
    // Si no se proporcionan los parámetros necesarios, devuelve un error
    $response = [
        'response' => 'Por favor, proporciona los parámetros \'text\' y \'amount\'.'
    ];
}

// Devuelve la respuesta JSON codificada en UTF-8
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
