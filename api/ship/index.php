<?php
// Verificar si se han proporcionado los parámetros nombre1 y nombre2
if (!isset($_GET['nombre1']) || !isset($_GET['nombre2'])) {
    // Mostrar un mensaje de error si falta alguno de los parámetros
    $respuesta = array(
        "error" => "Por favor, asegúrate de proporcionar ambos nombres (nombre1 y nombre2) , ejemplo: ?nombre1=Juan&nombre2=Maria"
    );
    echo json_encode($respuesta);
    exit(); // Terminar el script
}


// Obtener los nombres de los parámetros GET
$nombre1 = $_GET['nombre1'];
$nombre2 = $_GET['nombre2'];


// Función para combinar los nombres y generar la respuesta
function combinarNombres($nombre1, $nombre2) {
    // Tomar una parte del primer nombre
    $parte1 = substr($nombre1, 0, floor(strlen($nombre1) / 2));


    // Tomar una parte del segundo nombre
    $parte2 = substr($nombre2, floor(strlen($nombre2) / 2));


    // Combinar las partes de los nombres
    $nombreCombinado = $parte1 . $parte2;


    // Generar un porcentaje aleatorio del 1% al 100%
    $porcentaje = rand(1, 100);


    // Definir las frases según el rango de porcentaje
    if ($porcentaje >= 1 && $porcentaje <= 10) {
        $frases = [
            "No son compatibles, mejor busquen otra pareja.",
            "Quizás sea mejor reconsiderar su relación.",
            "Es posible que tengan dificultades para llevarse bien.",
            "Tienen gustos y perspectivas muy diferentes.",
            "Puede que necesiten trabajar en comunicación y entendimiento.",
            "No tienen mucho en común, lo que puede ser un desafío.",
            "Sus diferencias podrían causar tensiones en la relación.",
            "Es importante discutir sus expectativas y metas.",
            "Puede que necesiten tiempo para conocerse mejor.",
            "Sería útil establecer límites y expectativas claras desde el principio."
        ];
    } else if ($porcentaje >= 11 && $porcentaje <= 30) {
        $frases = [
            "Tienen una compatibilidad baja, pueden que enfrenten desafíos.",
            "Deben trabajar en resolver sus diferencias.",
            "Aunque tienen algunos puntos en común, pueden haber conflictos.",
            "Es importante tener en cuenta las necesidades del otro.",
            "La paciencia y la comprensión serán importantes en su relación.",
            "Pueden aprender mucho el uno del otro si están dispuestos a escuchar.",
            "Debe haber un compromiso mutuo para superar los obstáculos.",
            "Es posible que necesiten buscar ayuda externa para resolver problemas.",
            "Pueden encontrar la felicidad si están dispuestos a comprometerse.",
            "El respeto mutuo y la confianza son fundamentales para construir una relación sólida."
        ];
    } else if ($porcentaje >= 31 && $porcentaje <= 70) {
        $frases = [
            "Tienen una compatibilidad media, pueden llevarse bien en la mayoría de las situaciones.",
            "Es probable que disfruten de momentos agradables juntos.",
            "Pueden tener una relación estable y equilibrada.",
            "Sus intereses y valores se complementan bien.",
            "Ambos están dispuestos a comprometerse por el bien de la relación.",
            "Tienen una buena comunicación y pueden resolver conflictos de manera constructiva.",
            "Comparten suficientes intereses comunes para mantener la relación interesante.",
            "El apoyo mutuo será una fuente de fortaleza en tiempos difíciles.",
            "Pueden construir un futuro juntos si siguen trabajando en su relación.",
            "La confianza y el respeto mutuo son la base de su relación."
        ];
    } else if ($porcentaje >= 71 && $porcentaje <= 90) {
        $frases = [
            "Tienen una buena compatibilidad, disfruten su relación.",
            "Están destinados a pasar tiempo juntos.",
            "Pueden ser una pareja muy feliz.",
            "Comparten una conexión profunda y significativa.",
            "Se apoyan mutuamente en sus metas y sueños.",
            "La comunicación abierta y honesta fortalece su vínculo.",
            "Juntos pueden superar cualquier desafío que enfrenten.",
            "Su amor y compromiso los llevarán lejos en la vida.",
            "La pasión y la complicidad son el sello distintivo de su relación.",
            "Están viviendo una historia de amor verdadero."
        ];
    } else {
        $frases = [
            "¡Tienen una gran compatibilidad! ¡Son el uno para el otro!",
            "Están hechos el uno para el otro.",
            "Son una combinación perfecta.",
            "Su amor es verdaderamente único y especial.",
            "Están destinados a vivir una vida feliz juntos.",
            "La química entre ustedes es innegable.",
            "Juntos pueden enfrentar cualquier desafío que se les presente.",
            "Son la pareja perfecta en todos los sentidos.",
            "Su relación es un ejemplo de amor verdadero para todos.",
            "Están compartiendo un amor que perdurará para siempre."
        ];
    }


    // Seleccionar una frase aleatoria del arreglo
    $fraseAleatoria = $frases[array_rand($frases)];


    // Construir el arreglo de respuesta
    $respuesta = array(
        "nombres_combinados" => $nombreCombinado,
        "porcentaje" => $porcentaje,
        "frase" => $fraseAleatoria
    );


    // Devolver la respuesta en formato JSON
    return json_encode($respuesta);
}


// Llamar a la función y mostrar la respuesta
echo combinarNombres($nombre1, $nombre2);
?>