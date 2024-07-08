<?php
require_once("../config/config.php");
session_start();

if (!isset($_SESSION['sesion_personal'])) {
    header("Location: ./iniciar_sesion.php");
}

// Crear una conexión
$con = mysqli_connect($db_hostname, $db_username, $db_password, $db_name);
    
// verificar connection con la BD
if (mysqli_connect_errno()) :
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
else:
    $arreglo_de_productos=[];
    $result = mysqli_query($con, "SELECT p.id_producto,p.nombre_producto,p.precio_producto,p.cantidad_disponible,c.cantidad_seleccionada,c.id_carrito FROM carrito as c INNER JOIN producto as p ON c.id_producto = p.id_producto WHERE c.id_usuario=".$_SESSION['sesion_personal']['id'].";");
    $n_productos=mysqli_num_rows($result);
    while ($row = mysqli_fetch_array($result)):
        array_push($arreglo_de_productos, array(
            "id_carrito"=>$row['id_carrito'],
            "id"=>$row['id_producto'],
            "nombre"=>$row['nombre_producto'],
            "precio"=>$row['precio_producto'],
            "disponibles"=>$row['cantidad_disponible'],
            "cantidad"=>$row['cantidad_seleccionada'],
        ));
    endwhile;

    // cerrar conexión
    mysqli_close($con);
endif;
$suma=0;
$arreglo_para_comprar=array();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include "head_html.php" ?>
    <title>Carrito de compras</title>
    <!-- icono -->
    <link rel="shortcut icon" href="../img/logo.jpg">
    <!-- normalize -->
    <link rel="preload" href="../css/normalize.css" as="style">
    <link rel="stylesheet" href="../css/normalize.css">
    <!-- estilos -->
    <link rel="preload" href="../css/estilo_generico.css" as="style">
    <link rel="preload" href="../css/styles-carrito.css" as="style">
    <link rel="stylesheet" href="../css/estilo_generico.css">
    <link rel="stylesheet" href="../css/styles-carrito.css">
    <!-- JS -->
    <script type="text/javascript" src="../js/comprar_agregarcarrito.js"></script>
</head>

<body class="container">
    <!-- barra de navegación -->
    <header>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <!-- responsividad del header, marca -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!-- marca -->
                    <a class="navbar-brand" href="../index.php">Tienda</a>
                </div>

                <div class="collapse navbar-collapse" id="myNavbar">
                    <!-- menú izquierdo-->
                    <ul class="nav navbar-nav">
                        <li><a href="../index.php">Lista de productos</a></li>
                    </ul>
                    <!-- menú derecho -->
                    <ul class="nav navbar-nav">
                        <p class="navbar-text">Sesión iniciada como <a href="../php/perfil.php"
                                class="navbar-link"><u><?=$_SESSION['sesion_personal']['nombre']?></u></a></p>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if ($_SESSION['sesion_personal']['super']==1): ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-haspopup="true" aria-expanded="false">Modo dios 😎 <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../php/consultar_historial.php"><span
                                            class="glyphicon glyphicon-list"></span> Consultar historial</a></li>
                                <li><a href="../php/modificar_productos.php"><span
                                            class="glyphicon glyphicon-cog"></span> Modificar productos</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <li>
                            <a href="../php/cerrar_sesion.php"><span class="glyphicon glyphicon-log-out"></span> Cerrar
                                sesión</a>
                        </li>
                        <li class="active">
                            <a href="#"><span class="glyphicon glyphicon-shopping-cart"></span> Carrito
                                de compras</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- fin barra de navegación -->

    <?php if ($n_productos==0): ?>
    <h1 class="h1">TU CARRITO ESTA VACIO</h1>
    <?php else: ?>
    <h1 class="h1">CARRITO DE COMPRAS</h1>
    <div class="table-responsive">
        <table class="table table-hover">
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Disponibles</th>
                <th>Cantidad seleccionada</th>
                <th>Precio</th>
                <th>Total individual</th>
            </tr>

            <?php foreach ($arreglo_de_productos as $producto): 
            // [0]=
            array_push($arreglo_para_comprar,($producto["cantidad"].",".$producto["id"].""));
            ?>
            <tr>
                <td>
                    <img src="../img/productos/<?= $producto["id"] ?>.png" alt="producto <?= $producto["nombre"] ?>"
                        class="imagen">
                </td>
                <td>
                    <span class="texto-informativo"><?= $producto["nombre"] ?></span>
                </td>
                <td>
                    <span class="texto-informativo"><?= $producto["disponibles"] ?></span>
                </td>
                <td>
                    <div class="btn-group">
                        <a href="modificar_producto_carrito.php?signo=0&id_carrito=
                            <?=$producto['id_carrito']?>&disp=<?=$producto["disponibles"]?>&cant=
                            <?=$producto["cantidad"]?>" class="btn btn-default">-
                        </a>

                        <button type="submit" class="btn btn-default disabled"><?= $producto["cantidad"] ?></button>

                        <a href="modificar_producto_carrito.php?signo=1&
                            id_carrito=<?=$producto['id_carrito']?>&disp=<?=$producto["disponibles"]?>
                            &cant=<?=$producto["cantidad"]?>" class="btn btn-default">
                            +
                        </a>
                    </div>
                </td>
                <td>
                    <span
                        class="texto-informativo">$<?= number_format(floatval($producto["precio"]), 2, '.', ',') ?></span>
                </td>
                <td>
                    <span class="texto-informativo">
                        $<?= number_format(floatval(floatval($producto["precio"])*((int) $producto["cantidad"])), 2, '.', ',') ?>
                    </span>
                </td>
            </tr>
            <?php $suma+=floatval(floatval($producto["precio"])*((int) $producto["cantidad"])); ?>
            <?php endforeach; ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <th>Total</th>
                <td>$<?= number_format(floatval(floatval($suma)), 2, '.', ',') ?></td>
            </tr>
        </table>
    </div>
    <script>
    var arreglo_de_productos = JSON.parse('<?= json_encode($arreglo_para_comprar); ?>');
    </script>
    <div class="posiciona-botones">
        <a href="vaciar_carrito.php"><input type="submit" class="btn btn-default boton" value="Vaciar carrito"></a>
        <input type="submit" class="btn btn-default boton" value="Comprar todo"
            onclick="enviarAPantallaDeCompraMuchos(arreglo_de_productos)">
    </div>
    <?php endif ?>
</body>

</html>
