<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
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
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4 text-center mt-3">
                <a href="estados.php" class="btn btn-light btn-lg">
                    <img src="img/estado.png" alt="ESTADOS" width="100">
                    <br>
                    ESTADOS
                </a>
            </div>
            <div class="col-md-4 text-center mt-3">
                <a href="empresas.php" class="btn btn-light btn-lg">
                    <img src="img/empresa.png" alt="EMPRESA" width="100">
                    <br>
                    EMPRESA
                </a>
            </div>
            <div class="col-md-4 text-center mt-3">
                <a href="sucursales.php" class="btn btn-light btn-lg">
                    <img src="img/sucursal.png" alt="SUCURSAL" width="100">
                    <br>
                    SUCURSAL
                </a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>