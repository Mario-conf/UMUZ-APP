function iniciarCalculadora() {
    function calcularInteresSimple(monto, tasa, tiempo) {
      return monto * (1 + (tasa / 100) * tiempo);
    }
    // iniciar la calculadora y sus funciones
  function calcularInteresCompuesto(monto, tasa, tiempo) {
      return monto * Math.pow(1 + tasa / 100, tiempo);
    }
// calcular los interes 
    function manejarFormularioInteres() {
     var monto = parseFloat(document.getElementById("monto").value);
      var tasa = parseFloat(document.getElementById("tasa").value);
      var tiempo = parseFloat(document.getElementById("tiempo").value);

      var interesSimple = calcularInteresSimple(monto, tasa, tiempo);
      var interesCompuesto = calcularInteresCompuesto(monto, tasa, tiempo);

      alert("Initial Amount + Simple Interest: " + interesSimple.toFixed(2) + "\nInitial Amount + Simple Interest: " + interesCompuesto.toFixed(2));
    }

   document.getElementById("calcularInteresForm").addEventListener("submit", function (event) {
      event.preventDefault();
      manejarFormularioInteres();
    });
  }
  // calcular el promedio
document.addEventListener("DOMContentLoaded", iniciarCalculadora);
function iniciarCalculadoraPromedio() {
    function calcularPromedio(numeros) {
      const arrayNumeros = numeros.split(',').map(numero => parseFloat(numero.trim()));
      const suma = arrayNumeros.reduce((total, num) => total + num, 0);
      const promedio = suma / arrayNumeros.length;
  
      return promedio;
    }
    function manejarFormularioPromedio(event) {
      event.preventDefault();
  
      const numeros = document.getElementById('numeros').value;
  
      if (numeros.trim() === '') {
        alert('Please enter at least one number.');
        return;

      }
  
     const promedio = calcularPromedio(numeros);
     alert(`The average is: ${promedio.toFixed(2)}`);
    } document.getElementById('calcularPromedioForm').addEventListener('submit', manejarFormularioPromedio);
  }
  // calcular las dimensiones y areas
  document.addEventListener('DOMContentLoaded', iniciarCalculadoraPromedio); 
  function mostrarDimensiones() {
    const tipoFigura = document.getElementById('tipoFigura').value;
    const dimensionesDiv = document.getElementById('dimensionesFigura');
     dimensionesDiv.innerHTML = '';
    switch (tipoFigura) {
        case 'triangulo':
                dimensionsDiv.innerHTML = `
                    <label for="baseTriangulo">Base:</label>
                    <input type="text" id="baseTriangulo" name="baseTriangulo" required>
                    <label for="alturaTriangulo">Height:</label>
                    <input type="text" id="alturaTriangulo" name="alturaTriangulo" required>
                `;
                break;
            case 'cuadrado':
                dimensionsDiv.innerHTML = `
                    <label for="ladoCuadrado">Side:</label>
                    <input type="text" id="ladoCuadrado" name="ladoCuadrado" required>
                `;
                break;
            case 'circulo':
                dimensionsDiv.innerHTML = `
                    <label for="radioCirculo">Radius:</label>
                    <input type="text" id="radioCirculo" name="radioCirculo" required>
                `;
                break;
            default:
                break;
        }
}
// calcular el area
function calcularAreaVolumen() {
    const tipoFigura = document.getElementById('tipoFigura').value;
    const dimensionesDiv = document.getElementById('dimensionesFigura');
    let dimensiones;
    switch (tipoFigura) {
        case 'triangulo':
            dimensiones = {
                base: parseFloat(document.getElementById('baseTriangulo').value),
                altura: parseFloat(document.getElementById('alturaTriangulo').value)
            };
            break;
        case 'cuadrado':
            dimensiones = {
                lado: parseFloat(document.getElementById('ladoCuadrado').value)
            };
            break;
        case 'circulo':
            dimensiones = {
                radio: parseFloat(document.getElementById('radioCirculo').value)
            };
            break;
        default:
            break;
    }
    let resultado;
    switch (tipoFigura) {
        case 'triangulo':
            resultado = (dimensiones.base * dimensiones.altura) / 2;
            break;
        case 'cuadrado':
            resultado = Math.pow(dimensiones.lado, 2);
            break;
        case 'circulo':
            resultado = Math.PI * Math.pow(dimensiones.radio, 2);
            break;
        default:
            break;
    }
 alert(`The area / volume of the selected figure is: ${resultado.toFixed(2)}`);
}
// calcular proporciones
    var formulario = document.getElementById('proporcionForm');
    formulario.addEventListener('submit', function(event) {
        event.preventDefault();
        var parte1 = parseFloat(document.querySelector('input[name="parte1"]').value);
        var parte2 = parseFloat(document.querySelector('input[name="parte2"]').value);
        var parte3 = parseFloat(document.querySelector('input[name="parte3"]').value);
        var parte4 = parseFloat(document.querySelector('input[name="parte4"]').value);
        if (isNaN(parte1) || isNaN(parte2) || isNaN(parte3) || isNaN(parte4)) {
            alert(`Please enter valid numbers in all parts.`);
            return;
        }
        var resultado = (parte1 * parte4) === (parte2 * parte3) ? "The parts are proportional" : "The parts are not proportional";
        alert(resultado);
    });
    // ecuaciones 
    function resolverEcuacion() {
        var ecuacionForm = document.getElementById('ecuacionForm');
    
        var coeficiente = parseFloat(document.querySelector('input[name="coeficiente"]').value);
        var constante = parseFloat(document.querySelector('input[name="constante"]').value);
    
        if (isNaN(coeficiente) || isNaN(constante)) {
            alert("Enter valid coefficient and constant.");
            return false;            
        }
    
         if (coeficiente === 0) {
            alert("The equation is not linear.");
        } else {
            var solucion = -constante / coeficiente;
            alert("The solution of the linear equation is x = " + solucion);
        }
    
        return false;  
    }
    document.addEventListener("DOMContentLoaded", function() {
    });
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('resolverEcuacionSegundoGradoBtn').addEventListener('click', resolverEcuacionSegundoGrado);
        document.getElementById('resolverSistemaEcuacionesBtn').addEventListener('click', resolverSistemaEcuaciones);
    // ecuaciones de segundo grado
        function resolverEcuacionSegundoGrado() {
            var form = document.getElementById('ecuacionSegundoGradoForm');
    
            var coeficienteA = parseFloat(form.querySelector('input[name="coeficienteA"]').value);
            var coeficienteB = parseFloat(form.querySelector('input[name="coeficienteB"]').value);
            var coeficienteC = parseFloat(form.querySelector('input[name="coeficienteC"]').value);
    
            if (isNaN(coeficienteA) || isNaN(coeficienteB) || isNaN(coeficienteC)) {
                alert("Please enter valid coefficients.");
                return;
                
            }
    
            var discriminante = coeficienteB * coeficienteB - 4 * coeficienteA * coeficienteC;
    
            if (discriminante > 0) {
                var x1 = (-coeficienteB + Math.sqrt(discriminante)) / (2 * coeficienteA);
                var x2 = (-coeficienteB - Math.sqrt(discriminante)) / (2 * coeficienteA);
                alert("The solutions are x1 = " + x1 + " and x2 = " + x2);
            } else if (discriminante === 0) {
                var x = -coeficienteB / (2 * coeficienteA);
                alert("The double solution is x = " + x);
            } else {
                alert("The equation has no real solutions.");
            }
        }
        // sistemas de ecuaciones
        function resolverSistemaEcuaciones() {
            var form = document.getElementById('sistemaEcuacionesForm');
    
            var coeficienteA1 = parseFloat(form.querySelector('input[name="coeficienteA1"]').value);
            var coeficienteB1 = parseFloat(form.querySelector('input[name="coeficienteB1"]').value);
            var constanteC1 = parseFloat(form.querySelector('input[name="constanteC1"]').value);
    
            var coeficienteD2 = parseFloat(form.querySelector('input[name="coeficienteD2"]').value);
            var coeficienteE2 = parseFloat(form.querySelector('input[name="coeficienteE2"]').value);
            var constanteF2 = parseFloat(form.querySelector('input[name="constanteF2"]').value);
    
            if (isNaN(coeficienteA1) || isNaN(coeficienteB1) || isNaN(constanteC1) ||
                isNaN(coeficienteD2) || isNaN(coeficienteE2) || isNaN(constanteF2)) {
                    alert("Please enter valid coefficients and constants.");
                return;
            }
    
            var determinante = coeficienteA1 * coeficienteE2 - coeficienteB1 * coeficienteD2;
    
            if (determinante === 0) {
                alert("The system does not have a unique solution.");
            } else {
                var x = (constanteC1 * coeficienteE2 - constanteF2 * coeficienteB1) / determinante;
                var y = (constanteF2 * coeficienteA1 - constanteC1 * coeficienteD2) / determinante;
                alert("The solution of the system is x = " + x + " and y = " + y);
            }
        }
    });
    // calcular propporciones
    function calcularPorcentaje() {
        var valorInicial = parseFloat(document.forms["porcentajeForm"]["valorInicial"].value);
        var valorFinal = parseFloat(document.forms["porcentajeForm"]["valorFinal"].value);
        var porcentajeCambio = ((valorFinal - valorInicial) / valorInicial) * 100;
        alert("The percentage change is: " + porcentajeCambio.toFixed(2) + "%");
    }
    // calcular regla de 3
    function calcularReglaDeTres() {
        var valor1 = parseFloat(document.getElementById('valor1').value);
        var valor2 = parseFloat(document.getElementById('valor2').value);
        var valor3 = parseFloat(document.getElementById('valor3').value);
      
        var resultado = (valor3 * valor2) / valor1;
      
        alert('The result is: ' + resultado.toFixed(2));
      }
        