<?php
class Item {
    public $tipo;
    public $nombre;
    public $peso;

    public function __construct($tipo, $nombre, $peso) {
        $this->tipo = $tipo;
        $this->nombre = $nombre;
        $this->peso = $peso;
    }
}
?>