<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Sucursales</title>
</head>

<body>
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
    <!-- Contenedor principal -->
    <div class="container mt-5">
        <button type="button" class="btn btn-primary btn-sm" onclick="abrirModalInsertarModificar(0)">Nuevo</button>
        <h1>Listado de Sucursales</h1>
        <div class="table-responsive" style="max-height: 70vh; overflow-x: auto;">
  <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>ID Empresa</th>
                        <th>Empresa</th>
                        <th>Factura</th>
                        <th>Visible</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaSucursales">
                    <!-- Aquí se cargarán dinámicamente las filas de la tabla -->
                </tbody>
            </table>
        </div>
        <!-- Modal para insertar o modificar sucursales -->
        <div class="modal fade" id="insertarModal" tabindex="-1" role="dialog" aria-labelledby="insertarModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="insertarModalLabel">Insertar Nueva Sucursal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulario para insertar o modificar la sucursal -->
                        <form id="formularioSucursal">
                            <!-- Campo para el ID (oculto) -->
                            <input type="hidden" id="idSucursal" name="idSucursal">
                            <!-- Campo para el nombre de la sucursal -->
                            <div class="form-group">
                                <label for="nombreSucursal">Nombre</label>
                                <input type="text" class="form-control" id="nombreSucursal" name="nombreSucursal"
                                    required>
                            </div>
                            <!-- Campo select para seleccionar la empresa -->
                            <div class="form-group">
                                <label for="selectEmpresa">Empresa</label>
                                <select class="form-control" id="selectEmpresa" name="idEmpresa" required>
                                    <!-- Aquí se cargarán las opciones desde la API -->
                                </select>
                            </div>
                            <!-- Campo para la factura -->
                            <div class="form-group">
                                <label for="factura">Factura</label>
                                <input type="text" class="form-control" id="factura" name="factura" required>
                            </div>
                            <!-- Campo para el valor de visible (oculto, con valor por defecto 1) -->
                            <input type="hidden" id="visible" name="visible" value="1">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary"
                            onclick="insertarModificarSucursal()">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Función para cargar los datos de las sucursales desde la API
        function cargarDatosSucursales() {
            fetch('api/sucursales.php') // Realizar solicitud GET a la API
                .then(response => response.json()) // Parsear la respuesta como JSON
                .then(data => {
                    if (data.success) {
                        // Limpiar el contenido actual de la tabla
                        const tablaSucursales = document.getElementById('tablaSucursales');
                        tablaSucursales.innerHTML = '';

                        // Iterar sobre los datos y crear filas de tabla
                        data.datos.forEach(sucursal => {
                            const fila = document.createElement('tr');
                            fila.setAttribute('data-id', sucursal.id);
                            const visibilidad = sucursal.visible == 1 ? 'Activo' : 'Borrado';
                            const claseVisibilidad = sucursal.visible == 1 ? 'text-primary' : 'text-warning';
                            fila.innerHTML = `
                                <td>${sucursal.id}</td>
                                <td>${sucursal.nombre}</td>
                                <td>${sucursal.id_empresa}</td>
                                <td>${sucursal.empresa_descripcion}</td>
                                <td>${sucursal.factura}</td>
                                <td><span class="badge ${claseVisibilidad} rounded-pill">${visibilidad}</span></td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="abrirModalInsertarModificar(${sucursal.id})">Modificar</button>
                                </td>
                            `;
                            tablaSucursales.appendChild(fila);
                        });
                    } else {
                        console.error('Error al cargar los datos de las sucursales:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error al cargar los datos de las sucursales:', error);
                });
        }

        function insertarModificarSucursal() {
            const id = $('#idSucursal').val();
            const nombre = $('#nombreSucursal').val();
            const idEmpresa = $('#selectEmpresa').val();
            const factura = $('#factura').val();
            const visible = 1; // Valor predeterminado

            const data = {
                id,
                nombre,
                id_empresa: idEmpresa,
                factura,
                visible
            };

            fetch('api/sucursales.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        $('#insertarModal').modal('hide');
                        cargarDatosSucursales();
                    } else {
                        alert('Error al insertar o modificar la sucursal:', data.error);
                    }
                })
                .catch(error => {
                    alert('Error al insertar o modificar la sucursal:', error);
                });
        }


        function abrirModalInsertarModificar(idSucursal = 0) {
            const modal = $('#insertarModal');
            modal.find('form')[0].reset();
            modal.find('#idSucursal').val(idSucursal);
            const botonInsertarModificar = modal.find('.btn-primary');
            if (idSucursal === 0) {
                modal.find('.modal-title').text('Insertar Nueva Sucursal');
                botonInsertarModificar.text('Insertar');
            } else {
                modal.find('.modal-title').text('Modificar Sucursal');

                const fila = $(`tr[data-id="${idSucursal}"]`);

                modal.find('#nombreSucursal').val(fila.find('td:eq(1)').text());
                modal.find('#selectEmpresa').val(fila.find('td:eq(2)').text().trim());
                modal.find('#factura').val(fila.find('td:eq(4)').text());
                botonInsertarModificar.text('Modificar');
            }

            modal.modal('show');
        }


        function cargarDatosEmpresas() {
            fetch('api/empresas.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const selectEmpresa = document.getElementById('selectEmpresa');

                        // Limpiar opciones anteriores del select
                        selectEmpresa.innerHTML = '';

                        // Iterar sobre los datos y crear opciones para el select
                        data.datos.forEach(empresa => {
                            const option = document.createElement('option');
                            option.value = empresa.id;
                            option.textContent = empresa.nombre; // Considera cambiar 'descripcion' por el nombre real del campo
                            selectEmpresa.appendChild(option);
                        });
                    } else {
                        console.error('Error al cargar los datos de las empresas:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error al cargar los datos de las empresas:', error);
                });
        }

        window.addEventListener('load', function () {
            cargarDatosEmpresas();
            cargarDatosSucursales();
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</body>

</html>