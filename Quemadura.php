<?php
require_once "Personaje.php";

class Quemadura {
    public $danioPorTurno;
    public $duracion;  // turnos restantes

    public function __construct($danio, $duracion = 3) {
        $this->danioPorTurno = $danio;
        $this->duracion = $duracion;
    }
    
    public function aplicar(Personaje $p) {
        if ($this->duracion <= 0) return;

        $danioReal = $this->danioPorTurno + rand(-2, 2);
        if ($danioReal < 1) $danioReal = 1;
        echo $p->nombre . " sufre quemadura (-$danioReal)<br>";
        $p->recibirDanio($danioReal);
        $this->duracion--;
    }

    public function isActive() {
        return $this->duracion > 0;
    }
}
?>