let darkTheme = false;
// logica para cambiar el tema de claro a oscuro
function clearDisplay() {
  document.getElementById('display').value = '';
}
function appendToDisplay(value) {
  document.getElementById('display').value += value;
}
function calculateResult() {
  try {
    document.getElementById('display').value = eval(document.getElementById('display').value);
  } catch (error) {
    document.getElementById('display').value = 'Error';
  }
}
function calculateSquareRoot() {
  document.getElementById('display').value = Math.sqrt(eval(document.getElementById('display').value));
}
function calculateSin() {
  document.getElementById('display').value = Math.sin(eval(document.getElementById('display').value));
}
function calculateCos() {
  document.getElementById('display').value = Math.cos(eval(document.getElementById('display').value));
}
function toggleTheme() {
    darkTheme = !darkTheme;
    updateTheme();
  }
  function updateTheme() {
    const body = document.body;
    const calculator = document.querySelector('.calculator');
    const buttons = document.querySelectorAll('button');
    const input = document.getElementById('display');
    const links = document.querySelectorAll('a');
    const paragraphs = document.querySelectorAll('p');
    const labels = document.querySelectorAll('label');
    const selects = document.querySelectorAll('select');
  // compara el color orginal de los elementos que previamente ha obtenido su valor
    if (darkTheme) {
      body.style.backgroundColor = '#333';
      calculator.style.backgroundColor = '#444';
      calculator.style.boxShadow = '0 0 10px rgba(255, 255, 255, 0.1)';
      input.style.border = '1px solid #666';
  
      buttons.forEach(button => {
        button.style.backgroundColor = '#555';
        button.style.color = '#fff';
      });
  
      links.forEach(link => {
        link.style.color = '#fff';
      });
  
      paragraphs.forEach(paragraph => {
        paragraph.style.color = '#fff';
      });
  
      labels.forEach(label => {
        label.style.color = '#fff';
      });
  
      selects.forEach(select => {
        select.style.backgroundColor = '#555';
        select.style.color = '#fff';
      });
      // los transforma a su complementarios en dark mode
    } else {
      body.style.backgroundColor = '#f8f9fa';
      calculator.style.backgroundColor = 'rgba(167, 167, 167, 0.8)';
      calculator.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.1)';
      input.style.border = '1px solid #ccc';
  
      buttons.forEach(button => {
        button.style.backgroundColor = 'rgb(40, 0, 58)';
        button.style.color = '#fff';
      });
  
      links.forEach(link => {
        link.style.color = '#fff';
      });
  
      paragraphs.forEach(paragraph => {
        paragraph.style.color = '#000';
      });
  
      labels.forEach(label => {
        label.style.color = '#000';
      });
  
      selects.forEach(select => {
        select.style.backgroundColor = '#555';
        select.style.color = '#fff';
      });
    }
  
    input.style.color = darkTheme ? '#fff' : '#000';
  }
  // logica completa
  updateTheme();