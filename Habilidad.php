<?php
require_once "IHabilidad.php";
require_once "Personaje.php";
require_once "Quemadura.php";

class Habilidad implements IHabilidad {
    public $nombre;
    public $costoBase;     
    public $danioMin;
    public $danioMax;
    public $probCritico;
    public $probQuemadura;   // Nueva: chance de aplicar quemadura
    public $danioQuemadura;  // Daño base de la quemadura

    public function __construct($nombre, $costoBase, $danioMin, $danioMax, $probCritico = 30, $probQuemadura = 0, $danioQuemadura = 0) {
        $this->nombre = $nombre;
        $this->costoBase = $costoBase;
        $this->danioMin = $danioMin;
        $this->danioMax = $danioMax;
        $this->probCritico = $probCritico;
        $this->probQuemadura = $probQuemadura;
        $this->danioQuemadura = $danioQuemadura;
    }

    public function usar(Personaje $objetivo, $danoExtra = 0) {
        // Fallo
        if (rand(1, 100) <= 10) {
            echo $this->nombre . " ha fallado.<br>";
            return 0;
        }

        $danio = rand($this->danioMin, $this->danioMax) + $danoExtra;

        // Crítico
        if (rand(1, 100) <= $this->probCritico) {
            $danio *= 2;
            echo "<p class='critico'>¡Golpe crítico!</p>";
        }

        $objetivo->recibirDanio($danio);

        // Quemadura
        if ($this->probQuemadura > 0 && rand(1, 100) <= $this->probQuemadura) {
            $quemadura = new Quemadura($this->danioQuemadura);
            $objetivo->efectos[] = $quemadura;
            echo "<span style='color:orange'>🔥 $objetivo->nombre queda en llamas (quemadura por {$this->danioQuemadura} daño/turno)</span><br>";
        }

        return $danio;
    }
}
?>