<?php 
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=reporte.csv');



include_once "config.php";
include_once "entidades/venta.php";

$venta = new Venta();
$aVentas = $venta->obtenerTodos();

$fp = fopen("php://output", "w");

// Insertar cabecera

fputcsv($fp, array("Fecha", "Cliente", "Producto","Cantidad","Total"), ";");

// Insertar datos

foreach($aVentas as $venta){
    fputcsv($fp, array($venta->fecha, $venta->nombre_cliente, $venta->nombre_producto,$venta->cantidad,$venta->total), ";");
}

fclose($fp);



?>

