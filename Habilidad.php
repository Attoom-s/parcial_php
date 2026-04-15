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
        $danio = rand($this->danioMin, $this->danioMax);

        if (rand(1, 100) <= $this->probCritico) {
            $danio *= 2;
        echo "<p class='critico'>¡Golpe crítico!</p>";
        }

        if (rand(1, 100) <= 10) {
            echo $this->nombre . " ha fallado.\n",'<br>';
            return 0;
        }

        $objetivo->recibirDanio($danio);
        return $danio;
    }
}
?>