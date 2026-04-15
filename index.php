<!DOCTYPE html>
<html>
<head>
    <title>Batalla RPG - Chat de Combate</title>
    <style>
        body {
            background: linear-gradient(to bottom, #0f172a, #1e293b);
            color: white;
            font-family: 'Courier New', monospace;
            text-align: center;
            padding: 20px;
        }
        .card {
            background: #1e293b;
            padding: 15px;
            margin: 10px;
            width: 300px;
            border-radius: 10px;
            box-shadow: 0 0 10px #000;
            display: inline-block;
        }
        .contenedor {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .vida { color: #22c55e; }
        .danio { color: #ef4444; }
        .critico { color: gold; font-weight: bold; }
        .chat-container {
            background: #0f172a;
            border: 2px solid #facc15;
            border-radius: 15px;
            width: 80%;
            max-width: 800px;
            margin: 30px auto;
            padding: 15px;
            text-align: left;
            font-family: monospace;
            font-size: 14px;
            box-shadow: 0 0 15px rgba(250, 204, 21, 0.3);
        }
        .chat-log {
            height: 400px;
            overflow-y: auto;
            padding: 10px;
            background: #020617;
            border-radius: 10px;
        }
        .chat-log p {
            margin: 5px 0;
            border-left: 3px solid #facc15;
            padding-left: 10px;
        }
        .resultado {
            background: #020617;
            border: 2px solid gold;
            padding: 20px;
            margin: 20px auto;
            width: 60%;
            border-radius: 15px;
        }
        .ganador { color: #22c55e; font-size: 20px; font-weight: bold; }
        .perdedor { color: #ef4444; font-size: 20px; font-weight: bold; }
        .empate { color: #facc15; font-size: 20px; font-weight: bold; }
        button {
            background: #facc15;
            color: #0f172a;
            border: none;
            padding: 8px 20px;
            font-weight: bold;
            border-radius: 20px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #eab308;
        }
        h1, h2 {
            text-shadow: 0 0 5px gold;
        }
    </style>
</head>
<body>

<h1>⚔️ BATALLA RPG - MODO CHAT ⚔️</h1>

<div class="contenedor">
    <div class="card">
        <h2>🧙‍♂️ Gandalf</h2>
        <p class="vida"><strong>Vida:</strong> <span id="gandalf-vida">150</span></p>
        <p>Mana: <span id="gandalf-mana">200</span></p>
    </div>
    <div class="card">
        <h2>👾 Orco</h2>
        <p class="vida"><strong>Vida:</strong> <span id="orco-vida">200</span></p>
        <p>Mana: <span id="orco-mana">150</span></p>
    </div>
</div>

<div class="chat-container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h3>📜 Registro del combate</h3>
        <button id="reiniciarBtn">🔄 Reiniciar batalla</button>
    </div>
    <div id="chatLog" class="chat-log">
        <p>✨ El combate comenzará en breve...</p>
    </div>
</div>

<div id="resultadoFinal" class="resultado" style="display: none;"></div>

<script>
// Almacenará los mensajes generados por PHP
let mensajes = [];
let indiceActual = 0;
let intervalo = null;
let combateActivo = true;

// Función para mostrar un nuevo mensaje en el chat
function mostrarSiguienteMensaje() {
    if (indiceActual < mensajes.length) {
        const chatDiv = document.getElementById('chatLog');
        const nuevoMensaje = document.createElement('p');
        nuevoMensaje.innerHTML = mensajes[indiceActual];
        chatDiv.appendChild(nuevoMensaje);
        chatDiv.scrollTop = chatDiv.scrollHeight;
        indiceActual++;
    } else {
        // Fin de los mensajes, detener intervalo
        if (intervalo) clearInterval(intervalo);
        intervalo = null;
        combateActivo = false;
    }
}

// Función para cargar los datos de la batalla vía AJAX (sin recargar la página)
function iniciarBatalla() {
    // Limpiar chat y resultado
    const chatDiv = document.getElementById('chatLog');
    chatDiv.innerHTML = '<p>⚔️ Iniciando nueva batalla...</p>';
    document.getElementById('resultadoFinal').style.display = 'none';
    document.getElementById('resultadoFinal').innerHTML = '';
    
    // Resetear índices
    indiceActual = 0;
    if (intervalo) clearInterval(intervalo);
    
    // Petición fetch para obtener los mensajes y el resultado
    fetch('batalla_api.php')
        .then(response => response.json())
        .then(data => {
            mensajes = data.mensajes;
            indiceActual = 0;
            // Mostrar mensajes cada 0.8 segundos
            intervalo = setInterval(mostrarSiguienteMensaje, 2000);
            
            // Actualizar las barras de vida/mana según el estado final (opcional)
            if (data.estadoFinal) {
                document.getElementById('gandalf-vida').innerText = data.estadoFinal.gandalf.vida;
                document.getElementById('gandalf-mana').innerText = data.estadoFinal.gandalf.mana;
                document.getElementById('orco-vida').innerText = data.estadoFinal.orco.vida;
                document.getElementById('orco-mana').innerText = data.estadoFinal.orco.mana;
            }
            
            // Mostrar resultado final cuando terminen los mensajes
            const checkResultado = setInterval(() => {
                if (!intervalo && indiceActual >= mensajes.length) {
                    clearInterval(checkResultado);
                    const resultadoDiv = document.getElementById('resultadoFinal');
                    resultadoDiv.style.display = 'block';
                    resultadoDiv.innerHTML = data.resultadoHTML;
                }
            }, 100);
        })
        .catch(error => {
            console.error('Error al cargar la batalla:', error);
            chatDiv.innerHTML += '<p style="color:red">❌ Error al cargar el combate. Recarga la página.</p>';
        });
}

// Al cargar la página, iniciar automáticamente
window.onload = () => {
    iniciarBatalla();
    document.getElementById('reiniciarBtn').addEventListener('click', () => {
        iniciarBatalla();
    });
};
</script>

</body>
</html>