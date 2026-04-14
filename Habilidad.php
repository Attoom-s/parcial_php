<?php
require_once "IHabilidad.php";
require_once "Personaje.php";

class Habilidad implements IHabilidad {
    public $nombre;
    public $costoBase;     
    public $danioMin;
    public $danioMax;
    public $probCritico;    

    public function __construct($nombre, $costoBase, $danioMin, $danioMax, $probCritico = 30) {
        $this->nombre = $nombre;
        $this->costoBase = $costoBase;
        $this->danioMin = $danioMin;
        $this->danioMax = $danioMax;
        $this->probCritico = $probCritico;
    }

    public function usar(Personaje $objetivo) {
        // 1. Daño aleatorio dentro del rango
        $danio = rand($this->danioMin, $this->danioMax);

        // 2. Crítico aleatorio según probabilidad
        if (rand(1, 100) <= $this->probCritico) {
            $danio *= 2;
            echo "¡Golpe crítico!\n",'<br>';
        }

        // 3. Pequeña probabilidad de fallo (10%)
        if (rand(1, 100) <= 10) {
            echo $this->nombre . " ha fallado.\n",'<br>';
            return 0; // no causa daño
        }

        $objetivo->recibirDanio($danio);
        return $danio;
    }
}
?>