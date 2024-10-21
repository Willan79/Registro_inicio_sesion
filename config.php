<?php
// Configuración de la base de datos
$host = 'localhost'; 
$dbname = 'registro_inicio'; 
$username = 'root'; 
$password = ''; 

// Crear la conexión con MySQLi
$mysqli = new mysqli($host, $username, $password, $dbname);


// Verificar si hay algún error de conexión
if ($mysqli->connect_error) {
    die("Error de conexion: " . $mysqli->connect_error);
}else{
    echo "Conexion exitosa";
}
?>
