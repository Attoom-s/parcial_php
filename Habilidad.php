<?php
require_once "IHabilidad.php";
require_once "Personaje.php";

class Habilidad implements IHabilidad {
    public $nombre;
    public $costo;
    public $danioBase;

    public function __construct($nombre, $costo, $danioBase) {
        $this->nombre = $nombre;
        $this->costo = $costo;
        $this->danioBase = $danioBase;
    }

    public function usar(Personaje $objetivo) {
        $danio = $this->danioBase;

        if (rand(1,100) <= 30) {
            $danio *= 2;
            echo "¡Golpe crítico!\n";
        }

        $objetivo->recibirDanio($danio);
        return $danio;
    }
}
?>