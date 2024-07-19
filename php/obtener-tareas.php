<?php

require_once 'conexion.php';
include 'sesion.php';


$sql = "SELECT * FROM tareas WHERE id_usuario = " . $user['id'];
$result = $conn->query($sql);

$tareas = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tareas[] = $row;
    }
}

echo json_encode($tareas);

?>