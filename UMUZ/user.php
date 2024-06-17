<?php
session_start();

// Verificar si el usuario está autenticado como administrador o no
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'administrador') {
    echo "<script>alert('You must log in as an administrator to access this page.');</script>";
    echo "<script>window.location.href = 'login.php';</script>"; // Redirigir a la página de inicio de sesión
    exit;
}

// Verificar el token si se proporciona en la URL o bien estan intentando acceder sin token
if (isset($_GET['token']) && isset($_SESSION['auth_token']) && $_GET['token'] === $_SESSION['auth_token']) {
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
        $username = $_POST['delete_user'];
        // Leer el archivo JSON de usuarios
        $usuarios = file_get_contents('datos.json');
        // Convertir el contenido JSON a un array asociativo , para su manejo
        $usuarios_array = json_decode($usuarios, true);
        // Verificar si el usuario existe en el sistema
        if (isset($usuarios_array[$username])) {
            // Eliminar al usuario del array asociado a el
            unset($usuarios_array[$username]);
            // Convertir el array actualizado a formato JSON
            $json_usuarios = json_encode($usuarios_array);
            // Escribir el JSON actualizado en el archivo
            if (file_put_contents('datos.json', $json_usuarios) !== false) {
                // Eliminar el archivo de datos del usuario asociado
                $user_data_file = $username . '-data.json';
                if (file_exists($user_data_file)) {
                    unlink($user_data_file);
                }
                // Eliminar el archivo JSON de notas del usuario asociado
                $user_notes_file = $username . '-notes.json';
                if (file_exists($user_notes_file)) {
                    unlink($user_notes_file);
                }
                echo "<script>alert('The user $username has been successfully deleted.');</script>";
                echo "<script>window.location.href = 'login.php';</script>"; 
            } else {
                echo "<script>alert('Error updating the JSON file of users.');</script>";
                echo "<script>window.location.href = 'user.php';</script>"; 
            }
        } else {
            echo "<script>alert('The user $username does not exist.');</script>";
            echo "<script>window.location.href = 'login.php';</script>"; 
        }
    }
} else {
    // Si el token no es válido, redirigiremos al usuario a la página de inicio de sesión
    echo "<script>alert('Invalid token.');</script>";
    echo "<script>window.location.href = 'login.php';</script>"; 
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMUZ - Management</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../UMUZ/css/estilos.css">
</head>
<body class="user-body">
    <div class="user-container">
        <div class="boxer">


        <h1>UMUZ - Management</h1>
        <p class="welcome-msg">Welcome , <?php echo $_SESSION['username']; ?></p>
        <section class="user-section">
            <form action="user.php?token=<?php echo $_SESSION['auth_token']; ?>" method="post">
                <div class="form-group">
                <h2 class="section-title">Delete User</h2>
                    <label for="delete_user">Select user to delete:</label>
                    <select name="delete_user" id="delete_user">
                        <?php
                        // Leer el archivo JSON de usuarios
                        $usuarios = file_get_contents('datos.json');
                        // Convertir el contenido JSON a un array asociativo para su visualizacion
                        $usuarios_array = json_decode($usuarios, true);
                        // Mostrar la lista de usuarios del sistema
                        foreach ($usuarios_array as $username => $user) {
                            echo "<option value=\"$username\">$username</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn-delete-user">Delete User</button>
            </form>

            <form action="web.php" method="GET">
                <input type="hidden" name="token" value="<?php echo $_SESSION['auth_token']; ?>">
                <button type="submit" class="btn-go-to-web">Go to Web Panel</button>
            </form>
        </section>
        <p><a href="login.php" class="logout-link">Logout</a></p>
    </div>
        </div>
    <footer>
        <p>Developed by</p><a href="https://github.com/Mario-conf" target="_blank"> Mario.conf</a>
    </footer>
    <script src="js/main.js"></script>
</body>

</html>
