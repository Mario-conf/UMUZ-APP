document.addEventListener('DOMContentLoaded', function () {
    const generarBtn = document.getElementById('generarBtn');
    const descargarBtn = document.getElementById('descargarBtn');
    const instrucciones = document.getElementById('instrucciones');
  
    generarBtn.addEventListener('click', function () {
        const datosPersonalesForm = document.getElementById('formularioDatosPersonales');
        const descripcionPersonalForm = document.getElementById('formularioDescripcionPersonal');
        const experienciaForm = document.getElementById('formularioExperiencia');
        const educacionForm = document.getElementById('formularioEducacion');
        const idiomasForm = document.getElementById('formularioIdiomas');
        const actitudesForm = document.getElementById('formularioActitudes');
        const aptitudesForm = document.getElementById('formularioAptitudes');
        const habilidadesComplementariasForm = document.getElementById('formularioHabilidadesComplementarias');
  
        // Obtener datos de los formularios
        const datosPersonales = obtenerDatosFormulario(datosPersonalesForm);
        const descripcionPersonal = obtenerDatosFormulario(descripcionPersonalForm);
        const experiencia = obtenerDatosFormulario(experienciaForm);
        const educacion = obtenerDatosFormulario(educacionForm);
        const idiomas = obtenerDatosFormulario(idiomasForm);
        const actitudes = obtenerDatosFormulario(actitudesForm);
        const aptitudes = obtenerDatosFormulario(aptitudesForm);
        const habilidadesComplementarias = obtenerDatosFormulario(habilidadesComplementariasForm);
  
        // Unir todos los datos
        const datosCV = {
            datosPersonales,
            descripcionPersonal,
            experiencia,
            educacion,
            idiomas,
            actitudes,
            aptitudes,
            habilidadesComplementarias,
        };
  
        // Mostrar los datos en la visualización del CV
        mostrarDatosEnCV(datosCV);
  
        // Actualizar instrucciones
        instrucciones.textContent = '¡Tu CV está listo! Puedes descargarlo a continuación:';
    });
  
    descargarBtn.addEventListener('click', function () {
        // Generar y descargar el CV en formato de texto
        generarYDescargarCVTexto();
  
        // Mostrar mensaje de descarga
        instrucciones.textContent = '¡Descarga completada! Siéntete libre de generar otro CV.';
    });
  
    function obtenerDatosFormulario(formulario) {
        const elementos = formulario.elements;
        const datos = {};
  
        for (let i = 0; i < elementos.length; i++) {
            if (elementos[i].type !== 'button') {
                datos[elementos[i].name] = elementos[i].value;
            }
        }
  
        return datos;
    }
  
    function mostrarDatosEnCV(datos) {
        const visualizacionCV = document.getElementById('visualizacionCV');
        visualizacionCV.innerHTML = '';
  
        // Construcción de HTML con los datos
        visualizacionCV.innerHTML += `<h3>Datos Personales</h3>`;
        visualizacionCV.innerHTML += `<p>Nombre: ${datos.datosPersonales.nombre}</p>`;
        visualizacionCV.innerHTML += `<p>Apellidos: ${datos.datosPersonales.apellidos}</p>`;
        visualizacionCV.innerHTML += `<p>Dirección: ${datos.datosPersonales.direccion}</p>`;
        visualizacionCV.innerHTML += `<p>Teléfono: ${datos.datosPersonales.telefono}</p>`;
        visualizacionCV.innerHTML += `<p>Email: ${datos.datosPersonales.email}</p>`;
        visualizacionCV.innerHTML += `<p>Fecha de Nacimiento: ${datos.datosPersonales.fechaNacimiento}</p>`;
        visualizacionCV.innerHTML += `<p>Nacionalidad: ${datos.datosPersonales.nacionalidad}</p>`;
        visualizacionCV.innerHTML += `<p>Permiso de Conducir: ${datos.datosPersonales.permisoConducir}</p>`;
        visualizacionCV.innerHTML += `<h3>Descripción Personal</h3>`;
        visualizacionCV.innerHTML += `<p>${datos.descripcionPersonal.descripcionPersonal}</p>`;
  
        visualizacionCV.innerHTML += `<h3>Experiencia Laboral</h3>`;
        visualizacionCV.innerHTML += `<p>${datos.experiencia.experiencia}</p>`;
  
        visualizacionCV.innerHTML += `<h3>Educación</h3>`;
        visualizacionCV.innerHTML += `<p>${datos.educacion.educacion}</p>`;
  
        visualizacionCV.innerHTML += `<h3>Idiomas</h3>`;
        visualizacionCV.innerHTML += `<p>${datos.idiomas.idiomas}</p>`;
  
        visualizacionCV.innerHTML += `<h3>Actitudes</h3>`;
        visualizacionCV.innerHTML += `<p>${datos.actitudes.actitudes}</p>`;
  
        visualizacionCV.innerHTML += `<h3>Aptitudes</h3>`;
        visualizacionCV.innerHTML += `<p>${datos.aptitudes.aptitudes}</p>`;
  
        visualizacionCV.innerHTML += `<h3>Habilidades Complementarias</h3>`;
        visualizacionCV.innerHTML += `<p>${datos.habilidadesComplementarias.habilidadesComplementarias}</p>`;
    }
  
    function generarYDescargarCVTexto() {
        
        const visualizacionCV = document.getElementById('visualizacionCV');
  
        // Crea un contenido de texto con la información del CV
        const textoCV = construirTextoCV();
  
       
        const blob = new Blob([textoCV], { type: 'text/plain;charset=utf-8' });
  
        
        const enlaceDescarga = document.createElement('a');
        enlaceDescarga.href = URL.createObjectURL(blob);
        enlaceDescarga.download = 'cv.txt';
  
        
        document.body.appendChild(enlaceDescarga);
  
      
        enlaceDescarga.click();
  
        
        document.body.removeChild(enlaceDescarga);
    }
  
    function construirTextoCV() {
        const datos = obtenerDatosFormulario(document.getElementById('formularioDatosPersonales'));
        const descripcionPersonal = obtenerDatosFormulario(document.getElementById('formularioDescripcionPersonal'));
        const experiencia = obtenerDatosFormulario(document.getElementById('formularioExperiencia'));
        const educacion = obtenerDatosFormulario(document.getElementById('formularioEducacion'));
        const idiomas = obtenerDatosFormulario(document.getElementById('formularioIdiomas'));
        const actitudes = obtenerDatosFormulario(document.getElementById('formularioActitudes'));
        const aptitudes = obtenerDatosFormulario(document.getElementById('formularioAptitudes'));
        const habilidadesComplementarias = obtenerDatosFormulario(document.getElementById('formularioHabilidadesComplementarias'));
  
        let texto = '';
  
        texto += 'Datos Personales:\n';
        texto += `Nombre: ${datos.nombre}\n`;
        texto += `Apellidos: ${datos.apellidos}\n`;
        texto += `Dirección: ${datos.direccion}\n`;
        texto += `Teléfono: ${datos.telefono}\n`;
        texto += `Email: ${datos.email}\n`;
        texto += `Fecha de Nacimiento: ${datos.fechaNacimiento}\n`;
        texto += `Nacionalidad: ${datos.nacionalidad}\n`;
        texto += `Permiso de Conducir: ${datos.permisoConducir}\n\n`;
  
        texto += 'Descripción Personal:\n';
        texto += `${descripcionPersonal.descripcionPersonal}\n\n`;
  
        texto += 'Experiencia Laboral:\n';
        texto += `${experiencia.experiencia}\n\n`;
  
        texto += 'Educación:\n';
        texto += `${educacion.educacion}\n\n`;
  
        texto += 'Idiomas:\n';
        texto += `${idiomas.idiomas}\n\n`;
  
        texto += 'Actitudes:\n';
        texto += `${actitudes.actitudes}\n\n`;
  
        texto += 'Aptitudes:\n';
        texto += `${aptitudes.aptitudes}\n\n`;
  
        texto += 'Habilidades Complementarias:\n';
        texto += `${habilidadesComplementarias.habilidadesComplementarias}\n\n`;
  
        return texto;
    }
  });