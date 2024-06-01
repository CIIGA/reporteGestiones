<?php
session_start();
$_SESSION['plaza'] = 'implementtaTijuanaA';
$plaza = $_SESSION['plaza'];
require "cnx/cnx.php";
$cnx = conexion($plaza);

$rango_fechas = $_GET['rango_fechas'];
$tipo = $_GET['tipo'];
$cuenta = $_GET['cuenta'];
$gestor = $_GET['gestor'];

$_SESSION['rango_fechas'] = $rango_fechas;
$_SESSION['tipo'] = $tipo;
$_SESSION['cuenta'] = $cuenta;
$_SESSION['gestor'] = $gestor;

list($fechaInicio, $fechaFin) = explode(" - ", $rango_fechas);
if (empty($cuenta)) {
    $cuenta = '-- selecciona una cuenta --';
}
if (empty($gestor)) {
    $gestor = '-- selecciona un gestor --';
}


$gestiones = array(); // Inicializa un array para almacenar las calificaciones
$cuentas = array(); // Inicializa un array para almacenar las cuentas
$gestores = array(); // Inicializa un array para almacenar las cuentas
// Construir la consulta SQL
$sql = "EXEC [dbo].[sp_reporteGestion] 
    @fechaInicio = '$fechaInicio', 
    @fechaFin = '$fechaFin', 
    @tipo = $tipo, 
    @cuenta = '$cuenta', 
    @nombre = '$gestor'";

// Ejecutar la consulta
$resultado = sqlsrv_query($cnx, $sql);

if ($resultado === false) {
    // Manejo de errores
    die(print_r(sqlsrv_errors(), true));
}
if ($tipo == 4) {
    // Recorrer los resultados y almacenarlos en el array de calificaciones
while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
    $gestiones[] = array(
        'registro' => utf8_encode($row['Rol']),//
        'geopunto' => utf8_encode($row['GeoPunto']),
        'cuenta' => utf8_encode($row['Cuenta']),//
        'propietario' => utf8_encode($row['Propietario']),//
        'calle' => utf8_encode($row['Calle']),//
        'numext' => utf8_encode($row['NumExt']),//
        'numint' => utf8_encode($row['NumInt']),//
        'colonia' => utf8_encode($row['Colonia']),//
        'cp' => utf8_encode($row['CP']),//
        'adeudoa' => utf8_encode($row['Adeudo Inicial']),//
        'adeudoi' => utf8_encode($row['Adeudo Actual']),//
        'latitud' => utf8_encode($row['Latitud']),//
        'longitud' => utf8_encode($row['Longitud']),//
        'gestor' => utf8_encode($row['Gestor']),//
        'tarea' => utf8_encode($row['Tarea']),//
        'medidor' => utf8_encode($row['numeroMedidor']),//
        'fecha' => $row['Fecha'],//
    );
    if (!in_array($row['Cuenta'], $cuentas)) {
        $cuentas[] = utf8_encode($row['Cuenta']);
    }
    $gestores[] = utf8_decode($row['Gestor']); // Agregar todos los gestores
}
} else {
   // Recorrer los resultados y almacenarlos en el array de calificaciones
while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
    $gestiones[] = array(
        'registro' => utf8_encode($row['Rol']),//
        'geopunto' => utf8_encode($row['GeoPunto']),
        'cuenta' => utf8_encode($row['Cuenta']),//
        'propietario' => utf8_encode($row['Propietario']),//
        'calle' => utf8_encode($row['Calle']),//
        'numext' => utf8_encode($row['NumExt']),//
        'numint' => utf8_encode($row['NumInt']),//
        'colonia' => utf8_encode($row['Colonia']),//
        'cp' => utf8_encode($row['CP']),//
        'adeudoa' => utf8_encode($row['Adeudo Inicial']),//
        'adeudoi' => utf8_encode($row['Adeudo Actual']),//
        'latitud' => utf8_encode($row['latitud']),//
        'longitud' => utf8_encode($row['longitud']),//
        'gestor' => utf8_encode($row['Gestor']),//
        'tarea' => utf8_encode($row['Tarea']),//
        'medidor' => utf8_encode($row['numeroMedidor']),//
        'fecha' => $row['Fecha'],//
    );
    if (!in_array($row['Cuenta'], $cuentas)) {
        $cuentas[] = utf8_encode($row['Cuenta']);
    }
    $gestores[] = utf8_decode($row['Gestor']); // Agregar todos los gestores
}
}

$cuentas = array_unique($cuentas);
$gestores = array_unique($gestores);

// Liberar los recursos
sqlsrv_free_stmt($resultado);

// Devolver los resultados en formato JSON
echo json_encode([
    "data" => $gestiones,
    "cuentas" => array_values($cuentas),
    "gestores" => array_values($gestores)
]);
?>
