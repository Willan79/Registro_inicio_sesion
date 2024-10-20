<?php
// Indicar que la respuesta será en formato JSON
header("Content-Type: application/json");
// Incluir el archivo de configuración de la base de datos
require 'baseDatos.php';

// Obtener los datos de la solicitud en formato JSON
$data = json_decode(file_get_contents("php://input"), true);

// Verificar que los campos de correo y contraseña no estén vacíos
if (!empty($data['correo']) && !empty($data['contraseña'])) {
  $correo = htmlspecialchars(strip_tags($data['correo']));
  $contraseña = $data['contraseña'];

  $query = "SELECT * FROM usuarios WHERE correo = :correo";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(":correo", $correo);
  $stmt->execute();

  // Si se encuentra un usuario con ese correo
  if ($stmt->rowCount() > 0) {
    // Obtener los datos del usuario
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar la contraseña
    if (password_verify($contraseña, $usuario['contraseña'])) {
      // Si la contraseña es correcta, devolver los datos del usuario
      echo json_encode([
        'message' => 'Inicio de sesión exitoso',
        'usuario' => [
          'id' => $usuario['id'],
          'correo' => $usuario['correo']
        ]
      ]);
    } else {
      echo json_encode(['message' => 'Contraseña incorrecta']);
    }
  } else {
    echo json_encode(['message' => 'Usuario no encontrado']);
  }
} else {
  echo json_encode(['message' => 'Datos incompletos']);
}
