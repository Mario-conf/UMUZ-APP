<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../UMUZ/login.php");
    exit;
}

// Directorio donde se almacenarán los archivos JSON de las notas de los usuarios
$notas_dir = dirname(__FILE__) . "/notas/";

// Verificar si el directorio notas existe, si no, intentar crearlo
if (!file_exists($notas_dir)) {
    if (!mkdir($notas_dir)) {
        die("Error: No se pudo crear el directorio de notas.");
    }
}

// Función para obtener el nombre del archivo JSON de notas del usuario actual
function obtener_archivo_notas($usuario) {
    global $notas_dir;
    return $notas_dir . $usuario . '-notes.json';
}

// Función para obtener las notas del usuario actual
function obtener_notas_usuario($usuario) {
    $archivo_notas = obtener_archivo_notas($usuario);
    if (file_exists($archivo_notas)) {
        $datos_json = file_get_contents($archivo_notas);
        return json_decode($datos_json, true);
    } else {
        return [];
    }
}

// Función para guardar las notas del usuario actual
function guardar_notas_usuario($usuario, $notas) {
    $archivo_notas = obtener_archivo_notas($usuario);
    $datos_json = json_encode($notas, JSON_PRETTY_PRINT);
    file_put_contents($archivo_notas, $datos_json);
}

// Obtener las notas del usuario actual
$notas_usuario = obtener_notas_usuario($_SESSION['username']);

// Manejar las operaciones de agregar, editar y eliminar notas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agregar'])) {
        $titulo = $_POST['titulo'];
        $contenido = $_POST['contenido'];
        $notas_usuario[] = ['titulo' => $titulo, 'contenido' => $contenido];
        guardar_notas_usuario($_SESSION['username'], $notas_usuario);
    } elseif (isset($_POST['editar'])) {
        $indice = $_POST['indice'];
        $titulo = $_POST['titulo'];
        $contenido = $_POST['contenido'];
        $notas_usuario[$indice] = ['titulo' => $titulo, 'contenido' => $contenido];
        guardar_notas_usuario($_SESSION['username'], $notas_usuario);
    } elseif (isset($_POST['eliminar'])) {
        $indice = $_POST['indice'];
        array_splice($notas_usuario, $indice, 1);
        guardar_notas_usuario($_SESSION['username'], $notas_usuario);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../UMUZ/css/notes.css">
    <title>UMUZ - Notes</title>
    <style>
   :root {
            --primary-color: #ca43ff; /* Morado */
            --secondary-color: #450074; /* Morado oscuro */
            --background-color: rgb(218, 218, 218); /* Gris claro */
            --text-color: #333; /* Color de texto principal */
            --light-color: #fff; /* Blanco para resaltar */
            --letras: rgb(27, 0, 78); /* Color de texto adicional */
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: var(--background-color);
            color: var(--letras);
            transition: background-color 0.3s ease;
        }

        .container {
            max-width: 500px; /* Ancho máximo del contenedor */
            margin: 0 auto; /* Centrar el contenedor horizontalmente */
            padding: 20px; /* Añadir relleno */
            background-color: var(--light-color);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: var(--letras);
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #c7c7c7; 
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }


        button {
            background-color: var(--primary-color);
            color: var(--light-color);
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 3px;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: var(--secondary-color);
        }

        form input[type="text"],
        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: #c7c7c7; 
        }

        a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: var(--secondary-color);
            color: var(--light-color);
        }

        /* Estilos de los enlaces */
        a { 
            color: var(--primary-color);
        } 

        .btn { 
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white; 
            width: 460px; 
            text-align: center;
            cursor: pointer; 
            text-decoration: none;
            border-radius: 4px; 
            transition: background-color 0.3s ease;
            margin-bottom: 20px; 
        } 

        .btn:hover {
            background-color: #7a00cc; 
            text-decoration: none; 
        } 
    </style></head>
<body><br><br>
    <div class="container">
        <h1>UMUZ - Notes</h1>
        <form method="post">
            <input type="text" name="titulo" placeholder="Title" required>
            <textarea name="contenido" placeholder="Content" required></textarea>
            <button type="submit" name="agregar">Add Note</button>
        </form>
        <ul>
            <?php foreach ($notas_usuario as $indice => $nota): ?>
                <li>
                    <h3><?php echo $nota['titulo']; ?></h3>
                    <p><?php echo $nota['contenido']; ?></p>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="indice" value="<?php echo $indice; ?>">
                        <input type="hidden" name="titulo" value="<?php echo $nota['titulo']; ?>">
                        <input type="hidden" name="contenido" value="<?php echo $nota['contenido']; ?>">
                    </form>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="indice" value="<?php echo $indice; ?>">
                        <button type="submit" name="eliminar">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="../UMUZ/web.php" class="btn" id="web-panel-btn">Go to Web Panel</a>
    </div>
</body>
</html>