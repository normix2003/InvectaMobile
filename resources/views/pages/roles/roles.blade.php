<!doctype html>
<html lang="en">

<head>
    <title>Roles</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/roles/roles.css') }}" />


</head>

<body>
    <header>
        <div class="header-container">
            <i class="header-icon">
                <svg width="full" height="full" viewBox="0 0 104 104" fill="none" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <rect width="104" height="104" fill="url(#pattern0_5_46)" />
                    <defs>
                        <pattern id="pattern0_5_46" patternContentUnits="objectBoundingBox" width="1" height="1">
                            <use xlink:href="#image0_5_46" transform="scale(0.00520833)" />
                        </pattern>
                        <image id="image0_5_46" width="192" height="192"
                            xlink:href="data:image/png;base64,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" />
                    </defs>
                </svg>

            </i>
            <h2 class="header-subtitle">InvectaMobile</h2>
            <div class="header-divider"></div>
            <h1 class="header-title">Roles</h1>
        </div>
        @if (session('success'))
            <div class="alert alert-success" id="alert-success">
                {{ session('success') }}
            </div>
        @endif
    </header>
    <main>
        <div class="roles-container ">
            <span class="btn-container-nuevo">
                <a class="btn-nuevo-rol" href="{{route('roles.nuevo-rol')}}">
                    Nuevo Rol
                </a>
            </span>

            <div class="table-responsive w-100">
                <table class="table table-striped  ">
                    <thead class="table-dark">
                        <tr>
                            <th class="px-3 text-center" scope="col">ID</th>
                            <th class="px-3 text-center" scope="col"></th>
                            <th class="px-3 text-center" scope="col">NOMBRE</th>
                            <th class="px-3 text-center" scope="col"></th>
                            <th class="px-3 text-center" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $rol)
                            <tr class=" table-rows">
                                <td class="px-3 text-center" scope="row">{{$rol->idRoles}}</td>
                                <td class="px-3 text-center"></td>
                                <td class="px-3 text-center">{{$rol->Nombre}}</td>
                                <td class="px-3 text-center">
                                    <form action="{{ route('roles.destroy', $rol->idRoles) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-eliminar">
                                            Eliminar
                                        </button>
                                    </form>

                                </td>
                                <td class="px-3 text-center">
                                    <a class="btn-detalle"
                                        href="{{route('roles.detalles-roles', ['idRoles' => $rol->idRoles])}}">
                                        Detalle
                                    </a>

                                </td>
                            </tr>


                        @endforeach

                    </tbody>
                </table>
            </div>
            <span class="btn-container-regresar">
                <a class="btn-regresar" href="{{route('usuarios')}}">
                    Regresar
                </a>
            </span>

        </div>
    </main>
    <footer>
        <!-- place footer here -->
        <div class="footer-container">
            <div class="footer-legal">
                <p>InvectaMobile 2024 &copy; Todos los derechos reservados.</p>
            </div>
        </div>
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