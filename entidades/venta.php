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

        $sql2 = "UPDATE productos SET cantidad = cantidad - $this->cantidad WHERE idproducto = $this->fk_producto ";

        if (!$mysqli->query($sql)){
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        if(!$mysqli->query($sql2)){
            printf("Error en query: %s\n", $mysqli->error . " " . $sql2);
        }

        $this->idventa = $mysqli->insert_id;

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
            $this->fk_idcliente = $fila["fk_cliente"];
            $this->fk_producto = $fila["fk_producto"];
        }  
        $mysqli->close();
    }

    public function obtenerTodos(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);


        $sql = "SELECT idventa, 
                        cantidad, 
                        total, 
                        precio_unitario, 
                        fecha_hora,
                        fk_cliente,
                        fk_producto 
                FROM ventas";

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
                $aResultado[] = $entidadAux;
            }
        }

        return $aResultado;
    }
}

?>