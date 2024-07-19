<?php

if (isset($_POST['taskID'])) {
    $taskID = $_POST['taskID'];

    include 'conexion.php';

    $sql = "DELETE FROM tareas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $taskID);
    
    if ($stmt->execute()) {
        $res = ['status' => 'success', 'message' => 'Tarea eliminada con éxito'];
    } else {
        $res = ['status' => 'error', 'message' => 'Error al eliminar la tarea'];
    }

    $stmt->close();
    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($res);
}else{
    $res = ['status' => 'error', 'message' => 'No se ha recibido el ID de la tarea'];
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode($res);
}