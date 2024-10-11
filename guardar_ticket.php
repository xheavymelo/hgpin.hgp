<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // El usuario por defecto de MySQL en XAMPP es "root"
$password = ""; // La contraseña por defecto es vacía
$dbname = "soporte_tecnico";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario y aplicar validación básica
$nombre = htmlspecialchars($_POST['nombre']);
$email = htmlspecialchars($_POST['email']);
$asunto = htmlspecialchars($_POST['asunto']);
$descripcion = htmlspecialchars($_POST['descripcion']);

// Preparar la declaración SQL para evitar inyecciones
$stmt = $conn->prepare("INSERT INTO tickets (nombre, email, asunto, descripcion) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nombre, $email, $asunto, $descripcion);

// Ejecutar la declaración
if ($stmt->execute()) {
    // Obtener el ID del último ticket insertado
    $last_id = $conn->insert_id;

    // Redirigir a la página de confirmación con los detalles del ticket
    header("Location: confirmacion_ticket.php?ticket_id=" . $last_id);
    exit();
} else {
    $error = "Error: " . $stmt->error;
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soporte Técnico</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Altura completa de la ventana */
            padding: 20px;
            text-align: center; /* Centrar el texto */
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%; /* Ancho completo en móviles */
        }
        h1, h2 {
            margin-bottom: 20px; /* Espacio inferior */
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
            text-align: left; /* Alinear etiquetas a la izquierda */
        }
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .mensaje {
            margin-top: 20px;
            color: red; /* Color rojo para mensajes de error */
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Sistema de Soporte Técnico</h1>
        </header>

        <section class="form-section">
            <h2>Crear nuevo ticket</h2>
            <form action="guardar_ticket.php" method="POST" id="ticket-form">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required>

                <label for="asunto">Asunto:</label>
                <input type="text" id="asunto" name="asunto" required>

                <label for="descripcion">Descripción del problema:</label>
                <textarea id="descripcion" name="descripcion" rows="4" required></textarea>

                <button type="submit">Enviar ticket</button>
            </form>
            <!-- Sección para mensajes de error -->
            <?php if (isset($error)) echo "<div class='mensaje'>$error</div>"; ?>
        </section>

        <!-- Botón para abrir WhatsApp -->
        <section class="whatsapp-section">
            <h2>Contacto por WhatsApp</h2>
            <a href="https://wa.me/595976263126" class="button" target="_blank">Contactar por WhatsApp</a>
            <p>Ante cualquier solicitud de un <strong>más alto requerimiento</strong> haz clic en el botón</p>
        </section>
    </div>
</body>
</html>
