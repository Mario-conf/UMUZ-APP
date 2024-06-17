document.addEventListener('DOMContentLoaded', function() {
    const enterBtns = document.querySelectorAll('.btn-enter');
    // comprobar errores de enlaces y redireccionar
    enterBtns.forEach((btn) => {
        btn.addEventListener('click', function() {
            const link = btn.getAttribute('data-link');
            if (link) {
                window.location.href = link;
            } else {
                alert('No valid link provided.');
            }
        });
    });
    // logica para hacer que el reloj funcione correctamente
    function updateClock() {
        const now = new Date();
        const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const dayOfWeek = daysOfWeek[now.getDay()];
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
    
        document.querySelector('.clock-day').textContent = dayOfWeek;
    
        document.querySelector('.clock-hour').textContent = hours;
        document.querySelector('.clock-minute').textContent = minutes;
        document.querySelector('.clock-second').textContent = seconds;
    }
    setInterval(updateClock, 1000);
    updateClock();
     
});
