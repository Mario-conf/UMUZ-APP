# Universal Management Utilities Zone (UMUZ)

![UMUZ Logo](bak.png)

## About UMUZ

Welcome to Universal Management Utilities Zone, your ultimate productivity companion. UMUZ is a complete productivity ecosystem designed to revolutionize the way you manage your life and work. With UMUZ, you can centralize all your tasks, events, and projects in one place, simplifying your workflow and boosting your productivity.

## Features

- **Intuitive Dashboard**: Centralize and manage your tasks and projects effortlessly from one convenient location.
  
- **Customizable Interface**: Personalize UMUZ with customizable themes, layouts, and color schemes to suit your preferences.

- **Cross-Platform Accessibility**: Access your tasks, events, and projects from any device, ensuring you never miss a beat.

- **Task Management**: Prioritize, organize, and tackle your workload with powerful task management features.

- **Event Scheduling**: Seamlessly plan and manage your events with intuitive scheduling tools.

- **Accounting**: Manage your finances effortlessly with robust accounting features, including billable hours tracking, income monitoring, and budget creation.

- **Calculator**: Simplify complex calculations with an integrated calculator tool.

- **Currency Converter**: Stay informed about exchange rates and manage international transactions with ease.

- **Drawing Board**: Unleash your creativity and brainstorm ideas with an innovative drawing board feature.

- **Weather App**: Stay prepared with integrated weather forecasts.

- **Graphs and Statistics**: Track your productivity and performance with comprehensive graphs and statistics.

## Installation

### Using Dockerfile:

1. Create the following Dockerfile in your working directory:

    ```dockerfile
    # We use a base image of Ubuntu 22.04
    FROM ubuntu:22.04

    # Update the system and install Apache and PHP with the PHP module for Apache
    RUN apt-get update && \
        DEBIAN_FRONTEND=noninteractive apt-get install -y apache2 php libapache2-mod-php unzip

    # Copy the UMUZ.zip file to the container
    COPY UMUZ.zip /var/www/html/

    # Unzip the file in the web server folder
    RUN cd /var/www/html/ && \
        unzip UMUZ.zip && \
        mv UMUZ/* . && \
        rm -r UMUZ UMUZ.zip

    # Set the working directory
    WORKDIR /var/www/html

    # Expose the necessary ports (80 for Apache and 2024 for the web application)
    EXPOSE 80 2024

    # Start the necessary service (Apache) when running the container
    CMD service apache2 start && tail -f /dev/null
    ```

2. Build the Docker image:

    ```sh
    docker build -t umuz .
    ```

3. Run the Docker container:

    ```sh
    docker run -d -p 80:80 -p 2024:2024 umuz
    ```

4. Access the application through your browser:

    - `http://localhost` for port 80
    - `http://localhost:2024` for port 2024

### Manual Installation (LAMP, XAMPP, etc.):

1. Download the UMUZ repository from GitHub.

2. Install a local server environment such as LAMP or XAMPP.

3. Extract the UMUZ project folder into the appropriate directory of your local server environment.

4. Access UMUZ through your web browser by navigating to the appropriate URL.

## Documentation

For more detailed documentation and resources, please refer to the [UMUZ GitHub Repository](https://github.com/Mario-conf/UMUZ). The repository contains the source code, documentation, issue tracking, and contribution guidelines.

---

### Instructions for Using the Project Code

1. **Delete Files**:
   - Once you have downloaded the project's .zip file, delete the `index.html` and `bak.png` files that are outside the `UMUZ` folder.
   - Make sure to delete all the contents of the `htdocs` folder (but not the folder itself).

2. **Paste the Content**:
   - Paste the contents of the .zip file into the root of your web server.
   - Copy the contents of this repository into the `htdocs` folder.

3. **Web Server**:
   - You can use the Apache server provided in our documentation or any other server of your choice.

4. **Execution with Dockerfile**:
   - If you want to run the project with Dockerfile, unzip the project's .zip file and use the `UMUZ` folder inside it as the .zip file used by the Dockerfile. Otherwise, you might encounter file path issues when running it.

---

# Universal Management Utilities Zone (UMUZ)

![UMUZ Logo](bak.png)

## Acerca de UMUZ

Bienvenido a Universal Management Utilities Zone, tu compañero definitivo de productividad. UMUZ es un ecosistema de productividad completo diseñado para revolucionar la forma en que gestionas tu vida y trabajo. Con UMUZ, puedes centralizar todas tus tareas, eventos y proyectos en un solo lugar, simplificando tu flujo de trabajo y aumentando tu productividad.

## Características

- **Tablero Intuitivo**: Centraliza y gestiona tus tareas y proyectos de manera fácil desde un lugar conveniente.
  
- **Interfaz Personalizable**: Personaliza UMUZ con temas, diseños y esquemas de colores personalizables para adaptarse a tus preferencias.

- **Accesibilidad Multiplataforma**: Accede a tus tareas, eventos y proyectos desde cualquier dispositivo, asegurando que nunca pierdas un detalle.

- **Gestión de Tareas**: Prioriza, organiza y aborda tu carga de trabajo con potentes funciones de gestión de tareas.

- **Programación de Eventos**: Planifica y gestiona tus eventos de manera fluida con herramientas de programación intuitivas.

- **Contabilidad**: Gestiona tus finanzas sin esfuerzo con funciones robustas de contabilidad, incluyendo el seguimiento de horas facturables, monitoreo de ingresos y creación de presupuestos.

- **Calculadora**: Simplifica cálculos complejos con una herramienta de calculadora integrada.

- **Convertidor de Moneda**: Mantente informado sobre tasas de cambio y gestiona transacciones internacionales con facilidad.

- **Pizarra de Dibujo**: Desata tu creatividad y genera ideas con una función innovadora de pizarra de dibujo.

- **Aplicación del Clima**: Mantente preparado con pronósticos meteorológicos integrados.

- **Gráficos y Estadísticas**: Rastrea tu productividad y rendimiento con gráficos y estadísticas completas.

## Instalación

### Usando Dockerfile:

1. Crea el siguiente Dockerfile en tu directorio de trabajo:

    ```dockerfile
    # Usamos una imagen base de Ubuntu 22.04
    FROM ubuntu:22.04

    # Actualizar el sistema e instalar Apache y PHP con el módulo PHP para Apache
    RUN apt-get update && \
        DEBIAN_FRONTEND=noninteractive apt-get install -y apache2 php libapache2-mod-php unzip

    # Copiar el archivo UMUZ.zip al contenedor
    COPY UMUZ.zip /var/www/html/

    # Descomprimir el archivo en la carpeta del servidor web
    RUN cd /var/www/html/ && \
        unzip UMUZ.zip && \
        mv UMUZ/* . && \
        rm -r UMUZ UMUZ.zip

    # Establecer el directorio de trabajo
    WORKDIR /var/www/html

    # Exponer los puertos necesarios (80 para Apache y 2024 para la aplicación web)
    EXPOSE 80 2024

    # Iniciar el servicio necesario (Apache) al ejecutar el contenedor
    CMD service apache2 start && tail -f /dev/null
    ```

2. Construye la imagen Docker:

    ```sh
    docker build -t umuz .
    ```

3. Ejecuta el contenedor Docker:

    ```sh
    docker run -d -p 80:80 -p 2024:2024 umuz
    ```

4. Accede a la aplicación a través de tu navegador:

    - `http://localhost` para el puerto 80
    - `http://localhost:2024` para el puerto 2024

### Instalación Manual (LAMP, XAMPP, etc.):

1. Descarga el repositorio de UMUZ desde GitHub.

2. Instala un entorno de servidor local como LAMP o XAMPP.

3. Extrae la carpeta del proyecto UMUZ en el directorio apropiado de tu entorno de servidor local.

4. Accede a UMUZ a través de tu navegador web navegando a la URL correspondiente.

## Documentación

Para una documentación y recursos más detallados, consulta el [Repositorio de GitHub de UMUZ](https://github.com/Mario-conf/UMUZ). El repositorio contiene el código fuente, documentación, seguimiento de problemas y pautas de contribución.

---

### Instrucciones para Usar el Código del Proyecto

1. **Borrar Archivos**:
   - Una vez descargado el archivo .zip del proyecto, elimine los archivos `index.html` y `bak.png` que están fuera de la carpeta `UMUZ`.
   - Asegúrese de borrar todo el contenido de la carpeta `htdocs` (pero no la carpeta en sí).

2. **Pegar el Contenido**:
   - Pegue el contenido del archivo .zip en la raíz de su servidor web.
   - Copie el contenido de este repositorio en la carpeta `htdocs`.

3. **Servidor Web**:
   - Puede usar el servidor Apache proporcionado en nuestra documentación o cualquier otro servidor de su elección.

4. **Ejecución con Dockerfile**:
   - Si desea ejecutar el proyecto con Dockerfile, descomprima el archivo .zip del proyecto y use la carpeta `UMUZ` de su interior como el archivo .zip que usa el Dockerfile. De lo contrario, es probable que tenga fallos de rutas de archivos al ejecutarlo.
