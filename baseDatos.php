<?php
// Parámetros de conexión a la base de datos
$host = 'localhost';
$db_name = 'registro_inicio_sesion';
$username = 'root';
$password = '';

// Intentar conectarse a la base de datos usando PDO
try {
  // Crear una instancia de PDO para conectar a MySQL
  $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  // Mostrar un mensaje de error si la conexión falla
  die("Error al conectar a la base de datos: " . $e->getMessage());
}
