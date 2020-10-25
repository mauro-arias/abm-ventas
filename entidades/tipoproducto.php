<?php

class TipoProducto{
    private $idtipoproducto;
    private $nombre;

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
        $this->idtipoproducto = isset($request["id"])? $request["id"] : "";
        $this->nombre = isset($request["txtTipoProducto"])? $request["txtTipoProducto"] : "";
    }

    #Insertar en la base de datos

    public function insertar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);

        $sql = "INSERT INTO tipo_productos (
            tipo_producto)
            VALUES (
            '" . $this->nombre . "'
            );";

        if (!$mysqli->query($sql)){
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $this->idtipoproducto = $mysqli->insert_id;

        $mysqli->close();

    }

    public function actualizar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);

        $sql = "UPDATE tipo_productos SET 
        tipo_producto = '".$this->nombre."'
        WHERE id_tipoproducto = ". $this->idtipoproducto;
        

        if (!$mysqli->query($sql)){
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $mysqli->close();
    }


    public function eliminar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM tipo_productos WHERE id_tipoproducto = " . $this->idtipoproducto;

        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT id_tipoproducto,
                tipo_producto
                FROM tipo_productos 
                WHERE id_tipoproducto = " . $this->idtipoproducto;
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        if($fila = $resultado->fetch_assoc()){
            $this->idtipoproducto = $fila["id_tipoproducto"];
            $this->nombre = $fila["tipo_producto"];
        }  
        $mysqli->close();
    }

    public function obtenerTodos(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);


        $sql = "SELECT id_tipoproducto, 
                        tipo_producto
                FROM tipo_productos";

        if(!$resultado = $mysqli->query($sql)){
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $aResultado = array();

        if($resultado){
            while($fila = $resultado->fetch_assoc()){
                $entidadAux = new TipoProducto();
                $entidadAux->idtipoproducto = $fila["id_tipoproducto"];
                $entidadAux->nombre = $fila["tipo_producto"];
                $aResultado[] = $entidadAux;
            }
        }

        return $aResultado;
    }
    
}

?>