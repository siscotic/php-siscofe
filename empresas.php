<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <title>Empresas</title>
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
    <button type="button" class="btn btn-primary btn-sm" onclick="abrirModalInsertarModificar(0)">Nuevo</button>
        <h1>Listado de Empresas</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>RUC</th>
                    <th>Timbrado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="insertarModal" tabindex="-1" role="dialog" aria-labelledby="insertarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="insertarModalLabel">Insertar Nuevo Objeto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Aquí colocarás los campos para insertar el nuevo objeto -->
                    <form id="formularioInsertar">
                        <input type="hidden" id="id" name="id" name="0">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="ruc">RUC</label>
                            <input type="text" class="form-control" id="ruc" name="ruc" required>
                        </div>
                        <div class="form-group">
                            <label for="timbrado">Timbrado</label>
                            <input type="text" class="form-control" id="timbrado" name="timbrado" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="insertarObjeto()">Insertar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para cargar los datos de las empresas desde la API
        function cargarDatosEmpresas() {
            fetch('api/empresas.php') // Realizar solicitud GET a la API
                .then(response => response.json()) // Parsear la respuesta como JSON
                .then(data => {
                    if (data.success) {
                        // Limpiar el contenido actual de la tabla
                        const tbody = document.querySelector('tbody');
                        tbody.innerHTML = '';

                        // Iterar sobre los datos y crear filas de tabla
                        data.datos.forEach(empresa => {
                            const fila = document.createElement('tr');
                            fila.setAttribute('data-id', empresa.id);
                            fila.innerHTML = `
                        <td>${empresa.id}</td>
                        <td>${empresa.nombre}</td>
                        <td>${empresa.ruc}</td>
                        <td>${empresa.timbrado}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" onclick="abrirModalInsertarModificar(${empresa.id})">Modificar</button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminar(${empresa.id})">Eliminar</button>
                        </td>
                    `;
                            tbody.appendChild(fila);
                        });
                    } else {
                        console.error('Error al cargar los datos de las empresas:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error al cargar los datos de las empresas:', error);
                });
        }

        // Llamar a la función para cargar los datos cuando la página se carga
        window.addEventListener('load', cargarDatosEmpresas);

        function abrirModalInsertarModificar(idEmpresa = 0) {
            const modal = $('#insertarModal');
            modal.find('form')[0].reset();
            modal.find('#id').val(idEmpresa);
            const botonInsertarModificar = modal.find('.btn-primary');

            if (idEmpresa === 0) {
                modal.find('.modal-title').text('Insertar Nuevo Objeto');
                botonInsertarModificar.text('Insertar');
            } else {
                modal.find('.modal-title').text('Modificar Objeto');

                const fila = $(`tr[data-id="${idEmpresa}"]`);

                modal.find('#nombre').val(fila.find('td:eq(1)').text());
                modal.find('#ruc').val(fila.find('td:eq(2)').text());
                modal.find('#timbrado').val(fila.find('td:eq(3)').text());
                botonInsertarModificar.text('Modificar');
            }


            modal.modal('show');
        }

        window.addEventListener('load', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const idEmpresa = parseInt(urlParams.get('idEmpresa'));
            if (!isNaN(idEmpresa) && idEmpresa > 0) {
                abrirModalInsertarModificar(idEmpresa);
            }
        });

        function confirmarEliminar(idEmpresa) {
            if (confirm("¿Estás seguro de que deseas eliminar esta empresa?")) {
                eliminarEmpresa(idEmpresa);
            }
        }

        function eliminarEmpresa(idEmpresa) {
            fetch('api/empresas.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: idEmpresa })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cargarDatosEmpresas();
                        alert("La empresa se ha eliminado correctamente.");
                    } else {
                        alert('Error al insertar o modificar la sucursal:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar la empresa:', error);
                    alert("Error al eliminar la empresa.");
                });
        }

        function insertarObjeto() {
            const id = document.getElementById('id').value;
            const nombre = document.getElementById('nombre').value;
            const ruc = document.getElementById('ruc').value;
            const timbrado = document.getElementById('timbrado').value;
            const api_key = "";

            const data = {
                id,
                nombre,
                ruc,
                timbrado,
                api_key
            };

            fetch('api/empresas.php', {
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
                        cargarDatosEmpresas();
                    } else {
                        console.error('Error al insertar el objeto:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error al insertar el objeto:', error);
                });
        }

    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>