<?php
// Indicar que la respuesta será en formato JSON
header("Content-Type: application/json");
// Incluir el archivo de configuración de la base de datos
require 'baseDatos.php';

// Obtener los datos de la solicitud en formato JSON
$data = json_decode(file_get_contents("php://input"), true);

// Verificar que los datos requeridos no estén vacíos
if ( !empty($data['correo']) && !empty($data['contraseña'])) {
  
  $correo = htmlspecialchars(strip_tags($data['correo']));
  $contraseña = password_hash($data['contraseña'], PASSWORD_DEFAULT);

  // Verificar si el correo ya está registrado
  $query = "SELECT * FROM usuarios WHERE correo = :correo";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(":correo", $correo);
  $stmt->execute();

  // Si ya existe un usuario con ese correo, enviar un mensaje de error
  if ($stmt->rowCount() > 0) {
    echo json_encode(['message' => 'El correo ya está registrado']);
  } else {
    $query = "INSERT INTO usuarios (correo, contraseña) VALUES (:correo, :contraseña)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":correo", $correo);
    $stmt->bindParam(":contraseña", $contraseña);

    // Ejecutar la consulta y verificar si se insertó correctamente
    if ($stmt->execute()) {
      echo json_encode(['message' => 'Usuario registrado con éxito']);
    } else {
      echo json_encode(['message' => 'Error al registrar el usuario']);
    }
  }
} else {
  // Si faltan datos en la solicitud, enviar un mensaje de error
  echo json_encode(['message' => 'Datos incompletos']);
}
