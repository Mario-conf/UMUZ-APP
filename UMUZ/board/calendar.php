<?php
// Verificar si la sesión del usuario no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado o no
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401); // No autorizado
    exit;
}

// Verificar si se va a agregar un nuevo evento al calendario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eventTitle']) && isset($_POST['eventDate'])) {
    // Obtener el nombre de usuario del usuario actual
    $username = $_SESSION['username'];
    $user_events_file = $username . '-events.json';
    
    // Obtener y procesar los datos del formulario 
    $event_data = array(
        'title' => $_POST['eventTitle'], 
        'date' => $_POST['eventDate']
    );

    // Verificar si existe el archivo de eventos del usuario
    $user_events = array();
    if (file_exists($user_events_file)) {
        // Leer los datos del archivo 
        $user_events = json_decode(file_get_contents($user_events_file), true);
    }
    // Agregar el nuevo evento a los eventos del usuario
    $user_events[] = $event_data;

    // Convertir los datos a formato JSON y guardar en el archivo correspondiente
    file_put_contents($user_events_file, json_encode($user_events));

    // Redirigir de vuelta a la página principal de la App
    header("Location: web.php");
    exit;
}

// Verificar si se va a eliminar un evento ya creado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteIndex'])) {
    // Obtener el índice del evento a borrar
    $deleteIndex = $_POST['deleteIndex'];

    // Obtener el nombre de usuario del usuario actual
    $username = $_SESSION['username'];
    $user_events_file = $username . '-events.json';

    // Verificar si existe el archivo de eventos del usuario
    if (file_exists($user_events_file)) {
        // Leer los eventos del usuario desde el archivo
        $user_events = json_decode(file_get_contents($user_events_file), true);

        // Verificar si el índice del evento a borrar es válido o no
        if (array_key_exists($deleteIndex, $user_events)) {
            // Borrar el evento del array de eventos del usuario actual
            unset($user_events[$deleteIndex]);

            // Guardar los eventos actualizados en el archivo json
            file_put_contents($user_events_file, json_encode($user_events));
        }
    }

    // Redirigir de vuelta a la página principal de la App
    header("Location: web.php");
    exit;
}
// logica para el calendario

// Obtener el mes y año actuales
$month = date('n');
$year = date('Y');

// Obtener el número de días en el mes actual
$num_days = date('t', strtotime(date('Y-m')));

// Obtener el primer día de la semana para el mes y año actuales
$first_day = date('N', mktime(0, 0, 0, $month, 1, $year));

// Mostrar la estrucctura del calendario HTML
$calendar_html = '<table>';
$calendar_html .= '<caption>' . date('F Y', mktime(0, 0, 0, $month, 1, $year)) . '</caption>'; // Mes y año
$calendar_html .= '<tr><th>Lun</th><th>Mar</th><th>Mie</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th></tr>';

// Contador para los días del mes
$day_count = 1;

// Iniciar la fila del calendario
$calendar_html .= '<tr>';

// Rellenar los espacios en blanco hasta el primer día del mes
for ($i = 1; $i < $first_day; $i++) {
    $calendar_html .= '<td></td>';
}

// Imprimir los días del mes
while ($day_count <= $num_days) {
    // Si el día actual es domingo (7), cerrar la fila y abrir una nueva
    if ($first_day > 7) {
        $calendar_html .= '</tr><tr>';
        $first_day = 1;
    }

    // Mostrar el día actual en la celda
    $calendar_html .= '<td>' . $day_count . '</td>';

    // Incrementar el contador de días y el primer día de la semana
    $day_count++;
    $first_day++;
}

// Rellenar los espacios en blanco al final del último día del mes
while ($first_day <= 7) {
    $calendar_html .= '<td></td>';
    $first_day++;
}

// Cerrar la última fila y la tabla del calendario
$calendar_html .= '</tr>';
$calendar_html .= '</table>';

// Mostrar el calendario generado via PHP
echo $calendar_html;
?>
