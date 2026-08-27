<?php
$host = '172.17.0.1'; 
$user = 'root';
$password = 'root123';
$database = 'universidad';

$conn = new mysqli($host, $user, $password, $database);
$mensaje = "";

if (!$conn->connect_error) {
    $conn->query("CREATE TABLE IF NOT EXISTS contactos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100),
        correo VARCHAR(100),
        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $correo = $conn->real_escape_string($_POST['correo']);
        
        if (!empty($nombre) && !empty($correo)) {
            $sql = "INSERT INTO contactos (nombre, correo) VALUES ('$nombre', '$correo')";
            if ($conn->query($sql) === TRUE) {
                $mensaje = "<p style='color: green; font-weight: bold;'>¡Datos guardados exitosamente en la base de datos MySQL!</p>";
            } else {
                $mensaje = "<p style='color: red;'>Error al guardar: " . $conn->error . "</p>";
            }
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario con Base de Datos - Docker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Arquitectura Web con Docker Compose</h1>
            <p>Formulario interactivo conectado a Base de Datos MySQL</p>
        </header>

        <main>
            <section class="card">
                <h2>Registrar un Dato de Prueba</h2>
                <?php echo $mensaje; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" name="nombre" required placeholder="Ej. Rodrigo Villa">
                    </div>
                    <div class="form-group">
                        <label>Correo:</label>
                        <input type="email" name="correo" required placeholder="Ej. correo@usil.pe">
                    </div>
                    <button type="submit" class="btn">Guardar en Base de Datos</button>
                </form>
            </section>

            <section class="card">
                <h2>Registros Almacenados en MySQL</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!$conn->connect_error) {
                            $resultado = $conn->query("SELECT * FROM contactos ORDER BY id DESC");
                            if ($resultado && $resultado->num_rows > 0) {
                                while($row = $resultado->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['nombre']}</td>
                                            <td>{$row['correo']}</td>
                                            <td>{$row['fecha']}</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' style='text-align:center;'>No hay registros todavía. ¡Prueba llenando el formulario!</td></tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>

        <footer>
            <p>Práctica de Infraestructura de Software - Killercoda</p>
        </footer>
    </div>
</body>
</html>
