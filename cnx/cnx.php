<?php
function conexion($db)
{

    if ($db == 'implementtaGuadalajaraA') {
        $serverName = "51.79.98.210";
        $connectionInfo = array('Database' => $db, 'UID' => 'sa', 'PWD' => '=JeFGm[jFd%J?7j');
        $cnx = sqlsrv_connect($serverName, $connectionInfo);
        date_default_timezone_set('America/Mexico_City');
        return $cnx;
    } else {
        $serverName = "implementta.mx";
        $connectionInfo = array('Database' => $db, 'UID' => 'sa', 'PWD' => 'vrSxHH3TdC');
        $cnx = sqlsrv_connect($serverName, $connectionInfo);
        date_default_timezone_set('America/Mexico_City');
        return $cnx;
    }
}
function plaza($bd)
{
    $serverName = "51.222.44.135";
    $connectionInfo = array('Database' => 'kpis', 'UID' => 'sa', 'PWD' => 'sb19bXxM10');
    // $serverName = "DESKTOP-79KR1H4";
    // $connectionInfo = array('Database' => 'kpis', 'UID' => 'brayan', 'PWD' => '12345');
    $cnx = sqlsrv_connect($serverName, $connectionInfo);
    // date_default_timezone_set('America/Mexico_City');
    $pl = "SELECT top(1) p.data as base, pl.nombreplaza as plaza,  pl.id_plaza as id FROM plaza as pl INNER JOIN proveniente as p ON pl.id_proveniente=p.id_proveniente
    where  p.data='$bd'";
    $plz = sqlsrv_query($cnx, $pl);
    $result = sqlsrv_fetch_array($plz);
    return $result;
}
