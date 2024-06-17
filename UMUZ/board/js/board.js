document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('paintCanvas');
    const context = canvas.getContext('2d');
    let painting = false;
    let currentColor = '#000000';
    let currentThickness = 5;
    let undoHistory = [];
    let redoHistory = [];
    let textToolActive = false;
  
    function startPosition(e) {
      if (textToolActive) {
        const text = prompt('Enter your text:');
        context.font = `${currentThickness * 2}px Arial`;
        context.fillStyle = currentColor;
        context.fillText(text, e.clientX - canvas.offsetLeft, e.clientY - canvas.offsetTop);
        endPosition(); 
      } else {
        painting = true;
        draw(e);
      }
    }
  
    function endPosition() {
      painting = false;
      context.beginPath();
      undoHistory.push(context.getImageData(0, 0, canvas.width, canvas.height));
      redoHistory = [];
    }
    
  
    function draw(e) {
      if (!painting) return;
  
      let clientX, clientY;
      if (e.touches && e.touches.length > 0) {
        const touch = e.touches[0];
        clientX = touch.clientX;
        clientY = touch.clientY;
      } else {
        clientX = e.clientX;
        clientY = e.clientY;
      }
  
      context.lineWidth = currentThickness;
      context.lineCap = 'round';
      context.strokeStyle = currentColor;
  
      context.lineTo(clientX - canvas.offsetLeft, clientY - canvas.offsetTop);
      context.stroke();
      context.beginPath();
      context.moveTo(clientX - canvas.offsetLeft, clientY - canvas.offsetTop);
    }
  
    canvas.addEventListener('mousedown', startPosition);
    canvas.addEventListener('mouseup', endPosition);
    canvas.addEventListener('mousemove', draw);
  
    canvas.addEventListener('touchstart', startPosition);
    canvas.addEventListener('touchend', endPosition);
    canvas.addEventListener('touchmove', draw);
  
    const colorPicker = document.getElementById('colorPicker');
    colorPicker.addEventListener('input', () => {
      currentColor = colorPicker.value;
    });
  
    const thicknessSlider = document.getElementById('thicknessSlider');
    thicknessSlider.addEventListener('input', () => {
      currentThickness = thicknessSlider.value;
    });
  
    const clearButton = document.getElementById('clearButton');
    clearButton.addEventListener('click', () => {
      context.clearRect(0, 0, canvas.width, canvas.height);
    });
  
    const undoButton = document.getElementById('undoButton');
    undoButton.addEventListener('click', undo);
  
    const redoButton = document.getElementById('redoButton');
    redoButton.addEventListener('click', redo);
  
    const textToolButton = document.getElementById('textToolButton');
    textToolButton.addEventListener('click', toggleTextTool);
  
    const exportJpegButton = document.getElementById('exportJpegButton');
    exportJpegButton.addEventListener('click', () => exportToImage('jpeg'));
  
    const exportPngButton = document.getElementById('exportPngButton');
    exportPngButton.addEventListener('click', () => exportToImage('png'));
  
    function toggleTextTool() {
      textToolActive = !textToolActive;
    }
  
    function undo() {
      if (undoHistory.length > 0) {
        redoHistory.push(context.getImageData(0, 0, canvas.width, canvas.height));
        context.putImageData(undoHistory.pop(), 0, 0);
      }
    }
  
    function redo() {
      if (redoHistory.length > 0) {
        undoHistory.push(context.getImageData(0, 0, canvas.width, canvas.height));
        context.putImageData(redoHistory.pop(), 0, 0);
      }
    }
  
    function exportToImage(format) {
      const imgData = canvas.toDataURL(`image/${format}`);
      const link = document.createElement('a');
      link.href = imgData;
      link.download = `draw.${format}`;
      link.click();
    }
  });