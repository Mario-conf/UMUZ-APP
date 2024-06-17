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
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="author" content="Mario.conf" />
        <link
          rel="icon"
          href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2280%22>📟​</text></svg>"
        />
        <link
          rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        />
        <link rel="stylesheet" href="../UMUZ/css/calc.css">
     <title>UMUZ - Calculator </title>
      </head>
      <header>
        <h1>UMUZ - Calculator </h1>
        <a href="../UMUZ/web.php" class="btn">Go to Web Panel</a>
        </header>
      <body>
        <!-- Estos son los botones y display de la calculadora-->

        <div class="calculator">
          <input type="text" id="display" disabled>
          <br>
          <button onclick="clearDisplay()">C</button>
          <button onclick="appendToDisplay('(')">(</button>
          <button onclick="appendToDisplay(')')">)</button>
          <button onclick="appendToDisplay('^')">^</button>
          <br>
          <button onclick="calculateSquareRoot()">√</button>
          <button onclick="calculateSin()">sin</button>
          <button onclick="calculateCos()">cos</button>
          <button onclick="toggleTheme()">🌓</button>
          <br>
          <button onclick="appendToDisplay('7')">7</button>
          <button onclick="appendToDisplay('8')">8</button>
          <button onclick="appendToDisplay('9')">9</button>
          <button onclick="appendToDisplay('/')">/</button>
          <br>
          <button onclick="appendToDisplay('4')">4</button>
          <button onclick="appendToDisplay('5')">5</button>
          <button onclick="appendToDisplay('6')">6</button>
          <button onclick="appendToDisplay('*')">*</button>
          <br>
          <button onclick="appendToDisplay('1')">1</button>
          <button onclick="appendToDisplay('2')">2</button>
          <button onclick="appendToDisplay('3')">3</button>
          <button onclick="appendToDisplay('-')">-</button>
          <br>
          <button onclick="appendToDisplay('0')">0</button>
          <button onclick="appendToDisplay('.')">.</button>
          <button onclick="calculateResult()">=</button>
          <button onclick="appendToDisplay('+')">+</button>
        </div>
<div id="extra" class="extra">
    <!-- Funcionalidades extra de la calculadora: -->

    <!-- Interés Simple y Compuesto -->
    <form id="calcularInteresForm" onsubmit="manejarFormularioInteres(); return false;">
    <label for="monto">Initial Amount:</label>
            <input type="text" id="monto" placeholder="Enter the amount" required>
            <label for="tasa">Annual Interest Rate:</label>
            <input type="text" id="tasa" placeholder="Enter the interest rate" required>
            <label for="tiempo">Time (in years):</label>
            <input type="text" id="tiempo" placeholder="Enter the time" required>
            <button type="submit">Calculate Interest</button>
  </form>
    <hr><br>
    <!-- Promedio -->
    <form id="calcularPromedioForm" onsubmit="iniciarCalculadoraPromedio(); return false;">
    <label for="numeros">Enter numbers (comma separated):</label>
            <input type="text" id="numeros" name="numeros" placeholder="E.g. 10, 20, 30" required>
            <button type="submit">Calculate Average</button>
  </form>
    <hr><br>
    <!-- Áreas y Volúmenes -->
    <form id="calcularAreaVolumenForm" onsubmit="calcularAreaVolumen(); return false;">
    <label for="tipoFigura">Select the figure:</label>
            <select id="tipoFigura" name="tipoFigura" required onchange="showDimensions()">
                <option value="triangulo">Triangle</option>
                <option value="cuadrado">Square</option>
                <option value="circulo">Circle</option>
            </select> 
            <div id="dimensionesFigura"></div>
            <button type="submit">Calculate Area/Volume</button>
        </form>
  <hr><br>
    <!-- Proporciones -->
    <form id="proporcionForm" onsubmit="resolverProporcion();">
    <label for="parte1">Part 1:</label>
            <input type="text" name="parte1" placeholder="Enter part 1" required>
            <label for="parte2">Part 2:</label>
            <input type="text" name="parte2" placeholder="Enter part 2" required>
            <label for="parte3">Part 3:</label>
            <input type="text" name="parte3" placeholder="Enter part 3" required>
            <label for="parte4">Part 4:</label>
            <input type="text" name="parte4" placeholder="Enter part 4" required>
            <button type="submit">Resolve Proportion</button>
        </form>
    <hr><br>
    <!-- Ecuaciones Lineales -->
    <form id="ecuacionForm" onsubmit="return resolverEcuacion()">
    <label for="formulaeq">ax+b=0</label><br><br>
            <label for="coeficiente">Coefficient (a):</label>
            <input type="text" name="coeficiente" placeholder="Enter the coefficient" required>
            <label for="constante">Constant (b):</label>
            <input type="text" name="constante" placeholder="Enter the constant" required>
            <button type="submit">Solve Linear Equation</button>
        </form>
    <hr><br>
<!-- Ecuación de Segundo Grado -->
<form id="ecuacionSegundoGradoForm">
<label for="formulaeq">ax^2 + bx + c = 0</label><br><br>
            <label for="coeficienteA">Coefficient a:</label>
            <input type="text" name="coeficienteA" placeholder="Enter coefficient a" required>
            <label for="coeficienteB">Coefficient b:</label>
            <input type="text" name="coeficienteB" placeholder="Enter coefficient b" required>
            <label for="coeficienteC">Coefficient c:</label>
            <input type="text" name="coeficienteC" placeholder="Enter coefficient c" required>
            <button type="button" id="resolverEcuacionSegundoGradoBtn">Solve Second Degree Equation</button>
        </form>
<hr><br>
<!-- Sistema de Dos Ecuaciones Lineales -->
<form id="sistemaEcuacionesForm">
<label for="formulaeq1">ax + by = c</label><br>
            <label for="formulaeq2">dx + ey = f</label><br><br>
            <label for="coeficienteA1">Coefficient a:</label>
            <input type="text" name="coeficienteA1" placeholder="Enter coefficient a" required>
            <label for="coeficienteB1">Coefficient b:</label>
            <input type="text" name="coeficienteB1" placeholder="Enter coefficient b" required>
            <label for="constanteC1">Constant c:</label>
            <input type="text" name="constanteC1" placeholder="Enter constant c" required>
            <br><br>
            <label for="coeficienteD2">Coefficient d:</label>
            <input type="text" name="coeficienteD2" placeholder="Enter coefficient d" required>
            <label for="coeficienteE2">Coefficient e:</label>
            <input type="text" name="coeficienteE2" placeholder="Enter coefficient e" required>
            <label for="constanteF2">Constant f:</label>
            <input type="text" name="constanteF2" placeholder="Enter constant f" required>
            <br><br>
            <button type="button" id="resolverSistemaEcuacionesBtn">Solve System of Equations</button>
        </form>
<hr><br>
    <!-- Porcentajes -->
    <form name="porcentajeForm" onsubmit="calcularPorcentaje(); return false;">
    <label for="valorInicial">Initial Value:</label>
            <input type="text" name="valorInicial" placeholder="Enter the initial value" required>
            <label for="valorFinal">Final Value:</label>
            <input type="text" name="valorFinal" placeholder="Enter the final value" required>
            <button type="submit">Calculate Percentage Change</button>
        </form>
<br><hr><br>
<!-- Regla de 3-->

<form id="reglaDeTresForm">
<label for="valor1">Value 1:</label>
            <input type="number" id="valor1" required>
            
            <label for="valor2">Value 2:</label>
            <input type="number" id="valor2" required>
            
            <label for="valor3">Value 3:</label>
            <input type="number" id="valor3" required>
            
  <button type="button" onclick="calcularReglaDeTres()">Calculate Rule Of Three</button>
</form>


<br>
</div>    
<!-- Archivos js -->

  <script src="js/calc.app.js"></script>
  <script src="../UMUZ/UMUZ/js/calc.script.js"></script>


</body>

</html>