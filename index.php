<?php
session_start();

$_SESSION['plaza'] = 'implementtaTijuanaA';
$plaza = $_SESSION['plaza'];
require "cnx/cnx.php";
$cnx = conexion($plaza);

$_SESSION['bd'] = $plaza;
$_SESSION['user'] ='1';
$_SESSION['plz'] =1;




?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestiones</title>
    <link rel="icon" href="img/icon.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <!-- DataTables CSS y JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css" id="theme-styles">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
    <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Incluye CSS de Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Incluye JS de Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>



    <style>
        /* Personaliza la posición y el estilo de Toastr */
        #toast-container>.toast {
            background-color: #dc3545;
            color: #ffffff;
        }

        body {
            /* background-image: url(../img/back.jpg); */
            background-repeat: repeat;
            background-size: 100%;
            background-attachment: fixed;
            overflow-x: hidden;
        }

        body {
            font-family: sans-serif;
            font-style: normal;
            font-weight: normal;
            width: 100%;
            height: 100%;
            margin-top: -2%;
            padding-left: 30px;
            padding-right: 30px;
        }

        /* Estilo para el scrollbar superior */
        #tblreporte_wrapper .dataTables_scrollBody {
            overflow-x: auto;
            transform: rotateX(180deg);
            /* Rotar el scrollbar horizontal */
        }

        #tblreporte_wrapper .dataTables_scrollBody table {
            transform: rotateX(180deg);
            /* Rotar la tabla */
        }

        /* Estilo para el scrollbar inferior */
        #tblreporte_wrapper .dataTables_scrollBody2 {
            overflow-x: auto;
        }
    </style>
</head>
<br>
<nav class="navbar navbar-expand-lg navbar-light">
    <a href="#"><img src="img/logoImplementtaHorizontal.png" width="250" height="82" alt=""></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


            <a class="nav-item nav-link" href="logout.php"> Salir <i class="fas fa-sign-out-alt"></i></a>

        </ul>

    </div>
</nav>

<body>
    <div class="text-center">
        <h4 style="text-shadow: 0px 0px 2px #717171;"><img width="50" height="50" src="https://img.icons8.com/external-smashingstocks-flat-smashing-stocks/66/external-Feedback-testing-services-smashingstocks-flat-smashing-stocks-5.png" alt="external-Feedback-testing-services-smashingstocks-flat-smashing-stocks-5" />Reporte de Gestiones</h4>

    </div>
    <hr>

    <div id="tblreporte_wrapper" class="container-fluid">


        <div class="d-flex align-items-center justify-content-center mb-3">

            <div class="col-sm-2">
                <div class="form-group">
                    <label for="tipo" class="form-label">Fechas:</label>
                    <input type="text" class="form-control form-control-sm" id="rangof" value="" />
                </div>
            </div>
            &nbsp;&nbsp;&nbsp;
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="tipo" class="form-label">Tipo de Reporte:</label>
                    <select name="tipo" id="tipo" class="form-control form-control-sm" data-placeholder="Tipo de Reporte">
                        <option value="1" selected><i class="fa fa-users"></i>Todas</option>
                        <option value="2">Gestor</option>
                        <option value="3">Abogado</option>
                        <option value="4">Call Center</option>
                        <option value="5">SEPOMEX</option>
                        <option value="6">Pregrabadas</option>
                        <option value="7">Carta Iinvitación</option>
                        <option value="8">REDUCTORES</option>
                    </select>
                </div>
            </div>
            &nbsp;&nbsp;&nbsp;
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="cuenta" class="form-label">Cuenta</label>
                    <select class="form-control form-control-sm" id="cuenta" name="cuenta" data-placeholder="Cuenta">
                    </select>
                </div>
            </div>
            &nbsp;&nbsp;&nbsp;
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="gestor" class="form-label">Gestor</label>
                    <select class="form-control form-control-sm" id="gestor" name="gestor" data-placeholder="Gestor"></select>
                </div>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <div class="form-group mb-0">
                <button class="btn btn-primary btn-sm" id="buscar">Buscar</button>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <div class="form-group mb-0">
                <button class="btn btn-outline-success btn-sm" id="reporte"><img width="20" height="20" 
                src="https://img.icons8.com/color/48/ms-excel.png" alt="ms-excel" /> Descargar Reporte</button>
            </div>
        </div>

        <div class="reponse_table hiddebox">

            <div class="table-responsive">

                <table id="tblreporte" class="table table-bordered nowrap dt-scroll-head table-sm table-hover" style="width: 100%;" data-page-length='25'>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Cuenta</th>
                            <th>Registró</th>
                            <th>Tarea</th>
                            <th>Propietario</th>
                            <th>Calle</th>
                            <th>Num Int</th>
                            <th>Num Ext</th>
                            <th>Colonia</th>
                            <th>CP</th>
                            <th>Adeudo Actual</th>
                            <th>Adeudo Inicial</th>
                            <th>Latitud</th>
                            <th>Longitud</th>
                            <th>Gestor</th>
                            <th>Número Medidor</th>
                            <th>Fecha Captura</th>
                            <th>Geo Punto</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/reporte.js"></script>
    <br>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid mb-5">
            <span class="navbar-text" style="font-size:12px;font-weight:normal;color: #7a7a7a;">
                Implementta Web <i class="far fa-registered"></i><br>
                Estrategas de México <i class="far fa-registered"></i><br>
                Centro de Inteligencia Informática y Geografía Aplicada CIIGA
                <hr style="width:105%;border-color:#7a7a7a;">
                Created and designed by <i class="far fa-copyright"></i> <?php echo date('Y') ?> Estrategas de México<br>
            </span>
            <span class="navbar-text" style="font-size:12px;font-weight:normal;color: #7a7a7a;">
                Contacto:<br>
                <i class="fas fa-phone-alt"></i> Red: 187<br>
                <i class="fas fa-phone-alt"></i> 66 4120 1451<br>
                <i class="fas fa-envelope"></i> sistemas@estrategas.mx<br>
            </span>
            <ul class="navbar-nav ml-auto">
                <form class="form-inline my-2 my-lg-0">
                    <a href="#"><img src="img/logoImplementta.png" width="155" height="150" alt=""></a>
                    <a href="http://estrategas.mx/" target="_blank"><img src="img/logoTop.png" width="200" height="85" alt=""></a>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </form>
            </ul>
        </div>
    </nav>
</body>

</html>
<?php
?>