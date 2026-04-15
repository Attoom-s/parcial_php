<?php
require_once "Habilidad.php";

class Personaje {
    public $nombre;
    public $vida;
    public $mana;
    public $habilidades = [];
    public $nivel = 1;
    public $experiencia = 0;

    public function __construct($nombre, $vida, $mana) {
        $this->nombre = $nombre;
        $this->vida = $vida;
        $this->mana = $mana;
    }

    public function agregarHabilidad(Habilidad $h) {
        $this->habilidades[$h->nombre] = $h;
        echo $this->nombre . " aprendió: " . $h->nombre . "<br>";
    }

    public function usarHabilidad($nombre, Personaje $objetivo) {
        try {
            if (!isset($this->habilidades[$nombre])) {
                throw new Exception("No tiene esa habilidad");
            }

            $habilidad = $this->habilidades[$nombre];
            
            $costoReal = $habilidad->costoBase + rand(-5, 5);
            if ($costoReal < 5) $costoReal = 5; 

            if ($this->mana < $costoReal) {
                throw new Exception("No tiene suficiente mana (necesita $costoReal)");
            }

            $this->mana -= $costoReal;
            echo $this->nombre . " gasta $costoReal de mana (base: {$habilidad->costoBase}).<br>";

            $danio = $habilidad->usar($objetivo);

            if ($danio > 0) {
            echo "<p class='danio'>$objetivo->nombre recibió $danio de daño. Vida restante: $objetivo->vida</p>";
            }

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "<br>";
        }
    }

    public function recibirDanio($cantidad) {
        $this->vida -= $cantidad;
        if ($this->vida < 0) {
            $this->vida = 0;
        }
    }

    public function estaVivo() {
        return $this->vida > 0;
    }

    public function ganarExp($exp) {
        $this->experiencia += $exp;
        echo $this->nombre . " ganó $exp de experiencia<br>";

        while ($this->experiencia >= 50) {
            $this->nivel++;
            $this->experiencia -= 50;
            echo $this->nombre . " subió a nivel " . $this->nivel . "<br>";
        }
    }
}
?>