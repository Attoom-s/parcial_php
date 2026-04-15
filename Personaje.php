<?php
require_once "Habilidad.php";
require_once "Logger.php";

class Personaje {
    public $nombre;
    public $vida;
    public $vidaMax;
    public $mana;
    public $habilidades = [];
    public $items = [];
    public $efectos = [];
    public $nivel = 1;
    public $experiencia = 0;
    public $danoExtra = 0;

    public function __construct($nombre, $vida, $mana) {
        $this->nombre = $nombre;
        $this->vida = $vida;
        $this->vidaMax = $vida;
        $this->mana = $mana;
    }

    public function agregarHabilidad(Habilidad $h) {
        $this->habilidades[$h->nombre] = $h;
        Logger::log($this->nombre . " aprendió: " . $h->nombre . "<br>");
    }

    public function agregarItem(Item $item) {
        $this->items[] = $item;
        Logger::log($this->nombre . " recibió: " . $item->nombre . " (peso {$item->peso})<br>");
    }

    public function usarItem($indice) {
        if (!isset($this->items[$indice])) {
            Logger::log("No tienes ese objeto.<br>");
            return false;
        }
        $item = $this->items[$indice];
        $efecto = "";

        switch ($item->tipo) {
            case 'pocion':
                $curacion = $item->peso * 10;
                $this->vida += $curacion;
                if ($this->vida > $this->vidaMax) $this->vida = $this->vidaMax;
                $efecto = "restaura $curacion de vida";
                break;
            case 'arma':
                $this->danoExtra = $item->peso;
                $efecto = "aumenta el daño de su próximo ataque en {$item->peso} puntos";
                break;
            default:
                Logger::log("Objeto no usable.<br>");
                return false;
        }

        Logger::log("$this->nombre usa {$item->nombre} → $efecto.<br>");
        array_splice($this->items, $indice, 1);
        return true;
    }

    public function aplicarEfectos() {
        foreach ($this->efectos as $key => $efecto) {
            $efecto->aplicar($this);
            if (!$efecto->isActive()) {
                unset($this->efectos[$key]);
            }
        }
        $this->efectos = array_values($this->efectos);
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
            Logger::log($this->nombre . " gasta $costoReal de mana (base: {$habilidad->costoBase}).<br>");

            $danoExtraTemp = $this->danoExtra;
            $this->danoExtra = 0;

            $danio = $habilidad->usar($objetivo, $danoExtraTemp);

            if ($danio > 0) {
                Logger::log("<p class='danio'>$objetivo->nombre recibió $danio de daño. Vida restante: $objetivo->vida</p>");
            }

        } catch (Exception $e) {
            Logger::log("Error: " . $e->getMessage() . "<br>");
        }
    }

    public function recibirDanio($cantidad) {
        $this->vida -= $cantidad;
        if ($this->vida < 0) $this->vida = 0;
    }

    public function estaVivo() {
        return $this->vida > 0;
    }

    public function ganarExp($exp) {
        $this->experiencia += $exp;
        Logger::log($this->nombre . " ganó $exp de experiencia<br>");

        while ($this->experiencia >= 50) {
            $this->nivel++;
            $this->experiencia -= 50;
            Logger::log($this->nombre . " subió a nivel " . $this->nivel . "<br>");
        }
    }
}
?>