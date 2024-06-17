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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../UMUZ/css/cv.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <title>UMUZ - Quick-CV</title>
</head>
<body>
    <header>
        <h1>UMUZ - Quick-CV</h1>
        
        <a href="../UMUZ/web.php" class="btn">Go to Web Panel</a>
        <!--    <p id="instrucciones">Complete the form below:</p>    -->
      
    </header>

    <main>
      <form id="formularioDatosPersonales" class="cv-form">
        <h2>Personal Information</h2>
    
        <div class="form-group">
            <label for="nombre">Name:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
    
        <div class="form-group">
            <label for="apellidos">Surname:</label>
            <input type="text" id="apellidos" name="apellidos" required>
        </div>
    
        <div class="form-group">
            <label for="direccion">Address:</label>
            <input type="text" id="direccion" name="direccion" required>
        </div>
    
        <div class="form-group">
            <label for="telefono">Phone:</label>
            <input type="tel" id="telefono" name="telefono" required>
        </div>
    
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
    
        <div class="form-group">
            <label for="fechaNacimiento">Date of Birth:</label>
            <input type="date" id="fechaNacimiento" name="fechaNacimiento" required>
        </div>
    
        <div class="form-group">
            <label for="nacionalidad">Nationality:</label>
            <input type="text" id="nacionalidad" name="nacionalidad" required>
        </div>
    
        <div class="form-group">
            <label for="permisoConducir">Driving License:</label>
            <select id="permisoConducir" name="permisoConducir" required>
                <option value="si">Yes</option>
                <option value="no">No</option>
            </select>
        </div>
    </form>
    

        <form id="formularioDescripcionPersonal" class="cv-form">
            <h2>Personal Description</h2>
            <div class="form-group">
                <label for="descripcionPersonal">Personal Description:</label>
                <textarea id="descripcionPersonal" name="descripcionPersonal" rows="4" required></textarea>
            </div>
        </form>

        <form id="formularioExperiencia" class="cv-form">
            <h2>Work Experience</h2>
            <!-- Fields for work experience -->
            <div class="form-group">
                <label for="experiencia">Experience:</label>
                <textarea id="experiencia" name="experiencia" rows="4" required></textarea>
            </div>
            
        </form>

        <form id="formularioEducacion" class="cv-form">
            <h2>Education</h2>
            <!-- Education fields -->
            <div class="form-group">
                <label for="educacion">Education:</label>
                <textarea id="educacion" name="educacion" rows="4" required></textarea>
            </div>
            
        </form>

        <form id="formularioIdiomas" class="cv-form">
            <h2>Languages</h2>
            <!-- Language fields -->
            <div class="form-group">
                <label for="idiomas">Languages:</label>
                <textarea id="idiomas" name="idiomas" rows="4" required></textarea>
            </div>
           
        </form>

        <form id="formularioActitudes" class="cv-form">
            <h2>Attitudes</h2>
            <div class="form-group">
                <label for="actitudes">Attitudes:</label>
                <textarea id="actitudes" name="actitudes" rows="4" required></textarea>
            </div>
        </form>

        <form id="formularioAptitudes" class="cv-form">
            <h2>Skills</h2>
            <div class="form-group">
                <label for="aptitudes">Skills:</label>
                <textarea id="aptitudes" name="aptitudes" rows="4" required></textarea>
            </div>
        </form>

        <form id="formularioHabilidadesComplementarias" class="cv-form">
            <h2>Additional Skills</h2>
            <div class="form-group">
                <label for="habilidadesComplementarias">Additional Skills:</label>
                <textarea id="habilidadesComplementarias" name="habilidadesComplementarias" rows="4" required></textarea>
            </div>
        </form>

        <div id="visualizacionCV">
            <h2>Generated CV</h2>
            
        </div>
    </main>

  <div class="footer">
        <button id="generarBtn">Generate CV</button>
        <button id="descargarBtn">Download CV</button>
        </div>  
        
<footer>
  <p>Developed by</p><a href="https://github.com/Mario-conf" target="_blank"> Mario.conf</a>
</footer>

    <script src="../UMUZ/UMUZ/js/cv.js"></script>
</body>
</html>
