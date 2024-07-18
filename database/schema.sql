CREATE DATABASE gestion_tareas;

CREATE TABLE usuarios (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre_usuario VARCHAR(100),
correo VARCHAR(100),
contraseña VARCHAR(255)
);