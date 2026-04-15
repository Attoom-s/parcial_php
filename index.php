<!DOCTYPE html>
<html>
<head>
    <title>Batalla RPG</title>
    <style>
        body {
            background: linear-gradient(to bottom, #0f172a, #1e293b);
            color: white;
            font-family: Arial;
            text-align: center;
        }
        .card {
            background: #1e293b;
            padding: 15px;
            margin: 10px;
            width: 300px;
            border-radius: 10px;
            box-shadow: 0 0 10px #000;
        }
        .contenedor {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .vida { color: #22c55e; }
        .danio { color: #ef4444; }
        .critico { color: gold; font-weight: bold; }
        .resultado {
            background: #020617;
            border: 2px solid gold;
            padding: 20px;
            margin: 30px auto;
            width: 60%;
            border-radius: 15px;
            box-shadow: 0 0 15px gold;
        }
        .ganador { color: #22c55e; font-size: 20px; font-weight: bold; }
        .perdedor { color: #ef4444; font-size: 20px; font-weight: bold; }
        .empate { color: #facc15; font-size: 20px; font-weight: bold; }
    </style>
</head>
<body>

<h1>⚔️ Batalla RPG ⚔️</h1>
<?php
require_once "Personaje.php";
require_once "Habilidad.php";
require_once "Quemadura.php";
require_once "Item.php";

// Crear personajes
$gandalf = new Personaje("Gandalf", 150, 200);
$orco = new Personaje("Orco", 200, 150); 

// Crear items
$pocionG = new Item("pocion", "Poción de Vida", rand(1, 3));
$espada = new Item("arma", "Espada", rand(4, 8));
$pocionO = new Item("pocion", "Poción de Orco", rand(1, 3));

$gandalf->agregarItem($pocionG);
$gandalf->agregarItem($espada);
$orco->agregarItem($pocionO);

// Mostrar estado inicial
echo "<div class='contenedor'>";
echo "<div class='card'><h2>🧙‍♂️$gandalf->nombre</h2><p class='vida'><strong>Vida:</strong> $gandalf->vida</p><p>Mana: $gandalf->mana</p></div>";
echo "<div class='card'><h2>👾$orco->nombre</h2><p class='vida'><strong>Vida:</strong> $orco->vida</p><p>Mana: $orco->mana</p></div>";
echo "</div>";

// Habilidades: Bola de Fuego tiene 30% de quemadura (daño 10 por turno)
$bolaFuego = new Habilidad("Bola de Fuego", 20, 40, 60, 30, 30, 10);
$golpeFeroz = new Habilidad("Golpe Feroz", 15, 30, 50, 20);

$gandalf->agregarHabilidad($bolaFuego);
$orco->agregarHabilidad($golpeFeroz);

$turno = 1;
echo "<h2>¡Comienza el combate!</h2>";

// Bucle principal
while ($gandalf->estaVivo() && $orco->estaVivo()) {
    echo "<br>Turno $turno<br>";

    // ---------- APLICAR EFECTOS (quemadura) A AMBOS ----------
    echo "<strong>⚡ Efectos de estado:</strong><br>";
    $gandalf->aplicarEfectos();
    $orco->aplicarEfectos();

    // Si alguien muere por quemadura, salimos
    if (!$gandalf->estaVivo() || !$orco->estaVivo()) break;

    // ---------- TURNO DE GANDALF ----------
    echo "<strong>Fase de Gandalf:</strong><br>";
    $accionRealizada = false;

    // Intenta usar una poción si vida < 40%
    if ($gandalf->vida < $gandalf->vidaMax * 0.4) {
        foreach ($gandalf->items as $i => $item) {
            if ($item->tipo == 'pocion') {
                $gandalf->usarItem($i);
                $accionRealizada = true;
                break;
            }
        }
    }

    // Si no usó poción y tiene un arma, puede usarla para potenciar el próximo ataque
    if (!$accionRealizada && $gandalf->danoExtra == 0) {
        foreach ($gandalf->items as $i => $item) {
            if ($item->tipo == 'arma') {
                $gandalf->usarItem($i);
                $accionRealizada = true;
                break;
            }
        }
    }

    // Si no usó ningún item, ataca con habilidad
    if (!$accionRealizada) {
        if ($gandalf->mana >= $bolaFuego->costoBase) {
            $gandalf->usarHabilidad("Bola de Fuego", $orco);
        } else {
            echo "Gandalf no tiene suficiente mana para atacar.<br>";
        }
    }

    if (!$orco->estaVivo()) {
        echo "<br>¡Gandalf ha derrotado al Orco!<br>";
        break;
    }

    // ---------- TURNO DEL ORCO ----------
    echo "<strong>Fase del Orco:</strong><br>";
    $accionRealizada = false;

    // Orco usa poción si vida baja
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
        echo "El Orco no tiene suficiente mana para atacar.<br>";
    }

    // Mostrar estado después del turno
    echo "<br><strong>Estado del combate:</strong><br>";
    echo "{$gandalf->nombre} Vida: {$gandalf->vida} | Mana: {$gandalf->mana}<br>";
    echo "{$orco->nombre} Vida: {$orco->vida} | Mana: {$orco->mana}<br>";
    
    $turno++;
    if ($turno >= 10) {
        echo "<br>¡Límite de turnos alcanzado! Combate empatado.<br>";
        break;
    }
}

// Resultado final
echo "<div class='resultado'>";
echo "<h3>🏆 Resultado del combate</h3>";
if (!$orco->estaVivo()) {
    echo "<p class='ganador'>¡Gandalf gana el combate en $turno turnos! 🎉</p>";
    $expGanada = rand(20, 30);
    $gandalf->ganarExp($expGanada);
} elseif (!$gandalf->estaVivo()) {
    echo "<p class='perdedor'>💀 El Orco ha derrotado a Gandalf.</p>";
} else {
    echo "<p class='empate'>⚖️ El combate terminó sin un ganador claro.</p>";
}
echo "</div>";

// Mostrar items restantes para depuración
echo "<br>📦 Inventario final de Gandalf: ";
foreach ($gandalf->items as $item) echo $item->nombre . " (peso {$item->peso}) ";
echo "<br>📦 Inventario final del Orco: ";
foreach ($orco->items as $item) echo $item->nombre . " (peso {$item->peso}) ";

?> 
</body>
</html>