<?php
session_start();

// Definir la contraseña predeterminada
$contraseña_predeterminada = 'hgpin1190';
$error = "";

// Procesar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contraseña_ingresada = $_POST['contraseña'];

    // Verificar la contraseña
    if ($contraseña_ingresada === $contraseña_predeterminada) {
        // Redirigir a la página de gestión de usuarios
        header("Location: manage_users.php");
        exit();
    } else {
        $error = "Contraseña incorrecta. Inténtalo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Administrador</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .form-section {
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        .error {
            color: red;
        }

        .button {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Verificar Administrador</h1>
    </header>
    <section class="form-section">
        <form action="verificar_administrador.php" method="POST">
            <label for="contraseña">Ingresa la contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required>

            <button type="submit" class="button">Verificar</button>
        </form>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    </section>
</body>
</html>
