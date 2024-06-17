<?php
// Verificar si la sesión no está iniciada o no
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado o no
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../UMUZ/login.php");
    exit;
}

// Obtener el nombre de usuario del usuario actual
$username = $_SESSION['username'];
$user_events_file = $username . '-events.json';

// Obtener los eventos del usuario actual
$user_events = array();
if (file_exists($user_events_file)) {
    // Leer los datos del archivo y decodificar el JSON
    $user_events = json_decode(file_get_contents($user_events_file), true);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Permissions-Policy" content="interest-cohort=()">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMUZ - Wheather</title>
    <link rel="stylesheet" href="../UMUZ/css/time.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">

    <a href="../UMUZ/web.php" class="btn" id="web-panel-btn">Go to Web Panel</a>

    <div class="search-box">
        <i class="fa-solid fa-location-dot"></i> 
        <input type="text" placeholder="Enter your location">
        <button class="fa-solid fa-magnifying-glass"></button>
    </div>


    <div class="not-found">           
        <img src="https://img.freepik.com/vector-gratis/error-404-ilustracion-concepto-paisaje_114360-7898.jpg" alt="">
        <p>Oops! Invalid location</p>
    </div>

        <div class="not-found">           
            <img src="https://img.freepik.com/vector-gratis/error-404-ilustracion-concepto-paisaje_114360-7898.jpg" alt="">
            <p>Oops ! invalid location</p>
        </div>

        <div class="weather-box">
            <img src="" alt="">
            <p class="temperature"></p>
            <p class="description"></p>
        </div>

        <div class="weather-details">

            <div class="humidity">
                <i class="fa-solid fa-water"></i>
                <div class="text">
                    <span></span>
                    <p>Humidity</p>
                </div>
            </div>

            <div class="wind">
                <i class="fa-solid fa-wind"></i>
                <div class="text">
                    <span></span>
                    <p>Wind Speed</p>
                </div>
            </div>

          
        </div>
        <a href="index.php" class="btn reload-btn">Reload</a>
    </div>


    <script src="https://kit.fontawesome.com/7c8801c017.js" crossorigin="anonymous"></script>
    <script src="../UMUZ/UMUZ/js/time.js"></script>
  
</body>
</html>