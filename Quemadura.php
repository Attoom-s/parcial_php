<?php
require_once "Personaje.php";

class Quemadura {
    public $danioPorTurno;

    public function __construct($danio) {
        $this->danioPorTurno = $danio;
    }
    
    //Danio por turno
    public function aplicar(Personaje $p) {
        $danioReal = $this->danioPorTurno + rand(-2, 2);
        if ($danioReal < 1) $danioReal = 1;
        echo $p->nombre . " sufre quemadura (-$danioReal)\n";
        $p->recibirDanio($danioReal);
    }
}
?>