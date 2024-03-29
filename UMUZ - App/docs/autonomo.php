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
        $file_path = 'cuentas.json';
        $json_data = json_decode(file_get_contents($file_path), true);
        $json_data[] = array(
            'fecha' => $fecha,
            'horas' => $horas,
            'cliente' => $cliente
        );
        file_put_contents($file_path, json_encode($json_data));
    } elseif (isset($_POST['delete_submit']) && isset($_POST['delete_key'])) {
        $delete_key = $_POST['delete_key'];
        if (file_exists('cuentas.json')) {
            $json_data = json_decode(file_get_contents('cuentas.json'), true);
            if (isset($json_data[$delete_key])) {
                unset($json_data[$delete_key]);
                file_put_contents('cuentas.json', json_encode($json_data));
            }
        }
    } elseif (isset($_POST['precio_submit'])) {
        // Establecer el precio por hora
        $precio_por_hora = $_POST['precio_por_hora'];

        // Actualizar el precio por hora en un archivo separado
        file_put_contents('precio_por_hora.txt', $precio_por_hora);
    }
}
$precio_por_hora = file_get_contents('precio_por_hora.txt');

// Obtener datos de todos los clientes
$clientes = [];
if (file_exists('cuentas.json')) {
    $json_data = json_decode(file_get_contents('cuentas.json'), true);
    foreach ($json_data as $registro) {
        $clientes[$registro['cliente']] = $registro['cliente'];
    }
}

// Función para filtrar los registros por cliente y mes
function filtrarRegistros($registros, $cliente, $mes) {
    $registros_filtrados = [];
    foreach ($registros as $registro) {
        
        $fecha = date_create_from_format('Y-m-d', $registro['fecha']);
        $mes_registro = date_format($fecha, 'Y-m');
        
        if ($registro['cliente'] == $cliente && $mes_registro == $mes) {
            $registros_filtrados[] = $registro;
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
        <h2>Search by client and month</h2>
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
        $cliente_seleccionado = $_POST['cliente'] ?? '';
        $mes_seleccionado = $_POST['mes'] ?? '';
        if (!empty($cliente_seleccionado) && !empty($mes_seleccionado)) {
            $registros_filtrados = filterRecords($json_data, $cliente_seleccionado, $mes_seleccionado);
            if (!empty($registros_filtrados)) {
                $total_cobrado = 0;
        ?>
                <h2>Records filtered by client: <?php echo $cliente_seleccionado; ?> and month: <?php echo $mes_seleccionado; ?></h2>
                <table border='1'>
                    <tr>
                        <th>Date</th>
                        <th>Worked Hours</th>
                        <th>Client</th>
                        <th>Amount to Charge</th>
                    </tr>
                    <?php foreach ($registros_filtrados as $registro) : ?>
                        <?php
                        $monto_a_cobrar = $registro['horas'] * $precio_por_hora;
                        $total_cobrado += $monto_a_cobrar;
                        ?>
                        <tr>
                            <td><?php echo $registro['fecha']; ?></td>
                            <td><?php echo $registro['horas']; ?></td>
                            <td><?php echo $registro['cliente']; ?></td>
                            <td><?php echo $monto_a_cobrar; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan='3'>Total charged:</td>
                        <td><?php echo $total_cobrado; ?></td>
                    </tr>
                </table>
            <?php } else { ?>
                <p>No records for client: <?php echo $cliente_seleccionado; ?> in month: <?php echo $mes_seleccionado; ?></p>
        <?php }
        } else { ?>
            <p>Please select a client and a month to perform the search.</p>
        <?php } ?>
    </div>

    
    <h2>Worked Hours Records</h2>
    <table border="1">
    <tr>
        <th>Date</th>
        <th>Worked Hours</th>
        <th>Client</th>
        <th>Amount to Charge</th>
        <th>Actions</th>
    </tr>
    <?php if (file_exists('cuentas.json')) :
        $json_data = json_decode(file_get_contents('cuentas.json'), true);
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

<footer>
  <p>Developed by</p><a href="https://github.com/Mario-conf" target="_blank"> Mario.conf</a>
</footer>

</body>
</html>
