<?php

include_once "config.php";
include_once "entidades/usuario.php";

$usuario = new Usuario();
$usuario->usuario = "mauroarias";
$usuario->clave = $usuario->encriptarClave("admin123");
$usuario->nombre = "Mauro Daniel";
$usuario->apellido = "Arias Charras";
$usuario->correo = "mauroacharras@hotmail.com";
$usuario->insertar();

?>