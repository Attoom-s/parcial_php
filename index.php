<?php
require_once "Personaje.php";
require_once "Habilidad.php";
require_once "Quemadura.php";
require_once "Item.php";

// Crear personajes
$gandalf = new Personaje("Gandalf", 100, 100);
$orco = new Personaje("Orco", 120, 50); // Orco también tiene mana

// Habilidades
$bolaFuego = new Habilidad("Bola de Fuego", 20, 40, 60, 30);
$golpeFeroz = new Habilidad("Golpe Feroz", 15, 30, 50, 20);

$gandalf->agregarHabilidad($bolaFuego);
$orco->agregarHabilidad($golpeFeroz);

$turno = 1;

echo "<h2>¡Comienza el combate!</h2>";

// Bucle principal: ambos están vivos
while ($gandalf->estaVivo() && $orco->estaVivo()) {
    echo "<br>=== Turno $turno ===<br>";
    
    // Turno de Gandalf
    echo "<strong>Fase de Gandalf:</strong><br>";
    if ($gandalf->mana >= $bolaFuego->costoBase) {
        $gandalf->usarHabilidad("Bola de Fuego", $orco);
    } else {
        echo "Gandalf no tiene suficiente mana para atacar.<br>";
    }
    
    // Verificar si Orco murió por el ataque de Gandalf
    if (!$orco->estaVivo()) {
        echo "<br>¡Gandalf ha derrotado al Orco!<br>";
        break;
    }
    
    // Turno del Orco (solo si está vivo)
    echo "<strong>Fase del Orco:</strong><br>";
    if ($orco->mana >= $golpeFeroz->costoBase) {
        $orco->usarHabilidad("Golpe Feroz", $gandalf);
    } else {
        echo "El Orco no tiene suficiente mana para atacar.<br>";
    }
    
    // Mostrar estado después del turno
    echo "<br><strong>Estado del combate:</strong><br>";
    echo "{$gandalf->nombre} Vida: {$gandalf->vida} | Mana: {$gandalf->mana}<br>";
    echo "{$orco->nombre} Vida: {$orco->vida} | Mana: {$orco->mana}<br>";
    
    $turno++;
    
    // Seguridad: evitar bucles infinitos 
    if ($turno > 20) {
        echo "<br>¡Límite de turnos alcanzado! Combate empatado.<br>";
        break;
    }
}

// Resultado final
echo "<br><h3>Resultado del combate:</h3>";
if (!$orco->estaVivo()) {
    echo "¡Gandalf gana el combate en $turno turnos!<br>";
    $expGanada = rand(20, 30);
    $gandalf->ganarExp($expGanada);
} elseif (!$gandalf->estaVivo()) {
    echo " El Orco ha derrotado a Gandalf.<br>";
} else {
    echo " El combate terminó sin un ganador claro.<br>";
}

// Items
$item1 = new Item("pocion", "Poción de Vida", rand(1, 3));
$item2 = new Item("arma", "Espada", rand(4, 8));
echo "<br> Items creados: " . $item1->nombre . " (peso {$item1->peso}) y " . $item2->nombre . " (peso {$item2->peso})<br>";
?>