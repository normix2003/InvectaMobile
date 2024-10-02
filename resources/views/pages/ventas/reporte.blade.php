<!doctype html>
<html lang="en">

<head>
    <title>Factura</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <style>
        main {
            max-width: 85%;
            margin: 0 auto;
        }

        header {
            width: 95%;
            margin: 0 auto;
        }

        .header-container {
            background-color: #e2dede;
        }

        .header-title {
            text-align: start;
            padding: 5px;
            margin-left: 25px;
            font-family: 'Courier New';
            font: bold;
        }

        .fecha {
            position: absolute;
            left: 30px;
            margin-top: -3px;
            margin-left: 22px;
            font-size: 13.5px;
            font-family: 'Courier New';
            font: bold;
        }


        .cliente-container {
            font-size: 15px;
            margin-top: 60px;
            font-family: 'Courier New';
        }

        .vendedor-container {
            font-size: 14px;
            margin-top: 30px;
            font-family: 'Courier New';
            font: bold;
        }

        .contacto-container {
            font-size: 14px;
            margin-top: 60px;
            font-family: 'Courier New';
            font: bold;
        }

        /* Estilos generales para la tabla */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            font-family: 'Courier New';
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 50px;
        }

        /* Encabezado de la tabla */
        .table-header th {
            background-color: #111010;
            /* Color oscuro para la cabecera */
            color: #fff;
            padding: 8px;
            text-align: center;
        }

        /* Filas de la tabla */
        .table-rows td {
            padding: 9px;
            text-align: center;
        }

        .total-row td {
            padding: 15px;
            text-align: center;
            font-weight: bold;
        }

        .table-rows {
            border-bottom: 1px solid #000;
        }

        .linea {
            display: block;
            height: 1px;
            border: 0;
            border-top: 1px solid #ccc;
            margin: 1em 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <header>
        <div class="header-container">
            <h1 class="header-title">FACTURA</h1>
        </div>
    </header>
    <main>
        <span class="fecha">
            <p>Fecha: <strong>{{$fecha}}</strong></p>
        </span>
        <div class="cliente-container">
            <h3>INFORMACION DEL CLIENTE</h3>
            <p><strong>Nombre:</strong> {{$cliente->Nombres . ' ' . $cliente->Apellidos}}</p>
            <p><strong>DUI:</strong> {{$cliente->DUI}}</p>
        </div>
        <div class="vendedor-container">
            <h3>INFORMACIÓN DEL VENDEDOR</h3>
            <p><strong>Nombre:</strong> {{$empleado->Nombre_Empleado . ' ' . $empleado->Apellidos}}</p>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead class="table-header">
                    <tr>
                        <th scope="col">PRODUCTO</th>
                        <th scope="col">CANTIDAD</th>
                        <th scope="col">PRECIO</th>
                        <th scope="col">TOTAL</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                        <tr class="table-rows">
                            <td scope="row">{{$producto['Nombre_Producto']}}</td>
                            <td>{{$producto['Cantidad']}}</td>
                            <td>${{$producto['Precio']}}</td>
                            <td>${{$producto['Total']}}</td>
                            <td></td>

                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td scope="row" class="px-3 text-center"></td>
                        <td class="px-3 text-center"></td>
                        <td class="px-3 text-center"></td>
                        <td class="px-3 text-center">Total Venta:</td>
                        <td class="px-3 text-center">${{$total}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="contacto-container">
            <h3>INFORMACION DE CONTACTO</h3>
            <h4><strong>InvectaMobile</strong></h4>
            <p><strong>Telefono:</strong> 2222-2222</p>
            <p><strong>Correo:</strong> inventamobile@gmail.com</p>
            <p><strong>Direccion:</strong> Colonia de las fifis casa 9 lote 25</p>
        </div>
    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>