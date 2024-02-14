<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }

        .container {
            max-width: 500px;
        }

        .logo {
            max-width: 100px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="text-center mb-4">
            <img class="logo" src="img/logo.ico" alt="Logo">
        </div>
        <div class="form-group">
            <form id="formulario" class="needs-validation" novalidate action="buscar_qr.php" method="GET">
                <label for="clave1">Ingrese el Nro Factura:</label>
                <input type="text" class="form-control" id="clave1" name="clave1" placeholder="" required
                    data-inputmask="'mask': '999-999-9999999'">
                <div class="invalid-feedback">
                    Por favor ingrese la factura número: "001-001-0000001"
                </div>
        </div>
        <div class="form-group">
            <label for="clave2">PIN:</label>
            <input type="number" class="form-control" id="clave2" name="clave2" placeholder="" required>
            <div class="invalid-feedback">
                El pin tiene minimo 7 digitos
            </div>
        </div>
        <button type="button" onclick="consultar();" class="btn btn-primary btn-block">CONSULTAR</button>
        </form>
        <div id="message" class="mt-3"></div>
    </div>

    <!-- Bootstrap JS and jQuery (required for Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"
        integrity="sha512-efAcjYoYT0sXxQRtxGY37CKYmqsFVOIwMApaEbrxJr4RwqVVGw8o+Lfh/+59TU07+suZn1BWq4fDl5fdgyCNkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        inputFactura = document.getElementById("clave1");
        inputPin = document.getElementById("clave2");
        $(document).ready(function () {
            Inputmask().mask(document.querySelectorAll("input"));
        });

        function consultar() {
            if (!validar()) return false;

            document.getElementById("formulario").submit();
        }

        function validar() {
            let pin = inputPin.value;
            let numfactura = inputFactura.value.replace(/_/g, '');
            let partes = numfactura.split("-");

            if (pin.length < 5) {
                inputPin.classList.add("is-invalid");
                return false;
            }

            if (partes.length !== 3) {
                inputFactura.classList.add("is-invalid");
                return false;
            }

            if (partes[0].length !== 3 || partes[1].length !== 3 || partes[2].length !== 7) {
                inputFactura.classList.add("is-invalid");
                return false;
            }

            inputFactura.classList.remove("is-invalid");
            return true;
        }

    </script>

</body>

</html>