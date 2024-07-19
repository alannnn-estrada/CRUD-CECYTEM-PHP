<?php

require_once 'conexion.php';
include 'sesion.php';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($user['id'])) {
    $taskID = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $dueDate = $_POST['dueDate'];

    // Validations 

    // Validar que los campos no estén vacíos
    if (empty($title) || empty($description) || empty($dueDate) || empty($taskID)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son requeridos']);
        echo "titulo: $title, descripcion: $description, fecha_vencimiento: $dueDate, id: $taskID";
        return;
    }

    // Validar que la fecha de vencimiento sea mayor a la fecha actual
    $currentDate = date('Y-m-d');
    if ($dueDate < $currentDate) {
        echo json_encode(['status' => 'error', 'message' => 'La fecha de vencimiento debe ser mayor a la fecha actual']);
        return;
    }

    // Validar que el título solo contenga letras, números y espacios
    if (!preg_match('/^[a-zA-Z0-9 ]+$/', $title)) {
        echo json_encode(['status' => 'error', 'message' => 'El título solo puede contener letras, números y espacios']);
        return;
    }

    // Validar que la descripción solo contenga letras, números y espacios
    if (!preg_match('/^[a-zA-Z0-9 ]+$/', $description)) {
        echo json_encode(['status' => 'error', 'message' => 'La descripción solo puede contener letras, números y espacios']);
        return;
    }

    // End of validations

    $userId = $user['id'];
    $sql = "UPDATE tareas SET titulo = ?, descripcion = ?, fecha_vencimiento = ? WHERE id = ? AND id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $title, $description, $dueDate, $taskID, $userId);
    
    if ($stmt->execute()) {
        $res = ['status' => 'success', 'message' => 'Tarea actualizada con éxito'];
    } else {
        $res = ['status' => 'error', 'message' => 'Error al actualizar la tarea'];
    }

    $stmt->close();
    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($res);
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    header('Location: ../html/login.html');
}