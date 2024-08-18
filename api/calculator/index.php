<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $response = array();
    if (isset($_GET['num1']) && isset($_GET['operation']) && isset($_GET['num2'])) {
        $num1 = $_GET['num1'];
        $operation = $_GET['operation'];
        $num2 = $_GET['num2'];

        switch ($operation) {
            case 'add':
                $result = $num1 + $num2;
                $response['success'] = true;
                $response['result'] = $result;
                break;
            case 'sub':
                $result = $num1 - $num2;
                $response['success'] = true;
                $response['result'] = $result;
                break;
            case 'mul':
                $result = $num1 * $num2;
                $response['success'] = true;
                $response['result'] = $result;
                break;
            case 'div':
                if ($num2 != 0) {
                    $result = $num1 / $num2;
                    $response['success'] = true;
                    $response['result'] = $result;
                } else {
                    $response['success'] = false;
                    $response['result'] = "Error: Divisi�n por cero no est� permitida";
                }
                break;
            default:
                $response['success'] = false;
                $response['result'] = "Operaci�n no reconocida";
        }
    } else {
        $response['success'] = false;
        $response['result'] = "Por favor, proporciona una operaci�n en los par�metros 'num1', 'operation', y 'num2'";
    }
    echo json_encode($response);
}
?>