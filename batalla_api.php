<?php
require_once "Personaje.php";
require_once "Habilidad.php";
require_once "Quemadura.php";
require_once "Item.php";
require_once "Logger.php";

// Limpiar logger anterior
Logger::clear();

// Crear personajes
$gandalf = new Personaje("Gandalf", 150, 200);
$orco = new Personaje("Orco", 200, 150);

// Items
$pocionG = new Item("pocion", "Poción de Vida", rand(1, 3));
$espada = new Item("arma", "Espada", rand(4, 8));
$pocionO = new Item("pocion", "Poción de Orco", rand(1, 3));

$gandalf->agregarItem($pocionG);
$gandalf->agregarItem($espada);
$orco->agregarItem($pocionO);

// Habilidades (Bola de Fuego con 30% de quemadura, daño 10 por turno)
$bolaFuego = new Habilidad("Bola de Fuego", 20, 40, 60, 30, 30, 10);
$golpeFeroz = new Habilidad("Golpe Feroz", 15, 30, 50, 20);

$gandalf->agregarHabilidad($bolaFuego);
$orco->agregarHabilidad($golpeFeroz);

$turno = 1;

// Bucle de combate
while ($gandalf->estaVivo() && $orco->estaVivo()) {
    Logger::log("<br>📌 Turno $turno<br>");

    // Aplicar efectos de estado
    Logger::log("<strong>⚡ Efectos de estado:</strong><br>");
    $gandalf->aplicarEfectos();
    $orco->aplicarEfectos();

    if (!$gandalf->estaVivo() || !$orco->estaVivo()) break;

    // Turno de Gandalf
    Logger::log("<strong>🧙 Fase de Gandalf:</strong><br>");
    $accionRealizada = false;

    if ($gandalf->vida < $gandalf->vidaMax * 0.4) {
        foreach ($gandalf->items as $i => $item) {
            if ($item->tipo == 'pocion') {
                $gandalf->usarItem($i);
                $accionRealizada = true;
                break;
            }
        }
    }

    if (!$accionRealizada && $gandalf->danoExtra == 0) {
        foreach ($gandalf->items as $i => $item) {
            if ($item->tipo == 'arma') {
                $gandalf->usarItem($i);
                $accionRealizada = true;
                break;
            }
        }
    }

    if (!$accionRealizada) {
        if ($gandalf->mana >= $bolaFuego->costoBase) {
            $gandalf->usarHabilidad("Bola de Fuego", $orco);
        } else {
            Logger::log("Gandalf no tiene suficiente mana para atacar.<br>");
        }
    }

    if (!$orco->estaVivo()) break;

    // Turno del Orco
    Logger::log("<strong>👾 Fase del Orco:</strong><br>");
    $accionRealizada = false;

    if ($orco->vida < $orco->vidaMax * 0.4) {
        foreach ($orco->items as $i => $item) {
            if ($item->tipo == 'pocion') {
                $orco->usarItem($i);
                $accionRealizada = true;
                break;
            }
        }
    }

    if (!$accionRealizada && $orco->mana >= $golpeFeroz->costoBase) {
        $orco->usarHabilidad("Golpe Feroz", $gandalf);
    } elseif (!$accionRealizada) {
        Logger::log("El Orco no tiene suficiente mana para atacar.<br>");
    }

    // Estado después del turno
    Logger::log("<br><strong>📊 Estado actual:</strong><br>");
    Logger::log("{$gandalf->nombre} ❤️ {$gandalf->vida} | ✨ {$gandalf->mana}<br>");
    Logger::log("{$orco->nombre} ❤️ {$orco->vida} | ✨ {$orco->mana}<br>");

    $turno++;
    if ($turno >= 10) {
        Logger::log("<br>⏰ ¡Límite de turnos alcanzado! Combate empatado.<br>");
        break;
    }
}

// Construir resultado final en HTML
$resultadoHTML = "<h3>🏆 Resultado del combate</h3>";
if (!$orco->estaVivo()) {
    $resultadoHTML .= "<p class='ganador'>¡Gandalf gana el combate en $turno turnos! 🎉</p>";
    $expGanada = rand(20, 30);
    $gandalf->ganarExp($expGanada);
} elseif (!$gandalf->estaVivo()) {
    $resultadoHTML .= "<p class='perdedor'>💀 El Orco ha derrotado a Gandalf.</p>";
} else {
    $resultadoHTML .= "<p class='empate'>⚖️ El combate terminó sin un ganador claro.</p>";
}

// Preparar datos JSON
$response = [
    'mensajes' => Logger::getMessages(),
    'resultadoHTML' => $resultadoHTML,
    'estadoFinal' => [
        'gandalf' => ['vida' => $gandalf->vida, 'mana' => $gandalf->mana],
        'orco' => ['vida' => $orco->vida, 'mana' => $orco->mana]
    ]
];

header('Content-Type: application/json');
echo json_encode($response);
?>