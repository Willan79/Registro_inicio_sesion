<?php
// Incluir la configuración de la base de datos
require 'config.php';

// Establecer el tipo de contenido como JSON
header("Content-Type: application/json");

// Obtener los datos enviados desde Postman o cualquier otro cliente
$data = json_decode(file_get_contents("php://input"), true);

// Verificar que todos los campos requeridos están presentes
if (!empty($data['nombre']) && !empty($data['apellido']) && !empty($data['correo']) && !empty($data['contraseña'])) {

    // Sanitizar los datos de entrada
    $nombre = htmlspecialchars(strip_tags($data['nombre']));
    $apellido = htmlspecialchars(strip_tags($data['apellido']));
    $correo = htmlspecialchars(strip_tags($data['correo']));
    $contraseña = password_hash($data['contraseña'], PASSWORD_DEFAULT); // Encriptar la contraseña

    // Verificar si el correo ya está registrado
    $query = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si ya existe un usuario con ese correo, enviar un mensaje de error
    if ($result->num_rows > 0) {
        echo json_encode(['message' => 'El correo ya está registrado']);
    } else {
        // Insertar el nuevo usuario
        $query = "INSERT INTO usuarios (nombre, apellido, correo, contraseña) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssss', $nombre, $apellido, $correo, $contraseña);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Usuario registrado con exito']);
        } else {
            echo json_encode(['message' => 'Error al registrar el usuario']);
        }
    }
} else {
    echo json_encode(['message' => 'Datos incompletos']);
}
?>

