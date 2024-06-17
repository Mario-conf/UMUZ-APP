<?php
session_start();

// Ruta del archivo JSON de usuarios en el que se almacenan datos
$archivo_json = 'datos.json';

// Verificar si se han enviado datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'], $_POST['password'])) {
    // Obtener los datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Leer el contenido del archivo JSON de usuarios
    $usuarios = file_get_contents($archivo_json);

    // Convertir el contenido JSON a un array asociativo para su manejo
    $usuarios_array = json_decode($usuarios, true);

    // Verificar si el usuario existe y las credenciales de usuario son válidas
    if (isset($usuarios_array[$username]) && password_verify($password, $usuarios_array[$username]['password'])) {
        // Autenticación correcta
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        // Asignar el rol del usuario
        $_SESSION['role'] = $usuarios_array[$username]['role'];
        $token = generateUniqueToken(); // Generar un token único para la sesion
        $_SESSION['auth_token'] = $token;
        // Redirigir según el rol que tenga el usuario
        if ($_SESSION['role'] === 'administrador') {
            header("Location: user.php?token=$token"); // Redirigir al panel de administración de usuarios
        } else {
            header("Location: web.php?token=$token"); // Redirigir a la página princiapl de la App
        }
        exit;
    } else {
        // Nombre de usuario o contraseña incorrectos
        $error_message = "Nombre de usuario o contraseña incorrectos.";
    }
}

// Función para generar un token único por sesiones
function generateUniqueToken($length = 32) {
    // Caracteres permitidos en el token de sesion
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $token = '';
    // Generar un token aleatorio de sesion para cada usuario , uno diferente en cada sesion
    for ($i = 0; $i < $length; $i++) {
        $token .= $characters[rand(0, $charactersLength - 1)];
    }
    return $token;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UMUZ - Login</title>
  <link rel="stylesheet" href="../UMUZ/css/estilos.css">
</head>
<body class="login-body">

    <section class="login-section">
        <div class="login-container">
            <div class="login-content">
                <div class="login-form">
                    <div class="login-header">
                        <h3>UMUZ - Login</h3>
                    </div>
                    <form action="login.php" method="post" id="loginForm" class="signin-form">
                        <div class="form-group">
                            <input type="text" id="username" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <div class="password-group">
                                <input id="password-field" type="password" name="password" placeholder="Password" required>
                                <div class="password-toggle">
                                    <span class="toggle-password"><i class="fa fa-eye"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="redirect_to_web" value="true">
                            <button type="submit">Sign In</button>
                        </div>
                        <div class="form-group">
                            <div class="checkbox-wrap">
                                <label>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                    </form>
                    <div class="create-account-link">
                        <p><a href="register.php">Don't have an account? Sign up</a></p>
                    </div>
                    <div class="create-account-link">
                        <p class="bole"><a href="http://localhost/UMUZ/" style="font-size: 1em;">Go back to the Website</a></p>
                         </div>
                </div>
            </div>
        </div>
    </section>

  <script src="js/main.js"></script>


<footer>
  <p>Developed by</p><a href="https://github.com/Mario-conf" target="_blank"> Mario.conf</a>
</footer>
</body>
</html>
