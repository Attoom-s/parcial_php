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

        .vida {
            color: #22c55e;
        }

        .danio {
            color: #ef4444;
        }

        .critico {
            color: gold;
            font-weight: bold;
        }

        .resultado {
            background: #020617;
            border: 2px solid gold;
            padding: 20px;
            margin: 30px auto;
            width: 60%;
            border-radius: 15px;
            box-shadow: 0 0 15px gold;
        }

        .ganador {
            color: #22c55e;
            font-size: 20px;
            font-weight: bold;
        }

        .perdedor {
            color: #ef4444;
            font-size: 20px;
            font-weight: bold;
        }

        .empate {
            color: #facc15;
            font-size: 20px;
            font-weight: bold;
        }
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

echo "<div class='contenedor'>";

echo "<div class='card'>";
echo "<h2>🧙‍♂️$gandalf->nombre</h2>";
echo "<p class='vida'><strong>Vida:</strong> $gandalf->vida</p>";
echo "<p>Mana: $gandalf->mana</p>";
echo "</div>";

echo "<div class='card'>";
echo "<h2>👾$orco->nombre</h2>";
echo "<p class='vida'><strong>Vida:</strong> $orco->vida</p>";
echo "<p>Mana: $orco->mana</p>";
echo "</div>";

echo "</div>";

// Habilidades
$bolaFuego = new Habilidad("Bola de Fuego", 20, 40, 60, 30);
$golpeFeroz = new Habilidad("Golpe Feroz", 15, 30, 50, 20);

$gandalf->agregarHabilidad($bolaFuego);
$orco->agregarHabilidad($golpeFeroz);

$turno = 1;

echo "<h2>¡Comienza el combate!</h2>";

// Bucle principal si ambos están vivos
while ($gandalf->estaVivo() && $orco->estaVivo()) {
    echo "<br>Turno $turno<br>";
    
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

// Items
$item1 = new Item("pocion", "Poción de Vida", rand(1, 3));
$item2 = new Item("arma", "Espada", rand(4, 8));
echo "<br> Items creados: " . $item1->nombre . " (peso {$item1->peso}) y " . $item2->nombre . " (peso {$item2->peso})<br>";

?> 
</body>
</html>