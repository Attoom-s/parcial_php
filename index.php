<?php
require_once "Personaje.php";
require_once "Habilidad.php";
require_once "Quemadura.php";
require_once "Item.php";

$gandalf = new Personaje("Gandalf", 100, 100);
$orco = new Personaje("Orco", 120, 0);

$bolaFuego = new Habilidad("Bola de Fuego", 20, 50);

$gandalf->agregarHabilidad($bolaFuego);

$gandalf->usarHabilidad("Bola de Fuego", $orco);
$gandalf->usarHabilidad("Bola de Fuego", $orco);

$quemadura = new Quemadura(10);
$quemadura->aplicar($orco);

if (!$orco->estaVivo()) {
    echo "¡Orco ha sido derrotado!<br>";
    $gandalf->ganarExp(25);
} else {
    echo "El Orco sigue vivo con " . $orco->vida . "<br>";
}

$item1 = new Item("pocion", "Poción de Vida", 1);
$item2 = new Item("arma", "Espada", 5);

echo "Items creados: " . $item1->nombre . " y " . $item2->nombre . "<br>";
?>