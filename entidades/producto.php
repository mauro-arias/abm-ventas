<?php

class Producto{
    private $idproducto;
    private $nombre;
    private $cantidad;
    private $precio;
    private $descripcion;
    private $imagen;
    private $fk_idtipoproducto;

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
        $this->idproducto = isset($request["id"])? $request["id"] : "";
        $this->nombre = isset($request["txtNombre"])? $request["txtNombre"] : "";
        $this->cantidad = isset($request["nbCantidad"])? $request["nbCantidad"] : "";
        $this->precio = isset($request["nbPrecio"])? $request["nbPrecio"] : "";
        $this->descripcion = isset($request["txtDescripcion"])? $request["txtDescripcion"] : "";
        $this->fk_idtipoproducto = isset($request["lstTipoProducto"])? $request["lstTipoProducto"] : "";
    }

    #Insertar en la base de datos

    public function insertar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);

        $sql = "INSERT INTO productos (
            nombre,
            cantidad,
            precio,
            descripcion,
            fk_idtipoproducto)
            VALUES (
            '" . $this->nombre . "',
            $this->cantidad,
            $this->precio,
            '" . $this->descripcion. "',
            '" . $this->fk_idtipoproducto . "'
            );";

        if (!$mysqli->query($sql)){
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $this->idproducto = $mysqli->insert_id;

        $mysqli->close();

    }

    public function actualizar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);

        $sql = "UPDATE productos SET 
        nombre = '".$this->nombre."',
        cantidad = $this->cantidad ,
        precio = $this->telefono,
        descripcion = '".$this->correo."',
        fk_idtipoproducto = $this->fk_idtipoproducto
        WHERE idproducto = ". $this->idproducto;
        

        if (!$mysqli->query($sql)){
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $mysqli->close();
    }

    public function eliminar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM productos WHERE idproducto = " . $this->idproducto;

        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT idproducto, 
                        nombre, 
                        cantidad, 
                        precio, 
                        descripcion, 
                        imagen,
                        fk_idtipoproducto 
                FROM productos 
                WHERE idproducto = " . $this->idproducto;
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        if($fila = $resultado->fetch_assoc()){
            $this->idproducto = $fila["idproducto"];
            $this->nombre = $fila["nombre"];
            $this->cantidad = $fila["cantidad"];
            $this->precio = $fila["precio"];
            $this->descripcion = $fila["imagen"];
            $this->fk_idtipoproducto = $fila["fk_idtipoproducto"];
        }  
        $mysqli->close();
    }

    public function obtenerTodos(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);


        $sql = "SELECT idproducto, 
                        nombre, 
                        cantidad, 
                        precio, 
                        descripcion, 
                        imagen,
                        fk_idtipoproducto 
                FROM productos";

        if(!$resultado = $mysqli->query($sql)){
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $aResultado = array();

        if($resultado){
            while($fila = $resultado->fetch_assoc()){
                $entidadAux = new Producto();
                $entidadAux->idproducto = $fila["idproducto"];
                $entidadAux->nombre = $fila["nombre"];
                $entidadAux->cantidad = $fila["cantidad"];
                $entidadAux->precio = $fila["precio"];
                $entidadAux->descripcion = $fila["descripcion"];
                $entidadAux->imagen = $fila["imagen"];
                $entidadAux->fk_idtipoproducto = $fila["fk_idtipoproducto"];
                $aResultado[] = $entidadAux;
            }
        }

        return $aResultado;
    }
}

?>