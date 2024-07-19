CREATE DATABASE gestion_tareas;

USE gestion_tareas;

CREATE TABLE usuarios (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre_usuario VARCHAR(100),
correo VARCHAR(100),
contraseña VARCHAR(255)
);

CREATE TABLE tareas (
id INT AUTO_INCREMENT PRIMARY KEY,
titulo VARCHAR(255) NOT NULL,
descripcion TEXT NOT NULL,
fecha_vencimiento DATE NOT NULL,
id_usuario INT
);

ALTER TABLE tareas ADD FOREIGN KEY (id_usuario) REFERENCES usuarios(id);