<?php
session_start();
$_SESSION['plaza'] = 'implementtaTijuanaA';
$plaza = $_SESSION['plaza'];
require "cnx/cnx.php";
$cnx = conexion($plaza);
$validar = false;
if (
    isset($_GET['tipo']) and !empty($_GET['tipo']) and isset($_GET['fecha']) and !empty($_GET['fecha'])
    and isset($_GET['cta']) and !empty($_GET['cta'])
) {

    $fecha = $_GET['fecha'];
    $cuenta = $_GET['cta'];
    $rol = $_GET['tipo'];

    


    if ($rol == '2' || $rol == '3' || $rol == '7' || $rol == '8') {
        $validar = true;
        if ($rol == '7') {
            $rol_gestion = 'Carta';
            $gestion = 'Carta Invitación';
            $tabla = 'registroCartaInvitacion';
            $idRegistro = 'idRegistroCartaInvitacion';
        } elseif ($rol == '3') {
            $rol_gestion = 'Abogado';
            $gestion = 'Registro Abogado';
            $tabla = 'RegistroAbogado';
            $idRegistro = 'IdRegistroAbogado';
        } elseif ($rol == '2') {
            $rol_gestion = 'Gestor';
            $gestion = 'Registro Gestor';
            $tabla = 'RegistroGestor';
            $idRegistro = 'IdRegistroGestor';
        } elseif ($rol == '8') {
            $rol_gestion = 'Cortes';
            $gestion = 'Registro Reductor';
            $tabla = 'RegistroReductores';
            $idRegistro = 'IdRegistroReductores';
        }
        $sql_cruce = "select a.$idRegistro as id from $tabla a
    where Cuenta='$cuenta' and convert(date,a.fechacaptura)='$fecha'";

        
        $cnx_sql_cruce = sqlsrv_query($cnx, $sql_cruce);

        $cruce = sqlsrv_fetch_array($cnx_sql_cruce);

        $id_gestion = $cruce['id'];
   
    }



    $_SESSION['rol_gestion'] = $_GET['tipo'];

    if ($rol == '2' || $rol == '3' || $rol == '7' || $rol == '8') {
        if ($rol == '7') {
            $sql_gestion = "select tabla.$idRegistro as id_registro, u.Nombre as nombreUsr,t.DescripcionTarea as descT,tabla.Cuenta,tabla.fechaCaptura
            from $tabla as tabla
            inner join AspNetUsers as u on tabla.IdAspUser=u.Id 
            inner join CatalogoTareas as t on tabla.idTarea=t.IdTarea
            where tabla.$idRegistro='$id_gestion'";
        } elseif ($rol == '3') {
            $sql_gestion = "select tabla.$idRegistro as id_registro, u.Nombre as nombreUsr,t.DescripcionTarea as descT,
            tabla.Cuenta,tabla.fechaCaptura,tabla.ObservacionesLegal,tabla.observacionPredio
            from $tabla as tabla
            inner join AspNetUsers as u on tabla.IdAspUser=u.Id 
            inner join CatalogoTareas as t on tabla.idTarea=t.IdTarea
            where tabla.$idRegistro='$id_gestion'";
        } else {
            $sql_gestion = "select tabla.$idRegistro as id_registro, u.Nombre as nombreUsr,t.DescripcionTarea as descT,tabla.Cuenta,tabla.observaciones,tabla.fechaCaptura
            from $tabla as tabla
            inner join AspNetUsers as u on tabla.IdAspUser=u.Id 
            inner join CatalogoTareas as t on tabla.idTarea=t.IdTarea
            where tabla.$idRegistro='$id_gestion'";
        }
        // echo $sql_gestion;
        // exit;
        $cnx_sql_gestion = sqlsrv_query($cnx, $sql_gestion);
    }

    $bd=$_SESSION['bd'];
    $id_usuario=$_SESSION['user'];
$plz=$_SESSION['plz'];

    if ($validar) {

        $info = sqlsrv_fetch_array($cnx_sql_gestion);
        // print_r($info);
        // exit;
        $sql_fotos = "
        SELECT 
            f.idRegistroFoto,
            f.cuenta,
            f.idAspUser,
            f.nombreFoto,
            f.idTarea,
            f.fechaSincronizacion,
            f.tipo,
            f.urlImagen,
            f.fechaCaptura 
        FROM 
            $tabla a
        INNER JOIN 
            [dbo].Registrofotomovil f ON a.cuenta = f.cuenta 
        WHERE 
            a.$idRegistro = '$id_gestion'
            AND CONVERT(DATE, a.fechacaptura) = CONVERT(DATE, f.fechacaptura)
        ";

        // echo $sql_fotos;
        // exit;

        $cnx_sql_fotos = sqlsrv_query($cnx, $sql_fotos);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Fotos de la gestion</title>
    <link rel="icon" href="img/implementtaIcon.png">

    <nav class="navbar navbar-expand-lg navbar-dark">
        <a href="#"><img src="img/logoImplementtaHorizontal.png" width="250" height="82" alt=""></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="nav-item nav-link text-dark" href="PaginaCristian"><i class="fa-solid fa-house"></i> Inicio </a>
            </ul>
        </div>
    </nav>
</head>

<body>

    <?php
    if (!$validar) {
    ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error de parametros',
                text: 'no se esta recibiendo los datos necesarios para continuar, vuelva a intentarlo y si el problema persiste, comuniquese con soporte',
                showCancelButton: false, // Mostrar el botón de cancelar
                confirmButtonText: 'Regresar', // Cambiar el texto del botón de confirmar
                allowOutsideClick: false, // Evitar que se cierre haciendo clic afuera
                allowEscapeKey: false, // Evitar que se cierre presionando Esc
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si se hace clic en "Regresar", volver a la página anterior
                    window.history.back();
                }
            });
        </script>
        <?php
    } else {
        if (!sqlsrv_has_rows($cnx_sql_gestion)) { ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Dato no encontrado',
                    text: 'No se encontro ninguna coincidencia con los datos de esta gestión',
                    showCancelButton: false, // Mostrar el botón de cancelar
                    confirmButtonText: 'Regresar', // Cambiar el texto del botón de confirmar
                    allowOutsideClick: false, // Evitar que se cierre haciendo clic afuera
                    allowEscapeKey: false, // Evitar que se cierre presionando Esc
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si se hace clic en "Regresar", volver a la página anterior
                        window.history.back();
                    }
                });
            </script>

        <?php } else { ?>

            <div class="container col-md-11">
                <hr>
                <div class="row justify-content-center">
                    <!-- Aquí puedes agregar tus cards -->
                    <div class="col-md-2 text" style="border-right: 1px solid #ccc; border-left: 1px solid #ccc;">
                        <h3><i class="fa-solid fa-circle-info"></i> Datos de la Gestión</h3>
                        <hr>
                        <div>
                            <p><i class="fa-solid fa-file-circle-question"></i> Tipo: <?= $gestion ?></p>
                            <p><i class="fa-solid fa-calendar-days"></i> Fecha: <?= $info['fechaCaptura']->format('Y-m-d H:i:s') ?></p>
                            <p><i class="fa-solid fa-house-chimney-window"></i> Cuenta: <?= $info['Cuenta'] ?></p>
                            <p><i class="fas fa-user"></i> Gestor: <?= utf8_encode($info['nombreUsr']) ?></p>
                            <p><i class="fas fa-tasks"></i> Tarea: <?= utf8_encode($info['descT']) ?></p>
                            <?php if ($rol == '8' || $rol == '2') { ?>
                                <p><i class="fas fa-comment"></i> Observaciones: <?= utf8_encode($info['observaciones']) ?></p>
                            <?php } ?>
                            <?php if ($rol == '3') { ?>
                                <p><i class="fa-solid fa-gavel"></i> Observaciones Legal: <?= utf8_encode($info['ObservacionesLegal']) ?></p>
                                <p><i class="fas fa-comment"></i> Observaciones Predio: <?= utf8_encode($info['observacionPredio']) ?></p>
                            <?php } ?>
                            <!-- Puedes agregar más líneas de información aquí -->
                        </div>
                        <div class="text-center d-block">

                            <a href="gestiones.php?cta=<?= trim($info['Cuenta']) ?>&tipo=<?= $rol ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fa-solid fa-eye"></i> Gestiones de la cuenta</a>
                            
                            <a href="https://gallant-driscoll.198-71-62-113.plesk.page/Visor-Cuenta/php/gestiones/login.php?bd=<?=$bd?>&plz=<?=$plz?>&rol=<?=$rol_gestion?>&registro=<?=$id_gestion?>&cuenta=<?=$cuenta?>&id_usuario=<?=$id_usuario?>" target="_blank" class="btn btn-warning btn-sm"><i class="fa-solid fa-eye"></i> Editar gestión</a>
                        </div>
                    </div>
                    &nbsp;
                    <div class="col-md-9 text-center" style="border-right: 1px solid #ccc; border-left: 1px solid #ccc;">
                        <h3><i class="fa-regular fa-images"></i> Fotos de la gestión</h3>
                        <hr>
                        <?php if (!sqlsrv_has_rows($cnx_sql_fotos)) { ?>
                            <div class="alert alert-danger" role="alert">
                                No se encontro ninguna foto para esta gestión!.
                            </div>
                        <?php } else { ?>
                            <div class="row justify-content-center">
                                <?php while ($fotos = sqlsrv_fetch_array($cnx_sql_fotos)) { ?>
                                    <div class="col-md-2 mb-3">
                                        <div class="card">
                                            <!-- <div class="card-header text-center" style="padding: 0;">
                                                <button class="btn btn-block btn-lg btn-sm btnDelete" data-gestion="<?= $id_gestion ?>" data-id="<?= $fotos['idRegistroFoto'] ?>" data-nombre="<?= $fotos['nombreFoto'] ?>" data-cuenta="<?= $fotos['cuenta'] ?>" data-idaspuser="<?= $fotos['idAspUser'] ?>" data-idtarea="<?= $fotos['idTarea'] ?>" data-fechacaptura="<?= $fotos['fechaCaptura']->format('Y-m-d H:i:s') ?>" data-tipo="<?= $fotos['tipo'] ?>" data-urlimagen="<?= $fotos['urlImagen'] ?>" data-activo="<?= $fotos['Activo'] ?>" data-fechasincronizacion="<?= $fotos['fechaSincronizacion']->format('Y-m-d H:i:s') ?>" style="margin:0; transition: background-color 0.3s;">
                                                    Eliminar <i class="fa-solid fa-trash-can" style="color: red;"></i>
                                                </button>
                                            </div> -->
                                            <img src="<?= $fotos['urlImagen'] ?>" class="card-img-top" style="height: 150px;">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= $fotos['tipo'] ?></h5>
                                                <p class="card-text"><?= $fotos['fechaCaptura']->format('Y-m-d H:i:s') ?></p>
                                            </div>
                                            <div class="card-footer text-center" style="padding: 0;">
                                                <a href="<?= $fotos['urlImagen'] ?>" target="_blank" class="btn btn-block btn-lg btn-sm" style="margin:0; transition: background-color 0.3s;">
                                                    Visualizar <i class="fas fa-arrow-circle-right" style="color: blue;"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>

                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>

    <?php }
    } ?>

    <hr><br>
    <div style="text-align:center;">
        <button class="btn btn-dark btn-sm" id="btnCerrarVentana"><i class="fas fa-times"></i> Cerrar Ventana</button>
    </div>
    <script>
        document.getElementById('btnCerrarVentana').addEventListener('click', function() {
            // Cerrar la ventana actual
            window.close();
        });
    </script>

    <nav class="navbar bottom navbar-expand-lg">
        <span class="navbar-text" style="font-size:12px;font-weigth:normal;color: #7a7a7a;">
            Implementta ©<br>
            Estrategas de México <i class="far fa-registered"></i><br>
            Centro de Inteligencia Informática y Geografía Aplicada CIIGA
            <hr style="width:105%;border-color:#7a7a7a;">
            Created and designed by <i class="far fa-copyright"></i> <?php echo date('Y') ?> Estrategas de México<br>
        </span>
        <hr>
        <span class="navbar-text" style="font-size:12px;font-weigth:normal;color: #7a7a7a;">
            Contacto:<br>
            <i class="fas fa-phone-alt"></i> Red: 187<br>
            <i class="fas fa-phone-alt"></i> 66 4120 1451<br>
            <i class="fas fa-envelope"></i> sistemas@estrategas.mx<br>
        </span>
        <ul class="navbar-nav mr-auto">
            <br><br><br><br><br><br><br><br>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <a href="#"><img src="img/logoImplementta.png" width="155" height="150" alt=""></a>
            <a href="http://estrategas.mx/" target="_blank"><img src="img/logoTop.png" width="200" height="85" alt=""></a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </form>
    </nav>
    <!-- <script src="gestion.js"></script> -->

</body>

</html>
<?php

?>