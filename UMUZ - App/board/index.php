<?php
// Verificar si la sesión no está iniciada o no
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado o no
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../css/board.css">
  <title>UMUZ - Whiteboard</title>
</head>
<body>
  <header>
    <nav>
      <div class="logo">UMUZ - Whiteboard</div>
      <ul class="nav-links">
        <li>
          <label class="color-picker-label" for="colorPicker">Color</label>
          <input type="color" id="colorPicker" value="#000000">
        </li>
        <li>
          <label class="thickness-slider-label" for="thicknessSlider">Line Thickness</label>
          <input type="range" id="thicknessSlider" min="1" max="20" value="5">
        
          <li>
            <button id="undoButton" class="navbar-button" onclick="undo()"><i class="fas fa-arrow-left"></i></button>
          </li>
          <li>
            <button class="navbar-button" id="textToolButton" onclick="activateTextTool()"><i class="fas fa-font"></i></button>
          </li>
          <li>
            <button id="redoButton" class="navbar-button" onclick="redo()"><i class="fas fa-arrow-right"></i></button>
          </li>
        </li>

       <li><button class="exp-button" id="exportJpegButton">Export</button></li>
       <li><button class="clear-button" id="clearButton">Clear</button></li>
       <li><button class="exp-button" onclick="window.location.href='../web.php'" class="btn">Go to Web Panel</button></li>

        </ul>
    </nav>
  </header>

  <div class="container">
    <canvas id="paintCanvas" width="800" height="600"></canvas>
  </div>
  <script src="../js/board.js"></script>
  <footer>
  <style>
      footer a {
        color: #dddddd;
    text-decoration: none;
    font-weight: bold;
  }
  
footer a:hover{
  color: #ffffff;
}
  footer {
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: justify;
    color: #dddddd;
    background-color: #333;
    width: 100%;
}

  </style>
  <p>Developed by</p><a href="https://github.com/Mario-conf" target="_blank">Mario.conf</a>
 
</footer>

</body>
</html>

