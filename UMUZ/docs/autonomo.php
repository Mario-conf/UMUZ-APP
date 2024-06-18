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
// Manejar solicitudes POST para registrar y eliminar registros del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        $fecha = $_POST['fecha'];
        $horas = $_POST['horas'];
        $cliente = $_POST['cliente'];

        // Actualizar el archivo JSON con las horas trabajadas
        $file_path = $username . '-cuentas.json';
        $json_data = json_decode(file_get_contents($file_path), true);
        $json_data[] = array(
            'fecha' => $fecha,
            'horas' => $horas,
            'cliente' => $cliente
        );
        file_put_contents($file_path, json_encode($json_data));
    } elseif (isset($_POST['delete_submit']) && isset($_POST['delete_key'])) {
        $delete_key = $_POST['delete_key'];
        if (file_exists($username . '-cuentas.json')) {
            $json_data = json_decode(file_get_contents($username . '-cuentas.json'), true);
            if (isset($json_data[$delete_key])) {
                unset($json_data[$delete_key]);
                file_put_contents($username . '-cuentas.json', json_encode($json_data));
            }
        }
    } elseif (isset($_POST['precio_submit'])) {
        // Establecer el precio por hora
        $precio_por_hora = $_POST['precio_por_hora'];
        // Actualizar el precio por hora en un archivo separado

        file_put_contents($username . '-precio_por_hora.txt', $precio_por_hora);
    }
}
if (!file_exists($username . '-precio_por_hora.txt')) {
  file_put_contents($username . '-precio_por_hora.txt','');
}
$precio_por_hora = file_get_contents($username . '-precio_por_hora.txt');

// Obtener datos de todos los clientes
$clientes = [];
if (file_exists($username . '-cuentas.json')) {
    $json_data = json_decode(file_get_contents($username . '-cuentas.json'), true);
    foreach ($json_data as $registro) {
        $clientes[$registro['cliente']] = $registro['cliente'];
    }
}

// Función para filtrar los registros por cliente y mes
function filtrarRegistros($registros, $cliente, $mes) {
    $registros_filtrados = [];
    foreach ($registros as $registro) {
        $fecha = date_create_from_format('Y-m-d', $registro['fecha']);
        if ($fecha) {
            $mes_registro = date_format($fecha, 'Y-m');
            if (($cliente == '' || $registro['cliente'] == $cliente) && ($mes == '' || $mes_registro == $mes)) {
                $registros_filtrados[] = $registro;
            }
        }
    }
    return $registros_filtrados;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMUZ - Profitime</title>
    <link rel="stylesheet" href="../css/docs.css">
</head>
<body>

<div class="container">
        <a href="../web.php" class="btn">Go to Web Panel</a>
    <h2>Work Hours Registration and Client Management</h2>

    <form method="post" autocomplete="off">
        <div class="form-group">
            <label for="precio_por_hora">Price per hour:</label>
            <input type="number" id="precio_por_hora" name="precio_por_hora" value="<?php echo $precio_por_hora ?>" min="0" step="0.01" required>
        </div>
        <button type="submit" name="precio_submit">Save price</button>
    </form>

    <form method="post" autocomplete="off">
        <div class="form-group">
            <label for="fecha">Date:</label>
            <input type="date" id="fecha" name="fecha" required>
        </div>

        <div class="form-group">
            <label for="horas">Worked hours:</label>
            <input type="number" id="horas" name="horas" min="1" required>
        </div>

        <div class="form-group">
            <label for="cliente">Client:</label>
            <input type="text" id="cliente" name="cliente" required>
        </div>

        <button type="submit" name="submit">Register</button>
    </form>

    <div class="search-form">
    <h2>Search by client and/or month</h2>
    <form method="post" autocomplete="off">
        <div class="form-group">
            <label for="cliente">Client:</label>
            <select name="cliente" id="cliente">
                <option value="">Select a client</option>
                <?php foreach ($clientes as $cliente) : ?>
                    <option value="<?php echo $cliente; ?>"><?php echo $cliente; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="mes">Month:</label>
            <input type="month" id="mes" name="mes">
        </div>
        <button type="submit" name="search_submit">Search</button>
    </form>

</div>


<div class="filtered-records">
    <?php
    // Validar y asignar variables de entrada
    $cliente_seleccionado = isset($_POST['cliente']) ? $_POST['cliente'] : '';
    $mes_seleccionado = isset($_POST['mes']) ? $_POST['mes'] : '';

    // Comprobar si al menos una de las variables no está vacía
    if (!empty($cliente_seleccionado) || !empty($mes_seleccionado)) {
        // Filtrar los registros
        $registros_filtrados = filtrarRegistros($json_data, $cliente_seleccionado, $mes_seleccionado);

        // Verificar si hay registros filtrados
        if (!empty($registros_filtrados)) {
            $total_cobrado = 0;
    ?>
            <h2>Records filtered by <?php echo !empty($cliente_seleccionado) ? 'client: ' . htmlspecialchars($cliente_seleccionado) : ''; ?> <?php echo !empty($cliente_seleccionado) && !empty($mes_seleccionado) ? 'and ' : ''; ?> <?php echo !empty($mes_seleccionado) ? 'month: ' . htmlspecialchars($mes_seleccionado) : ''; ?></h2>
            <table border='1'>
                <tr>
                    <th>Date</th>
                    <th>Worked Hours</th>
                    <th>Client</th>
                    <th>Amount to Charge</th>
                </tr>
                <?php
                // Iterar sobre los registros filtrados y mostrarlos en la tabla
                foreach ($registros_filtrados as $registro) {
                    $monto_a_cobrar = $registro['horas'] * $precio_por_hora;
                    $total_cobrado += $monto_a_cobrar;
                    echo "<tr>
                            <td>" . htmlspecialchars($registro['fecha']) . "</td>
                            <td>" . htmlspecialchars($registro['horas']) . "</td>
                            <td>" . htmlspecialchars($registro['cliente']) . "</td>
                            <td>" . htmlspecialchars($monto_a_cobrar) . "</td>
                          </tr>";
                }
                ?>
            </table>
            <p>Total Amount Charged: <?php echo htmlspecialchars($total_cobrado); ?></p>
    <?php
        } else {
            // Mensaje en caso de que no se encuentren registros
            echo "<p>No records found for the selected criteria.</p>";
        }
    } else {
        // Mensaje en caso de que no se haya seleccionado cliente o mes
        echo "<p>Please select a client and/or a month.</p>";
    }
    ?>
</div>

    <h2>Worked Hours Records</h2>

    <canvas id="lineChart"></canvas>
<br>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php
if (!file_exists($username . '-cuentas.json')) {
        file_put_contents($username . '-cuentas.json','[]');
    }

// Obtener datos de los registros desde el archivo JSON
$json_data = file_get_contents($username . '-cuentas.json');
$registros = json_decode($json_data, true);

// Crear un array para almacenar los datos en formato JSON
$datos_js = array();

// Iterar sobre los registros y calcular $monto_a_cobrar para cada uno
foreach ($registros as $registro) {
    $monto_a_cobrar = $registro['horas'] * $precio_por_hora;
    // Agregar los datos al array en formato JSON
    $datos_js[] = array(
        'fecha' => $registro['fecha'],
        'horas' => $registro['horas'],
        'monto_a_cobrar' => $monto_a_cobrar
    );
}
?>

<script>
// Obtener datos de los registros desde PHP
var registros = <?php echo json_encode($datos_js); ?>;
// Función para agrupar los registros por mes
function agruparRegistrosPorMes(registros) {
    var agrupados = {};
    // Crear un objeto de fecha para el primer día de este año
    var fechaInicio = new Date(new Date().getFullYear(), 0, 1);
    // Nombres de los meses
    var mesesNombres = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    // Iterar sobre cada mes del año actual
    for (var i = 0; i < 12; i++) {
        // Obtener el mes y el año para esta iteración
        var mes = mesesNombres[fechaInicio.getMonth()];
        // Inicializar los datos para este mes
        agrupados[mes] = { horas: 0, ingresos: 0 };
        // Avanzar al siguiente mes
        fechaInicio.setMonth(fechaInicio.getMonth() + 1);
    }
    // Iterar sobre los registros y sumar los datos a los meses correspondientes
    registros.forEach(function(registro) {
        var fecha = new Date(registro.fecha);
        var mes = mesesNombres[fecha.getMonth()];
        agrupados[mes].horas += parseInt(registro.horas);
        // Suma los ingresos totales para este mes
        agrupados[mes].ingresos += parseFloat(registro.monto_a_cobrar);
    });
    return agrupados;
}


// Agrupar registros por mes
var registrosAgrupados = agruparRegistrosPorMes(registros);

// Preparar datos para la gráfica
var meses = Object.keys(registrosAgrupados);
var horasTrabajadas = meses.map(function(mes) { return registrosAgrupados[mes].horas; });
var ingresos = meses.map(function(mes) { return registrosAgrupados[mes].ingresos; });
var añoActual = new Date().getFullYear();
// Luego puedes usar la variable añoActual en tu código según sea necesario
var ctx = document.getElementById('lineChart').getContext('2d');
var lineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: meses,
        datasets: [
        {
            label: 'Total Worked Hours',
            data: horasTrabajadas,
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 2,
            fill: false
        },
        {
            label: 'Total Income',
            data: ingresos,
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            fill: false
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            },

        },
        plugins: {
            title: {
                display: true,
                text: añoActual
            }
        }
    }
});
</script>





<table border="1">
    <tr>
        <th>Date</th>
        <th>Worked Hours</th>
        <th>Client</th>
        <th>Amount to Charge</th>
        <th>Actions</th>
    </tr>
    <?php if (file_exists($username . '-cuentas.json')) :
        $json_data = json_decode(file_get_contents($username . '-cuentas.json'), true);
        if (!empty($json_data)) :
            foreach ($json_data as $key => $registro) :
                $monto_a_cobrar = $registro['horas'] * $precio_por_hora;
    ?>
                <tr>
                    <td><?php echo $registro['fecha']; ?></td>
                    <td><?php echo $registro['horas']; ?></td>
                    <td><?php echo $registro['cliente']; ?></td>
                    <td><?php echo $monto_a_cobrar; ?></td>
                    <td>
                        <form method='post' autocomplete="off">
                            <input type='hidden' name='delete_key' value='<?php echo $key; ?>'>
                            <button type='submit' name='delete_submit'>Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach;
        else : ?>
            <tr>
                <td colspan='5'>No records</td>
            </tr>
        <?php endif;
    else : ?>
        <tr>
            <td colspan='5'>No records</td>
        </tr>
    <?php endif; ?>
</table>


</div>

</body>
</html>
