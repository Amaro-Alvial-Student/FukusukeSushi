<?php
session_start(); 
if (isset($_SESSION['usuario_id']) && ($_SESSION['perfil'] == 'Admin' or $_SESSION['perfil'] == 'Dueno')) {
    header('Location: adminPersonas.php');
    exit();
}
if (isset($_SESSION['usuario_id']) && $_SESSION['perfil'] == 'Despachador') {
    header('Location: ordenesDespacho.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fukusuke Sushi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="./css/styles.css">
    <script src="./js/pagPrincipal.js"></script>
    <script src="./js/carrito.js"></script>
    <script src="./js/modalProducto.js"></script>
    <?php
    if (isset($_SESSION['usuario_id'])) {
        echo "<script>
            let idCliente = '" . $_SESSION['usuario_id'] . "';
            setCarrito(idCliente);
        </script>";
    }
    ?>
</head>

<body>
    <!-- Navbar pricipal, inicio de sesión y más opciones (hay que confirmar cuáles se necesitan). -->
    <nav class="navbar navbar-expand-sm pt-0 pb-0" id="navbar-principal">
        <div class="container-fluid p-2" id="navbar-container">
            <div class="container-logo">
                <a id="logotype" href="./index.php">
                    <img src="./img/logo/FUKUSUKE_LOGO.png" class="img-fluid" alt="Logo de la empresa" style="height: 60px">
                </a>
            </div>
            <ul class="navbar-nav d-flex align-items-center d-flex flex-row">
                <li class="nav-item">
                    <button class="botones-navbar pb-1" type="button" data-bs-toggle="modal" data-bs-target="#horariosModal">
                        Horarios
                    <button>
                </li>
                <li class="nav-item me-3">
                    <button class="botones-navbar pb-1" type="button">
                        Local
                    </button>
                </li>
                <div class="d-none d-sm-flex">
                    <?php if (!isset($_SESSION['usuario_id'])): ?>
                        <!-- Mostrar si no hay sesión activa -->
                        <li class="nav-item">
                            <button type="button" id="login-button" data-bs-toggle="modal" data-bs-target="#loginModal">
                                Iniciar Sesión
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" id="signup-button" data-bs-toggle="modal" data-bs-target="#regModal">
                                Crear Cuenta 
                            </button>
                        </li>
                    <?php else: ?>
                        <!-- Mostrar si hay sesión activa -->
                        <li class="nav-item dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="#" onclick="MiPerfil(idCliente)">Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
                                <li><a class="dropdown-item" href="#" onclick="ModalReclamo()">Reclamo</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </div>

                <div class="d-sm-none">
                    <?php if (!isset($_SESSION['usuario_id'])): ?>
                        <!-- Mostrar si no hay sesión activa -->
                        <li class="nav-item dropdown me-2 position-relative">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="loginMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="./img/user_icon.png" height="25px">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end position-absolute" aria-labelledby="loginMenu">
                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#loginModal">Iniciar Sesión</a></li>
                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#regModal">Crear Cuenta</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Mostrar si hay sesión activa -->
                        <li class="nav-item dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="./img/user_icon.png" height="25px">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end position-absolute" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="#" onclick="MiPerfil(idCliente)">Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
                                <li><a class="dropdown-item" href="#" onclick="ModalReclamo()">Reclamo</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </div>
            </ul>
        </div>
    </nav>

    <!-- Aviso de iniciar sesión (debería eliminarse/cambiar cuando se inicia sesión) -->
    <?php if (!isset($_SESSION['usuario_id'])): ?>
        <div class="container-fluid d-flex justify-content-center" id="aviso-carrusel" style="text-align: center">
            Te invitamos a iniciar sesión para disfrutar de nuestra carta.
        </div>
    <?php else: ?>
        <!-- Mostrar si hay sesión activa -->
        <div class="container-fluid d-flex justify-content-center" id="aviso-carrusel">
            ¡¡Aprovecha nuestras promociones!!
        </div>
    <?php endif; ?>

    <!-- Carrusel -->
    <div>
        <div id="demo" class="carousel slide" data-bs-ride="carousel">

            <div class="carousel-indicators">
                <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="./img/banner/Banner2.webp" class="img" id="img-sushi-bienvenida">
                </div>
                <div class="carousel-item">
                    <img src="./img/banner/Banner3.webp" class="img" id="img-sushi-bienvenida">
                </div>
                <div class="carousel-item">
                    <img src="./img/banner/Banner1.webp" class="img" id="img-sushi-bienvenida">
                </div>
            </div>
        
            <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
    
    <!--Offcanvas (Carrito)-->
    <div class="offcanvas offcanvas-end" id="carrito" value="">
        <div class="offcanvas-header">
            <h1 class="offcanvas-title">Carrito</h1>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <h3 class="text-center">Inicia sesión 👤</h3>
            <h5 class="text-center text-muted">Para poder usar el carrito debes iniciar sesión.</h5>
        </div>
    </div>

    <!-- Pide Ya -->
    <div class="container-fluid d-flex justify-content-center mt-0 mb-2">
        <button id="pideya-button">
            ¡Pide Ya!<br>
        </button>
        <script>
            document.getElementById("pideya-button").addEventListener("click", function() {
            document.getElementById("productos-section").scrollIntoView({
                behavior: 'smooth',
                block: 'start'
                });
            });
            </script>
    </div>

    <!-- Scroll pantallas pequeñas -->
    <div class="container-fluid d-md-none mt-2 d-flex" id="categoria-scroll" name="categoria"></div>

    <!-- Container con productos y categorías -->
    <div class="container-fluid" id="productos-section" style="height: auto">
        <div class="row d-flex" id="row-productos">
            <div class="col-sm-12 col-md-10" id="div-scroll-productos">
                <div class="ps-2 pe-3" id="scroll-productos"></div>
            </div>
            <div class="col-2 d-none d-md-flex flex-column p-0 pe-2" style="height: 60%">
                <h5 class="d-flex d-flex justify-content-center pe-3">Categorías</h5>
                <div class="mt-2" id="categoria-scroll2" name="categoria"></div>

                <!-- Botón para mostrar el carrito -->

                <div class="d-flex justify-content-center mt-3">
                    <button id="carrito-button" type="button" data-bs-toggle="offcanvas" data-bs-target="#carrito" onclick="actualizarCarrito();">
                        <img src="./img/carrito.png" style="width: 45px">
                        <span id="cantidadCarrito">0</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón de carrito para pantallas pequeñas -->
    <button class="d-md-none" id="fixed-carrito-button" type="button" data-bs-toggle="offcanvas" data-bs-target="#carrito" onclick="actualizarCarrito();">
        <img src="./img/carrito.png" style="width: 45px">
        <span id="cantidadCarrito2">0</span>
    </button>

    <!-- Modal del producto seleccionado-->
    <div class="modal fade" id="productModal" value="" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel" style="font-weight: bold">Nombre del Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Imagen del producto (lado izquierdo) -->
                        <div id="productModalImage" class="col-md-6 d-flex align-items-center justify-content-center">
                            <img src="https://via.placeholder.com/300" alt="Imagen del Producto" class="img-fluid rounded">
                        </div>
                        
                        <!-- Información del producto (lado derecho) -->
                        <div class="col-md-6">
                            <p id="productModalDesc" style="height: 80%; margin: 0 0; font-size: 1.1rem">Descripción detallada del producto. Aquí puedes añadir más detalles relevantes.</p>
                            
                            <!-- Control de cantidad -->
                            <div class="quantity-container d-flex justify-content-center align-items-end row" style="height: 20%">
                                <div class="row">
                                    <div class="quantity-display col-3 col-sm-4 col-md-12 col-lg-4 col-xl-4" id="productModalPrecio" value="" style="text-align: center; font-size: 22px; font-weight: bold">222</div>
                                    <div class="col-1 col-sm-1 col-md-2 col-lg-1 d-flex justify-content-center align-items-center p-0">
                                        <button type="button" class="quantity-button" onclick="updateQuantity(-1)">
                                            <img src="./img/signo_resta.png" style="width: 24px">
                                        </button>
                                    </div>
                                    <div class="col-1 quantity-display d-flex justify-content-center p-0" id="quantity" style="font-size: 1.2rem;">1</div>
                                    <div class="col-1 col-sm-1 col-md-2 col-lg-1 d-flex justify-content-center align-items-center p-0">
                                        <button type="button" class="quantity-button" onclick="updateQuantity(1)">
                                            <img src="./img/signo_mas.png" style="width: 24px;">
                                        </button>
                                    </div>
                                    <div class="col-6 col-sm-5 col-md-6 col-lg-5 d-flex justify-content-center align-items-center ps-1 pe-1">
                                        <button type="button" id="agregar-button-modal" data-bs-dismiss="modal" onclick="
                                        <?php echo isset($_SESSION['usuario_id']) ? 'agregarDetalleCarrito();' : ''; ?>" 
                                        <?php echo !isset($_SESSION['usuario_id']) ? 'data-bs-toggle="modal" data-bs-target="#loginModal"' : ''; ?>>
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <!-- Botón de agregar al carrito -->
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal inicio de sesión -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
    
                <div class="modal-body">
                    <form id="login-form">
                        <div class="d-flex flex-column align-items-center">
                            <div class="mb-3">
                                <label for="loginNombreUsuario" class="form-label">Nombre de Usuario</label>
                                <input type="text" style="width: 400px" class="form-control" id="loginNombreUsuario" placeholder="Ingrese nombre de usuario." name="nombreUsuario" required>
                            </div>
                            <div class="mb-3">
                                <label for="loginPass" class="form-label">Contraseña</label>
                                <input type="password" style="width: 400px" class="form-control" id="loginPass" placeholder="Ingrese su contraseña." name="pass" required>
                            </div>
                            <div class="mt-2 mb-3">
                                <button type="Button" class="btn-modals" id="login-button-modal" onclick="IniciarSesion()">Iniciar Sesión</button>
                            </div>
                            <div>
                                Si no tienes una cuenta aún,
                                <a href="#" data-bs-toggle="modal" data-bs-target="#regModal">¡Regístrate!</a>
                                .
                            </div>
                        </div>
                    </form>
                </div>
    
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de registro -->
    <div class="modal fade" id="regModal" tabindex="-1" aria-labelledby="regModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="regModalLabel">Registrar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"> <!-- Se abre el Modal Body -->
                    <form id="regForm">
                        <div class ="row"> <!-- Se abre fila 1 del modal -->
                            <div class="mb-3 col-12">
                                <label for="regRun" class="form-label">RUN</label>
                                <input type="text" class="form-control" id="regRun">
                            </div>
                            <div class="mb-3 col-12">
                                <label for="regFechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="regFechaNacimiento">
                            </div>
                        </div> <!-- Cierre de la Fila 1 del modal -->
                        <div class = "row"> <!-- Se abre fila 2 del modal -->
                            <div class="mb-3 col-12">
                                <label for="regNombre" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="regNombre">
                            </div>

                            <div class="mb-3 col-12">
                                <label for="regSexo" class="form-label">Sexo</label>
                                <select class="form-select" id="regSexo">
                                    <option value="M">M</option>
                                    <option value="F">F</option>
                                </select>
                            </div> 
                        </div> <!-- Cierre de la Fila 2 del modal -->
                        <div class="row"> <!-- Se abre fila 3 del modal -->
                            <div class="mb-3 col-12">
                                <label for="regNombreUsuario" class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="regNombreUsuario">
                            </div>
                            <div class="mb-3 col-12">
                                <label for="regPass" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="regPass">
                            </div>
                        </div> <!-- Cierre de la Fila 3 del modal -->
                        <div class= "row"> <!-- Se abre fila 4 del modal -->
                            <div class="mb-3 col-xxl-7">
                                <label for="regTelefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="regTelefono">
                            </div>
                        </div> <!-- Cierre de la Fila 4 del modal -->
                        <div class = "row"> <!-- Se abre fila 5 del modal -->
                            <div class="mb-3 col-12">
                                <label for="regEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="regEmail">
                            </div>
                        </div> <!-- Cierre de la Fila 5 del modal -->
                        <div class="row"> <!-- Se abre fila 6 del modal -->
                            <div class="mb-3 col-12">
                                <label for="regRegion" class="form-label">Región</label>
                                <select class="form-select" name="region" id="regRegion"></select>
                            </div>
                        </div> <!-- Cierre de la Fila 6 del modal -->
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label for="regProvincia" class="form-label">Provincia</label>
                                <select class="form-select" name="provincia" id="regProvincia"><option value="defaultProvincia">Seleccione la Provincia</option></select>
                            </div>
                        </div> <!-- Cierre de la Fila 6 del modal -->
                        <div class="row"> <!-- Se abre fila 7 del modal -->
                            <div class="mb-3 col-12">
                                <label for="regComuna" class="form-label">Comuna</label>
                                <select class="form-select" name="comuna" id="regComuna"><option value="defaultComuna">Seleccione la Comuna</option></select>
                            </div>
                        </div> <!-- Cierre de la Fila 7 del modal -->
                        <div class="row"> <!-- Se abre fila 8 del modal -->
                            <div class="mb-3">
                                <label for="regDireccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="regDireccion">
                            </div>
                        </div> <!-- Cierre de la Fila 8 del modal -->
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="btn-modals2" data-bs-dismiss="modal">Cancelar</button>
                            <button id="confirmarRegBtn"type="button" class="btn-modals" onclick="RegUsuario()">Confirmar</button>
                        </div>
                        <div class="d-flex justify-content-center">
                            ¿Ya tienes una cuenta?,
                            <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">¡Ingresa!</a>
                            .
                        </div>
                    </form>
                </div> <!-- Cierre del Modal Body -->
            </div>
        </div>
    </div>

    <!-- Modal de Editar Perfil -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"> <!-- Se abre el Modal Body -->
                    <form id="editForm">
                        <div class ="row"> <!-- Se abre fila 1 del modal -->
                            <div class="mb-3 col-12">
                                <label for="editRun" class="form-label">RUN</label>
                                <input type="text" class="form-control" id="editRun" disabled>
                            </div>
                            <div class="mb-3 col-12">
                                <label for="editFechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="editFechaNacimiento" disabled>
                            </div>
                        </div> <!-- Cierre de la Fila 1 del modal -->
                        <div class = "row"> <!-- Se abre fila 2 del modal -->
                            <div class="mb-3 col-12">
                                <label for="editNombre" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="editNombre">
                            </div>
                            <div class="mb-3 col-12">
                                <label for="editSexo" class="form-label">Sexo</label>
                                <select class="form-select" id="editSexo">
                                    <option value="M">M</option>
                                    <option value="F">F</option>
                                </select>
                            </div> 
                        </div> <!-- Cierre de la Fila 2 del modal -->
                        <div class="row"> <!-- Se abre fila 3 del modal -->
                            <div class="mb-3 col-12">
                                <label for="editNombreUsuario" class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" id="editNombreUsuario" disabled>
                            </div>
                            <div class="mb-3 col-12">
                                <label for="editPass" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="editPass">
                            </div>
                        </div> <!-- Cierre de la Fila 3 del modal -->
                        <div class= "row"> <!-- Se abre fila 4 del modal -->
                            <div class="mb-3 col-12">
                                <label for="editTelefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="editTelefono">
                            </div>
                        </div> <!-- Cierre de la Fila 4 del modal -->
                        <div class = "row"> <!-- Se abre fila 5 del modal -->
                            <div class="mb-3 col-12">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail">
                            </div>
                        </div> <!-- Cierre de la Fila 5 del modal -->
                        <div class="row"> <!-- Se abre fila 6 del modal -->
                            <div class="mb-3 col-12">
                                <label for="editRegion" class="form-label">Región</label>
                                <select class="form-select" name="region" id="editRegion"></select>
                            </div>
                        </div> <!-- Cierre de la Fila 6 del modal -->
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label for="editProvincia" class="form-label">Provincia</label>
                                <select class="form-select" name="provincia" id="editProvincia"><option value="defaultProvincia">Seleccione la Provincia</option></select>
                            </div>
                        </div> <!-- Cierre de la Fila 6 del modal -->
                        <div class="row"> <!-- Se abre fila 7 del modal -->
                            <div class="mb-3 col-12">
                                <label for="editComuna" class="form-label">Comuna</label>
                                <select class="form-select" name="comuna" id="editComuna"><option value="defaultComuna">Seleccione la Comuna</option></select>
                            </div>
                        </div> <!-- Cierre de la Fila 7 del modal -->
                        <div class="row"> <!-- Se abre fila 8 del modal -->
                            <div class="mb-3">
                                <label for="editDireccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="editDireccion">
                            </div>
                        </div> <!-- Cierre de la Fila 8 del modal -->
                    </form>
                </div> <!-- Cierre del Modal Body -->
                <div class="modal-footer">
                    <button type="button" class="btn-modals2" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn-modals" onclick="UpdMiPerfil()">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Horarios -->
    <div class="modal fade" id="horariosModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body d-flex flex-column align-items-center">
                    <div class="row">
                        <p>
                            LU, MA, MI ,JU
                        </p>
                    </div>
                    <div class="row">
                        <p>
                            11:00 - 20:00
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Reclamo -->
    <div class="modal fade" id="reclamoModal" tabindex="-1" aria-labelledby="reclamoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reclamoModalLabel">Reclamo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"> <!-- Se abre el Modal Body -->
                    <form id="reclamoForm">
                        <div class="mb-3">
                            <label for="reclamoTitulo" class="form-label">Titulo:</label>
                            <input type="text" class="form-control" id="reclamoTitulo">
                        </div>
                        <div class="mb-3">
                            <label for="reclamoDescripcion" class="form-label">Descripción:</label>
                            <textarea class="form-control" id="reclamoDescripcion" rows="3"></textarea>
                        </div>
                    </form>
                </div> <!-- Cierre del Modal Body -->
                <div class="modal-footer">
                    <button type="button" class="btn-modals2" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn-modals" onclick="Reclamo(idCliente)">Enviar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="d-none d-md-flex">
        <div class="container-fluid mt-5" id="footer-container" style="background-color: #F2F1F1; height: 150px">
            <div class="row" style="height: 100%">
                <div class="col-3 pt-4 pb-4 h-100">
                    <img src="./img/logo/FUKUSUKE_LOGO.png" height="100%">
                </div>
                <div class="col-4 ms-auto d-flex flex-column justify-content-start align-items-center mt-3">
                    <div class="mb-2" style="color: black;">
                        Nuestras redes:
                    </div>
                    <div class="d-flex justify-content-center align-items-center mt-2 gap-3">
                        <!-- Ejemplo de contenidos adicionales -->
                        <a id="facebook" href="https://www.facebook.com/?locale=es_LA" target="_blank" rel="noopener noreferrer"><img src="./img/redes/facebook-icon.png" style="width: 60px"></a>
                        <a id="instagram" href="https://www.instagram.com" target="_blank" rel="noopener noreferrer"><img src="./img/redes/instagram-icon.png" style="width: 60px"></a>
                        <a id="twitter" href="https://x.com/?lang=es" target="_blank" rel="noopener noreferrer"><img src="./img/redes/twitter-icon.png" style="width: 60px"></a>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center" style="color: black; background-color: #F2F1F1; height: 20%; text-align: center">
                @ COPYRIGHT 2024 FukusukeSushi
            </div>
        </div>
    </footer>

    <!--https://www.svgrepo.com/collection/dazzle-line-icons/, íconos con licencia libre. -->

</body>
</html>
<script src="./js/pagPrincipalPos.js"></script>