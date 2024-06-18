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
    <title>UMUZ - Charts</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    <link rel="stylesheet" href="../css/chart.css">
    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            document.forms[0].classList.toggle('dark-mode');
            document.querySelectorAll('label').forEach(label => label.classList.toggle('dark-mode'));
      const isDarkMode = document.body.classList.contains('dark-mode');
            localStorage.setItem('dark-mode', isDarkMode);
        }
     const savedDarkMode = localStorage.getItem('dark-mode');
        if (savedDarkMode === 'true') {
            toggleDarkMode();
        }
    </script>
</head>
<body>
<form id="dataForm">
    <label for="categories">Categories:</label>
    <input type="text" id="categories" placeholder="Separated by commas (e.g. Cat1, Cat2, Cat3)">
    <br>
    <label for="values">Values:</label>
    <input type="text" id="values" placeholder="Separated by commas (e.g. 12, 19, 3, 5, 2)">
    <br>
    <label for="chartType">Chart Type:</label>
    <select id="chartType">
        <option value="bar">Bar Chart</option>
        <option value="area">Area Chart</option>
        <option value="line">Line Chart</option>
        <option value="radar">Radar Chart</option>
        <option value="pie">Pie Chart</option>
    </select>
    <br>
    <button type="button" onclick="updateChart()">Update Chart</button>
    <br>
    <button type="button" onclick="exportAsImage()">Export as Image</button>
    <br>
    <label for="csvInput" class="upload-button" onclick="chooseFile()">Select CSV File</label>
    <input type="file" id="csvInput" accept=".csv" onchange="loadCSV()" style="display: none;">
    <button type="button" class="dark-mode-toggle" onclick="toggleDarkMode()">Toggle Dark Mode</button>
    <a href="../web.php" class="btn">Go to Web Panel</a>
</form>

<br>
<canvas id="myChart"></canvas>
<script src="../js/chart.js"></script>
</body>
</html>