<?php
session_start();

require_once 'conexion.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];

    ## Validations
    
    # Verificar que no haya campos vacíos
    if(empty($username) || empty($password)){
        echo json_encode(['status' => 'error', 'message' => 'Por favor, rellene todos los campos']);
        exit();
    }

    # Verificar que el nombre de usuario solo contenga letras y números
    if(!preg_match('/^[a-zA-Z0-9]+$/', $username)){
        echo json_encode(['status' => 'error', 'message' => 'El nombre de usuario solo puede contener letras y números']);
        exit();
    }

    # Verificar que el usuario exista
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$username'";
    $result = $conn->query($sql);
    
    if($result->num_rows == 0){
        echo json_encode(['status' => 'error', 'message' => 'El usuario no existe']);
        exit();
    }

    ## End of validations

    $user = $result->fetch_assoc();

    if(password_verify($password, $user['contraseña'])){
        $_SESSION['user'] = $user;
        echo json_encode(['status' => 'success', 'message' => 'Inicio de sesión exitoso']);
    }else{
        echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta']);
    }
}