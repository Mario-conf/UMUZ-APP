  // Mostrar y ocultar la visibilidad de la contraseña
  document.querySelector(".toggle-password").addEventListener("click", function() {
    var input = this.closest('.password-group').querySelector('input');
    var icon = this.querySelector('i');
    if (input.getAttribute("type") === "password") {
      input.setAttribute("type", "text");
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    } else {
      input.setAttribute("type", "password");
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    }
  });