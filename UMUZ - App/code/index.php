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

// Definir tasas de cambio manualmente 
$exchangeRates = [
    'USD' => ['EUR' => 0.85, 'GBP' => 0.72, 'JPY' => 109.84, 'CNY' => 6.37, 'CHF' => 0.92],
    'EUR' => ['USD' => 1.18, 'GBP' => 0.85, 'JPY' => 130.18, 'CNY' => 7.58, 'CHF' => 1.09],
    'GBP' => ['USD' => 1.39, 'EUR' => 1.18, 'JPY' => 152.52, 'CNY' => 8.89, 'CHF' => 1.28],
    'JPY' => ['USD' => 0.0091, 'EUR' => 0.0077, 'GBP' => 0.0066, 'CNY' => 0.058, 'CHF' => 0.0084],
    'CNY' => ['USD' => 0.16, 'EUR' => 0.13, 'GBP' => 0.11, 'JPY' => 17.22, 'CHF' => 0.14],
    'CHF' => ['USD' => 1.09, 'EUR' => 0.92, 'GBP' => 0.78, 'JPY' => 118.68, 'CNY' => 7.16]
];

// Función para convertir una cantidad de una moneda a otra
function convertCurrency($amount, $fromCurrency, $toCurrency) {
    global $exchangeRates;

    // Verificar si las monedas son válidas
    if (isset($exchangeRates[$fromCurrency]) && isset($exchangeRates[$fromCurrency][$toCurrency])) {
        // Si las monedas son las mismas, devolver la cantidad original
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }
        // Calcular la conversión
        $conversion = $amount * $exchangeRates[$fromCurrency][$toCurrency];
        return $conversion;
    } else {
        return null; // Monedas no válidas
    }
}

// Variables del formulario
$amount = null;
$fromCurrency = null;
$toCurrency = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : null;
    $fromCurrency = isset($_POST['fromCurrency']) ? $_POST['fromCurrency'] : null;
    $toCurrency = isset($_POST['toCurrency']) ? $_POST['toCurrency'] : null;

    // Verificar si se han proporcionado todas las entradas requeridas
    if ($amount !== null && $fromCurrency !== null && $toCurrency !== null) {
        // Si las monedas son las mismas, mostrar el valor original
        if ($fromCurrency === $toCurrency) {
            $resultMessage = "{$amount} {$fromCurrency}";
        } else {
            // Realizar la conversión de divisas
            $conversion = convertCurrency($amount, $fromCurrency, $toCurrency);
            if ($conversion !== null) {
                $resultMessage = "{$amount} {$fromCurrency} is equal to {$conversion} {$toCurrency}";
            } else {
                $resultMessage = "Invalid currencies.";
            }
        }
        // Mostrar el resultado en un alert
        echo "<script>alert('$resultMessage');</script>";
    } else {
        $resultMessage = "Please fill in all fields.";
    }
} else {
    // Inicializar la variable resultMessage
    $resultMessage = "";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMUZ - Exchange</title>
    <link rel="stylesheet" href="../UMUZ - App/css/code.css">
    <style>

:root {
    --x-color: #ca43ff;
    --y-color: #450074;
    --z-color: rgb(218, 218, 218);
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #cccccc;
    margin: 20px 0;
    color: #000;
    padding: 20px;
    height: auto;
    margin-top: 200px;
    min-height: 100vh;
    overflow-x: hidden;
}

header {
    background-color: var(--z-color);
    color: #ffffff;
    text-align: center;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    background-color: #383838;
    padding: 1em 0;
}

h1 {
    color: #e4e4e4;
}
/* Estilos de los enlaces */
a {
    color: var(--x-color); 
    text-decoration: none;
}
a:hover {
    text-decoration: underline; 
}
.btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: var(--x-color);
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s ease; 
    margin-bottom: 20px;
}

.btn:hover {
    background-color: #7a00cc;
    text-decoration: none;
}
/* Estilos formulario */
form {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: rgba(167, 167, 167, 0.8);
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    color: #000;
}

form label {
    display: block;
    margin-top: 10px;
}

form input[type="number"],
form select {
    width: 300px;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    outline: none;
    color: #000;
}

form input[type="submit"] {
    margin-top: 10px;
    width: 100%;
    padding: 10px;
    font-size: 16px;
    background-color: var(--x-color);
    color: #ffffff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

form input[type="submit"]:hover {
    background-color: #7a00cc;
    color: #ffffff;
}

/* Estilos del footer */
footer {
    width: 100%;
    background-color: #333;
    color: #bdbdbd;
    text-align: center;
    padding: 10px;
    position: fixed;
    bottom: 0;
    left: 0;
}
footer a {
    color: #bdbdbd;
    text-decoration: none;
    font-weight: bold;
}
footer a:hover{
    color: #ffffff;
    text-decoration: none;
}

    </style>
   <header>
        <h1>UMUZ - Exchange</h1>
        <a href="../web.php" class="btn">Go to Web Panel</a>
</header>
<body>
    <form action="" method="POST">
        <label for="amount">Amount:</label><br>
        <input type="number" id="amount" name="amount" step="0.01" required><br>
        <label for="fromCurrency">From:</label><br>
        <select id="fromCurrency" name="fromCurrency" required>
            <option value="USD">United States Dollar (USD)</option>
            <option value="EUR">Euro (EUR)</option>
            <option value="GBP">British Pound Sterling (GBP)</option>
            <option value="JPY">Japanese Yen (JPY)</option>
            <option value="CNY">Chinese Yuan (CNY)</option>
            <option value="CHF">Swiss Franc (CHF)</option>
        </select><br>
        <label for="toCurrency">To:</label><br>
        <select id="toCurrency" name="toCurrency" required>
            <option value="USD">United States Dollar (USD)</option>
            <option value="EUR">Euro (EUR)</option>
            <option value="GBP">British Pound Sterling (GBP)</option>
            <option value="JPY">Japanese Yen (JPY)</option>
            <option value="CNY">Chinese Yuan (CNY)</option>
            <option value="CHF">Swiss Franc (CHF)</option>
        </select><br>
        <input type="submit" value="Convert">
    </form>
    <footer>
        <p>Developed by</p><a href="https://github.com/Mario-conf" target="_blank"> Mario.conf</a>
    </footer>
</body>
</html>
