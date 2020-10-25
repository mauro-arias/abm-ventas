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

    
}

?>