<?php

class Venta{
    private $idventa;
    private $fecha;
    private $hora;
    private $cantidad;
    private $fk_producto;
    private $fk_idcliente;
    private $total;
    private $precio;

    public function __construct(){

    }

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
        return $this;
    }

    # Carga formulario

    public function cargarFormulario($request){
        $this->idventa = isset($request["id"])? $request["id"] : "";
        $this->fecha = isset($request["txtFecha"])? $request["txtFecha"] : "";
        $this->hora = isset($request["txtHora"])? $request["txtHora"] : "";
        $this->cantidad = isset($request["nbCantidad"])? $request["nbCantidad"] : "";
        $this->fk_producto = isset($request["lstProducto"])? $request["lstProducto"] : "";
        $this->fk_idcliente = isset($request["lstCliente"])? $request["lstCliente"] : "";
        $this->total = isset($request["nbTotal"])? $request["nbTotal"] : "";
        $this->precio = isset($request["nbPUnitario"])? $request["nbPUnitario"] : "";
    }

    #Insertar en la base de datos

    public function insertar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);

        $sql = "INSERT INTO ventas (
            fecha_hora,
            cantidad,
            fk_producto,
            fk_cliente,
            precio_unitario,
            total)
            VALUES (
            '" . $this->fecha . ' ' . $this->hora . "',
            $this->cantidad,
            $this->fk_producto,
            $this->fk_idcliente,
            $this->precio,
            $this->total
            );";

        

        // Query para restar el stock al realizar la venta
        $sql2 = "SELECT cantidad FROM productos";
        

        if(!$resultado = $mysqli->query($sql2)){
            printf("Error en query: %s\n", $mysqli->error . " " . $sql2);
        }
        
        $fila = $resultado->fetch_assoc();
        $cantidadActual = $fila["cantidad"];
        
        $sql3 = "UPDATE productos SET cantidad = cantidad - $this->cantidad WHERE idproducto = $this->fk_producto ";

        # VERIFICAMOS SI LA CANTIDAD DEL STOCK ES MENOR A LA VENTA

        if($cantidadActual < $this->cantidad){
            echo "La cantidad ingresada es mayor a la del stock disponible, la venta no se realizó";
        } else{
            # INSERTAMOS LA VENTA
            if (!$mysqli->query($sql)){
                printf("Error en query: %s\n", $mysqli->error . " " . $sql);
            }
            # RESTAMOS LA CANTIDAD DE LA VENTA AL STOCK
            $this->idventa = $mysqli->insert_id;
            if (!$mysqli->query($sql3)){
                printf("Error en query: %s\n", $mysqli->error . " " . $sql3);
            }
        }

        $mysqli->close();

    }

    public function actualizar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);

        $sql = "UPDATE ventas SET 
        fecha_hora = '".$this->fecha."',
        cantidad = $this->cantidad ,
        fk_producto = $this->fk_producto,
        fk_cliente = $this->fk_idcliente,
        total = $this->total
        WHERE idventa = ". $this->idventa;

        

        if (!$mysqli->query($sql)){
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $mysqli->close();
    }


    public function eliminar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM ventas WHERE idventa = " . $this->idventa;

        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT idventa, 
                        cantidad, 
                        total, 
                        precio_unitario, 
                        fecha_hora,
                        fk_cliente,
                        fk_producto 
                FROM ventas 
                WHERE idventa = " . $this->idventa;
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        if($fila = $resultado->fetch_assoc()){
            $this->idventa = $fila["idventa"];
            $this->total = $fila["total"];
            $this->cantidad = $fila["cantidad"];
            $this->precio = $fila["precio_unitario"];
            $this->fecha = $fila["fecha_hora"];
            $this->hora = $fila["fecha_hora"];
            $this->fk_idcliente = $fila["fk_cliente"];
            $this->fk_producto = $fila["fk_producto"];
        }  
        $mysqli->close();
    }

    public function obtenerTodos(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);


        $sql = "SELECT V.idventa, 
                V.total,
                V.fecha_hora,
                V.fk_cliente,
                V.fk_producto,
                V.cantidad,
                V.precio_unitario,
                P.nombre AS nombre_producto, 
                C.nombre AS nombre_cliente FROM ventas V
                INNER JOIN clientes C ON C.idcliente = V.fk_cliente
                INNER JOIN productos P ON P.idproducto = V.fk_producto
                ORDER BY fecha_hora DESC ";

        if(!$resultado = $mysqli->query($sql)){
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $aResultado = array();

        if($resultado){
            while($fila = $resultado->fetch_assoc()){
                $entidadAux = new Venta();
                $entidadAux->idventa = $fila["idventa"];
                $entidadAux->total = $fila["total"];
                $entidadAux->cantidad = $fila["cantidad"];
                $entidadAux->precio = $fila["precio_unitario"];
                $entidadAux->fecha = $fila["fecha_hora"];
                $entidadAux->fk_idcliente = $fila["fk_cliente"];
                $entidadAux->fk_producto = $fila["fk_producto"];
                $entidadAux->nombre_cliente = $fila["nombre_cliente"];
                $entidadAux->nombre_producto = $fila["nombre_producto"];
                $aResultado[] = $entidadAux;
            }
        }

        return $aResultado;
    }

    public function obtenerFacturacionMensual($mes){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);

        $sql = "SELECT SUM(total) AS importe_mensual FROM ventas WHERE $mes = EXTRACT(MONTH FROM fecha_hora);";

        $resultado = $mysqli->query($sql);
        
        if($fila = $resultado->fetch_assoc()){
            $importeMensual = $fila["importe_mensual"];
        }

        return $importeMensual;
    }

    public function obtenerFacturacionAnual($año){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);

        $sql = "SELECT SUM(total) AS importe_anual FROM ventas WHERE $año = EXTRACT(YEAR FROM fecha_hora);";
        
        $resultado = $mysqli->query($sql);
        
        if($fila = $resultado->fetch_assoc()){
            $importeAnual = $fila["importe_anual"];
        }

        return $importeAnual;
    }

    public function obtenerVentasPorCliente($id){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);

        $sql = "SELECT COUNT(*) as cantidad FROM ventas WHERE fk_cliente = $id";

        if(!$resultado = $mysqli->query($sql)){
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        if($fila = $resultado->fetch_assoc()){
            $cantidad = $fila["cantidad"];
        }

        return $cantidad;

    }

}

?>