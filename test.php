<?php

session_start();

$_SESSION['plaza'] = 'implementtaTijuanaA';
$plaza = $_SESSION['plaza'];
require "cnx/cnx.php";
$cnx = conexion($plaza);

require 'excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$rango_fechas = $_SESSION['rango_fechas'];
$tipo = $_SESSION['tipo'];
$cuenta = $_SESSION['cuenta'];
$gestor = $_SESSION['gestor'];

list($fechaInicio, $fechaFin) = explode(" - ", $rango_fechas);
if (empty($cuenta)) {
    $cuenta = '-- selecciona una cuenta --';
}
if (empty($gestor)) {
    $gestor = '-- selecciona un gestor --';
}

$sql = "EXEC [dbo].[sp_reporteGestion] 
    @fechaInicio = '$fechaInicio', 
    @fechaFin = '$fechaFin', 
    @tipo = $tipo, 
    @cuenta = '$cuenta', 
    @nombre = '$gestor'";

// Ejecutar la consulta
$resultado = sqlsrv_query($cnx, $sql);

// Crear un nuevo objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Agregar el título
$title = "REPORTE DE GESTIONES";
$sheet->setCellValue('A1', $title);
$sheet->mergeCells('A1:Q1');
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1')->getFont()->setBold(true);

// Escribir los encabezados de la tabla
$headers = [
    "Cuenta", "Registró", "Tarea", "Propietario", "Calle", "Num Int", "Num Ext", "Colonia", "CP", "Adeudo Actual",
    "Adeudo Inicial", "Latitud", "Longitud", "Gestor", "Número Medidor", "Fecha Captura", "Geo Punto"
];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '2', $header);
    $col++;
}

// Escribir los datos de la consulta
$fila = 3;
while ($row = sqlsrv_fetch_array($resultado, SQLSRV_FETCH_ASSOC)) {
    $sheet->setCellValue('A' . $fila, $row['Cuenta']);
    $sheet->setCellValue('B' . $fila, $row['Rol']);
    $sheet->setCellValue('C' . $fila, $row['Tarea']);
    $sheet->setCellValue('D' . $fila, $row['Propietario']);
    $sheet->setCellValue('E' . $fila, $row['Calle']);
    $sheet->setCellValue('F' . $fila, $row['NumInt']);
    $sheet->setCellValue('G' . $fila, $row['NumExt']);
    $sheet->setCellValue('H' . $fila, $row['Colonia']);
    $sheet->setCellValue('I' . $fila, $row['CP']);
    $sheet->setCellValue('J' . $fila, $row['Adeudo Actual']);
    $sheet->setCellValue('K' . $fila, $row['Adeudo Inicial']);
    if ($tipo == 4) {
        $sheet->setCellValue('L' . $fila, $row['Latitud']);
        $sheet->setCellValue('M' . $fila, $row['Longitud']);
    } else {
        $sheet->setCellValue('L' . $fila, $row['latitud']);
        $sheet->setCellValue('M' . $fila, $row['longitud']);
    }


    $sheet->setCellValue('N' . $fila, $row['Gestor']);
    $sheet->setCellValue('O' . $fila, $row['numeroMedidor']);
    $sheet->setCellValue('P' . $fila, $row['Fecha']);

    if (!empty($row['GeoPunto'])) {
        $sheet->setCellValue('Q' . $fila, "Ubicación");
        $sheet->getCell('Q' . $fila)->getHyperlink()->setUrl($row['GeoPunto']);
        $sheet->getStyle('Q' . $fila)->getFont()->getColor()->setARGB(Color::COLOR_BLUE);
    } else {
        $sheet->setCellValue('Q' . $fila, "");
    }

    if ($row['Rol'] == "Gestor") {
        $rol = 2;
    } else if ($row['Rol'] == "Abogado") {
        $rol = 3;
    } else if ($row['Rol'] == "Carta Invitacion") {
        $rol = 7;
    } else if ($row['Rol'] == "Reductores") {
        $rol = 8;
    }
    if ($rol == '2' || $rol == '3' || $rol == '7' || $rol == '8') {
        if ($rol == '7') {
            $tabla = 'registroCartaInvitacion';
            $idRegistro = 'idRegistroCartaInvitacion';
        } elseif ($rol == '3') {
            $tabla = 'RegistroAbogado';
            $idRegistro = 'IdRegistroAbogado';
        } elseif ($rol == '2') {
            $tabla = 'RegistroGestor';
            $idRegistro = 'IdRegistroGestor';
        } elseif ($rol == '8') {
            $tabla = 'RegistroReductores';
            $idRegistro = 'IdRegistroReductores';
        }
        $fecha = $row['Fecha'];
        $sql_cruce = "select a.$idRegistro as id from $tabla a
        where Cuenta='$cuenta' and convert(date,a.fechacaptura)='$fecha'";
        echo $sql_cruce;
        exit;
        $cnx_sql_cruce = sqlsrv_query($cnx, $sql_cruce);

        $cruce = sqlsrv_fetch_array($cnx_sql_cruce);

        $id_gestion = $cruce['id'];
    }

    $fila++;
}

// Aplicar bordes a todas las celdas de la tabla
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];
$sheet->getStyle('A1:Q' . ($fila - 1))->applyFromArray($styleArray);

// Guardar el archivo Excel temporalmente en el servidor
$filename = 'Reporte_Gestiones_' . date('YmdHis') . '.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($filename);

// Forzar la descarga del archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header('Content-Length: ' . filesize($filename));
header('Pragma: public');
flush(); // Eliminar contenido del buffer
readfile($filename);

// Eliminar el archivo temporal del servidor
unlink($filename);

exit;
