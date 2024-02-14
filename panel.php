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
</head>

<body>
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
