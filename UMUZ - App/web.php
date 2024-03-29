<?php
// Verificar si la sesión no está iniciada o no
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado o no
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirigir al usuario a la página de inicio de sesión  , si falla la autenticacion
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
    <title>UMUZ - App</title>
    <link rel="stylesheet" href="../UMUZ - App/css/styles.css">
</head>
<div class="container">
  <header class="d-flex" style="background-color: #fff;">
    <div class="col-md-3">
    <h1>UMUZ</h1>
    </div>
    <div class="nav">  
    <a href="#banner" class="nav-link px-3">Home</a>
<a href="#calendariodiv" class="nav-link px-3">Calendar</a>
<a href="#Tools" class="nav-link px-3">Tools</a>
</div>
<div class="d-flex">
  <p><a  class="btn btn-primary" href="login.php">Log-out</a></p>
</div>
  </header>

</div>
    <section class="banner">
        <div>
        <h1 style="text-transform: uppercase;">Welcome Back, <?php echo $_SESSION['username']; ?></h1>
            <p>Universal Management Utilities Zone</p>
        </div>
    </section>

    <div class="clock-container">
  <div class="clock-day"></div>
  <div class="clock-hour"></div>
  <div class="clock-separator">:</div>
  <div class="clock-minute"></div>
  <div class="clock-separator">:</div>
  <div class="clock-second"></div>
</div>

<div class="calendario" id="calendariodiv">
    <div id="calendar">
    <?php include 'calendar.php';
    // muestra el contenido del calendario
    ?>
</div>
<div id="events">
    <h2>User Events</h2>
    <table>
        <thead>
            <tr>
                <th>Event</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
// Leer los datos del archivo y decodificar el JSON
$json_data = file_get_contents($user_events_file);
if ($json_data !== false) {
    // Intentar decodificar el JSON
    $user_events = json_decode($json_data, true);
    if ($user_events === null) {
        // Manejar el error de decodificación del JSON
        echo "";
} else {
    // Manejar el error de lectura del archivo JSON
    echo "";
  }
}  
// Mostrar los eventos del usuario en la tabla
foreach ($user_events as $key => $event) {
  echo '<tr>';
  // Mostrar el nombre del evento
  $title = $event['title']; 
  echo '<td>' . $title . '</td>'; 
  // Mostrar la fecha del evento
  echo '<td>' . $event['date'] . '</td>';
  // Botón para borrar el evento
  echo '<td><button onclick="deleteEvent(' . $key . ')">Borrar</button></td>';
  echo '</tr>';
}
?>
        </tbody>
    </table>
</div>
<div class="calendario-form">
<form id="eventForm" method="post" action="calendar.php">
    <p>New Event</p>
    <label for="eventTitle">Event Title</label>
    <input type="text" id="eventTitle" name="eventTitle" required><br><br>
    <label for="eventDate">Event Date</label>
    <input type="date" id="eventDate" name="eventDate" required><br><br>
    <button type="submit">Add Event</button>
</form>
</div>
</div>
<script>
    // Función para borrar un evento
    function deleteEvent(index) {
        if (confirm('¿Estás seguro de que quieres borrar este evento?')) {
            // Crear un formulario para enviar la orden con el índice del evento a eliminar
            var form = document.createElement('form');
            form.method = 'post';
            form.action = 'calendar.php'; 
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'deleteIndex';
            input.value = index;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
<script>
    // Función para agregar un evento al lista
    function addEvent() {
        // Obtener los datos del formulario
        var title = document.getElementById('eventTitle').value;
        var date = document.getElementById('eventDate').value;

        // Crear un objeto FormData para enviar los datos al servidor
        var formData = new FormData();
        formData.append('eventTitle', title);
        formData.append('eventDate', date);

        // Enviar los datos del formulario mediante una solicitud AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'calendar.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Actualizar la lista de eventos después de agregar el evento
                loadEvents();
            } else {
                console.log('Error al agregar el evento');
            }
        };
        xhr.send(formData);
    }

// Cargar los eventos después de agregar uno nuevo
function loadEvents() {
    // Realizar una solicitud AJAX para obtener los eventos actualizados del servidor
    fetch('get_events.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener los eventos');
            }
            return response.json();
        })
        .then(data => {
            // Una vez que se obtengan los eventos actualizados, actualiza la parte de la página donde se muestran los eventos
            updateEventList(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Actualizar la lista de eventos en la página
function updateEventList(events) {
    // Referenciar donde se mostrarán los eventos
    const eventsList = document.getElementById('events');

    // Limpiar la lista de eventos
    eventsList.innerHTML = '';

    // Recorrer la lista de eventos y agregar cada uno a la lista en la página
    events.forEach(event => {
        const eventItem = document.createElement('li');
        eventItem.textContent = event.title + ' - ' + event.date;
        eventsList.appendChild(eventItem);
    });
}
</script>
<section id="Tools">
  <div id="card" class="card">
  <img src="../UMUZ - App/css/bg.png" alt="Imagen">
    <div class="card-content">
      <h3>Notes</h3>
      <button class="btn-enter" data-link="https://example.com">Enter</button>
    </div>
  </div>

  <div id="card" class="card">
  <img src="../UMUZ - App/css/bg.png" alt="Imagen">
    <div class="card-content">
      <h3>Charts </h3>
      <button class="btn-enter" data-link="https://example.com">Enter</button>
    </div>
  </div>

  <div id="card" class="card">
  <img src="../UMUZ - App/css/bg.png" alt="Imagen">
    <div class="card-content">
      <h3>Weather</h3>
      <button class="btn-enter" data-link="https://example.com">Enter</button>
    </div>
  </div>

  <div id="card" class="card">
  <img src="../UMUZ - App/css/beg.png" alt="Imagen">
    <div class="card-content">
      <h3>Calculator</h3>
      <button class="btn-enter" data-link="../UMUZ - App/calc/index.php">Enter</button>
    </div>
  </div>

  <div id="card" class="card">
  <img src="../UMUZ - App/css/beg.png" alt="Imagen">
    <div class="card-content">
      <h3>Whiteboard</h3>
      <button class="btn-enter" data-link="../UMUZ - App/board/index.php">Enter</button>
    </div>
  </div>

  <div id="card" class="card">
  <img src="../UMUZ - App/css/beg.png" alt="Imagen">
    <div class="card-content">
      <h3>Curriculum</h3>
      <button class="btn-enter" data-link="../UMUZ - App/cv/index.php">Enter</button>
    </div>
  </div>

  <div id="card" class="card">
  <img src="../UMUZ - App/css/beg.png" alt="Imagen">
    <div class="card-content">
      <h3>Bookkeeper</h3>
      <button class="btn-enter" data-link="../UMUZ - App/docs/autonomo.php">Enter</button>
    </div>
  </div>

  <div id="card" class="card">
  <img src="../UMUZ - App/css/beg.png" alt="Imagen">
    <div class="card-content">
      <h3>Exchange</h3>
      <button class="btn-enter" data-link="../UMUZ - App/code/index.php">Enter</button>
    </div>
  </div>
<!--
  <div id="card" class="card">
  <img src="../UMUZ - App/css/bg.png" alt="Imagen">
    <div class="card-content">
      <h3>Code/h3>
      <button class="btn-enter" data-link="Ejemplo para escalabilidad futura de la App">Enter</button>
    </div>
  </div>
-->
</section>

  <script src="../UMUZ - App/js/index.js"></script>  

<footer>
  <p>Developed by</p><a href="https://github.com/Mario-conf" target="_blank"> Mario.conf</a>
</footer>

</body>
</html>