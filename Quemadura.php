<?php
require_once "Personaje.php";

class Quemadura {
    public $danioPorTurno;

    public function __construct($danio) {
        $this->danioPorTurno = $danio;
    }

    public function aplicar(Personaje $p) {
        echo $p->nombre . " sufre quemadura (-$this->danioPorTurno)\n";
        $p->recibirDanio($this->danioPorTurno);
    }
}
?>