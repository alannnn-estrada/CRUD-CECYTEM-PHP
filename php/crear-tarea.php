<?php

require_once 'conexion.php';
include 'sesion.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($user['id'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $dueDate = $_POST['dueDate'];

    // Validations 

    // Validar que los campos no estén vacíos
    if (empty($title) || empty($description) || empty($dueDate)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son requeridos']);
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

    $sql = "INSERT INTO tareas (titulo, descripcion, fecha_vencimiento, id_usuario) VALUES ('$title', '$description', '$dueDate', " . $user['id'] . ")";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Tarea creada con éxito']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al crear la tarea']);
    }

}