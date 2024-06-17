<?php
session_start();

// Ruta del archivo JSON
$archivo_json = 'datos.json';

// Verificar la existencia de datos en el envío
if(isset($_POST['fullname'], $_POST['email'], $_POST['username'], $_POST['password'], $_POST['role'])){
    // Obtener los datos del formulario
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Obtener el rol seleccionado por el usuario

    // Contar el número de usuarios administradores existentes en el archivo JSON
    $adminCount = 0;
    $usuarios = file_get_contents($archivo_json);
    $usuarios_array = json_decode($usuarios, true);
    foreach ($usuarios_array as $usuario) {
        if ($usuario['role'] === 'administrador') {
            $adminCount++;
        }
    }

    // Verificar si ya existe un usuario administrador en el archivo JSON
    if ($adminCount >= 1 && $role === 'administrador') {
        echo "<script>alert('There can only be one administrator user.'); window.location.href = 'register.php';</script>";
        exit; // Detener la ejecución del script si ya existe un administrador
    }

    // Hashear la contraseña , para preservar la seguridad
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Verificar si el nombre de usuario ya existe
    if(isset($usuarios_array[$username])){
        echo "<script>alert('The username already exists. Please choose another one.');</script>";
        echo "<script>window.location.href = 'register.php';</script>";
        exit; // Detener la ejecución del script , si este falla
    }

    // Crear un array con los datos del nuevo usuario
    $nuevo_usuario = array(
        'fullname' => $fullname,
        'email' => $email,
        'username' => $username,
        'password' => $hashed_password,
        'role' => $role // Almacenar el rol en el array de este usuario
    );

    // Agregar el nuevo usuario al array
    $usuarios_array[$username] = $nuevo_usuario;

    // Convertir el array a formato JSON
    $json_usuarios = json_encode($usuarios_array);

    // Escribir el JSON actualizado en el archivo
    if (file_put_contents($archivo_json, $json_usuarios . PHP_EOL) !== false) {

        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role; // Almacenar el rol en la sesión del usuario

        //file_put_contents($username . '-precio_por_hora.txt','');


         echo "<script>window.location.href = 'login.php';</script>";
         // Redirigir al script de inicio de sesión
        exit;
    } else {
        echo "<script>alert('Error writing to the JSON file.');</script>";
    }
} else {

}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UMUZ - Register</title>
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../UMUZ/css/estilos.css">
</head>
<body class="login-body">
    <section class="login-section">
    <div class="login-container" style="margin-top: 20px;">
            <div class="login-content">
                <div class="login-form">
                    <div class="login-header">
                        <h3>UMUZ - Register</h3>
                    </div>
                    <form action="register.php" method="post" id="registerForm" class="signin-form">
                        <div class="form-group">
                            <input type="text" id="fullname" name="fullname" placeholder="Full Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="Email" required>
                        </div>
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
                          <label for="user-type">Select user type:</label>
                          <select name="role" id="role" required>
                            <option value="usuario">User</option>
                              <!--x-->
                                <option value="administrador">Admin</option>
                              </select>
                        </div>
                        <div class="form-group">
                            <button type="submit">Sign Up</button>
                        </div>
                    </form>
                    <div class="create-account-link">
                        <p><a href="login.php">Already have an account? Sign in</a></p>
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
