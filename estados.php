<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Dashboard</title>
    <style>
        .bg-verde-suave {
            background-color: #d4edda;
        }

        .bg-amarillo-suave {
            background-color: #fff3cd;
        }

        .bg-rojo-suave {
            background-color: #f8d7da;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

</head>

<body>
    <!-- Menú de navegación -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand" href="panel.php">PANEL DE FACTURA ELECTRONICA</a>
        <!-- Botón de hamburguesa para dispositivos móviles -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Contenido del menú -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="panel.php">VOLVER <i class="fas fa-backward"></i></a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>Listado de Sucursales</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Sucursal</th>
                    <th>Empresa</th>
                    <th>Sucursal</th>
                    <th>Último Horario de Conexión</th>
                    <th>Diferencia (minutos)</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se mostrarán los datos de las sucursales -->
            </tbody>
        </table>

    </div>

    <script>
        // Función para calcular el color de fondo de la fila y la diferencia en minutos
        function calcularColorYDiferencia(fila, ultimaConexion) {
            const fechaActual = new Date();
            const fechaConexion = new Date(ultimaConexion);
            const diferenciaEnMilisegundos = fechaActual - fechaConexion;
            const diferenciaEnMinutos = Math.floor(diferenciaEnMilisegundos / 60000);

            if (diferenciaEnMinutos < 3) {
                fila.classList.add('bg-verde-suave');
            } else if (diferenciaEnMinutos >= 10 && diferenciaEnMinutos < 60) {
                fila.classList.add('bg-amarillo-suave');
            } else if (diferenciaEnMinutos >= 60) {
                fila.classList.add('bg-rojo-suave');
            }

            fila.querySelector('.diferencia').textContent = diferenciaEnMinutos;
        }

        function insertarObjeto() {
            const id = 0;
            const nombre = document.getElementById('nombre').value;
            const ruc = document.getElementById('ruc').value;
            const timbrado = document.getElementById('timbrado').value;
            const api_key = document.getElementById('api_key').value; // Nuevo campo de API Key

            // Construir el objeto a enviar en la solicitud POST
            const data = {
                id,
                nombre,
                ruc,
                timbrado,
                api_key
            };

            // Ejecutar la solicitud POST a api/empresas.php
            fetch('http://localhost/php-siscofe/api/empresas.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    // Manejar la respuesta del servidor
                    if (data.success) {
                        // Éxito: cerrar el modal y recargar los datos
                        $('#insertarModal').modal('hide'); // Cerrar el modal
                        cargarDatos(); // Recargar los datos en la tabla
                    } else {
                        // Error: mostrar mensaje de error al usuario
                        console.error('Error al insertar el objeto:', data.error);
                        // Aquí puedes mostrar un mensaje de error al usuario, por ejemplo, en un elemento HTML
                        // document.getElementById('mensajeError').textContent = data.error;
                    }
                })
                .catch(error => {
                    console.error('Error al insertar el objeto:', error);
                    // Manejar el error, por ejemplo, mostrar un mensaje de error genérico al usuario
                    // document.getElementById('mensajeError').textContent = 'Se produjo un error al procesar su solicitud. Por favor, inténtelo de nuevo más tarde.';
                });
        }


        // Función para cargar los datos de las sucursales y su último horario de conexión
        function cargarDatos() {
            // Aquí debes realizar una solicitud AJAX para obtener los datos de tu servidor
            // y luego insertarlos en la tabla.

            // Ejemplo de solicitud AJAX (debes adaptarlo a tu backend)
            fetch('/util/fe/api/estados.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector('tbody');
                    tbody.innerHTML = ''; // Limpiar la tabla antes de insertar los nuevos datos

                    // Iterar sobre los datos y crear filas de tabla
                    data.forEach(sucursal => {
                        const fila = document.createElement('tr');
                        fila.innerHTML = `
                            <td>${sucursal.id_sucursal}</td>
                            <td>${sucursal.empresa_nombre}</td>
                            <td>${sucursal.sucursal_nombre}</td>
                            <td>${sucursal.ultimaconexion}</td>
                            <td class="diferencia"></td>
                        `;
                        calcularColorYDiferencia(fila, sucursal.ultimaconexion);
                        tbody.appendChild(fila);
                    });
                })
                .catch(error => {
                    console.error('Error al cargar los datos:', error);
                });
        }

        // Llamar a la función para cargar los datos cuando la página se carga
        window.addEventListener('load', cargarDatos);
    </script>

</body>

</html>