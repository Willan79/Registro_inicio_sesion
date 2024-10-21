<?php
// Incluir la configuración de la base de datos
require 'config.php';

echo "<br />";

// Establecer el tipo de contenido como JSON
header("Content-Type: application/json");

// Obtener los datos enviados desde Postman
$data = json_decode(file_get_contents("php://input"), true);

// Verificar que los campos de correo y contraseña no están vacíos
if (!empty($data['correo']) && !empty($data['contraseña'])) {

    // Sanitizar los datos de entrada (evitando SQL Injection).
    $correo = htmlspecialchars(strip_tags($data['correo']));
    $contraseña = $data['contraseña'];

    // Verificar si el correo existe en la base de datos
    $query = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si el correo está registrado, verificar la contraseña
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        // Verificar la contraseña encriptada
        if (password_verify($contraseña, $usuario['contraseña'])) {
            echo json_encode(['message' => 'Inicio de sesion exitoso']);
        } else {
            echo json_encode(['message' => 'Contrasena incorrecta']);
        }
    } else {
        echo json_encode(['message' => 'El correo no está registrado']);
    }
} else {
    echo json_encode(['message' => 'Datos incompletos']);
}
?>
