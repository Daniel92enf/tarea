<?php
    class Rutas {
        protected $urlBase = "http://localhost/tarea";
            public function __construct() {
        }
        public function dameUrlBase() {
            return $this->urlBase;
        }
        public function dameMenuInicio() {
            return '<a href="'.$this->urlBase.'/cliente/index.php">Inicio</a>';
        }
        public function dameMenuNuevo() {
            return "<a href='".$this->urlBase."/cliente/registro.php'>Registro</a>";
        }     
    }
?>