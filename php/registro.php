<?php

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];
    $email = $_POST['email'];

    // Validaciones
    
    // Verificar que no haya campos vacíos
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, rellene todos los campos']);
        exit();
    }

    // Verificar que el correo sea válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Email no válido']);
        exit();
    }

    // Verificar que la contraseña tenga al menos 6 caracteres
    if (strlen($password) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'La contraseña debe tener al menos 6 caracteres']);
        exit();
    }

    // Verificar que la contraseña tenga al menos una letra minúscula
    if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^a-zA-Z\d]/', $password)) {
        echo json_encode(['status' => 'error', 'message' => 'La contraseña debe tener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial']);
        exit();
    }

    // Verificar que el nombre de usuario solo contenga letras y números
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        echo json_encode(['status' => 'error', 'message' => 'El nombre de usuario solo puede contener letras y números']);
        exit();
    }

    // Verificar que el usuario o email no existan
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$username' OR correo = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'El usuario o email ya existe']);
        exit();
    }
    // Fin de las validaciones

    if ($password != $confirm_password) {
        echo json_encode(['status' => 'error', 'message' => 'Las contraseñas no coinciden']);
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre_usuario, correo, contraseña) VALUES ('$username', '$email', '$hashedPassword')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Usuario registrado correctamente']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar el usuario: ' . $conn->error]);
    }
}

?>
