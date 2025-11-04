// main.js - Versión insegura pero funcional (corregida)
let score = 0;
let level = 1;
let vidas = 3;
let aparecion = 1000;
let vidaObjeto = 3000;
let jugando = false;
let intervalo;
let gameOver = false; // 🔒 evita múltiples guardados

// Al cargar la página
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("start-btn").onclick = startGame;
    document.getElementById("reset-btn").onclick = resetGame;
    cargarProgreso();
});

async function cargarProgreso() {
    try {
        const res = await fetch("game-config.php?usuari=" + encodeURIComponent(jugadorNombre));
        const data = await res.json();

        score = Number(data.puntos) || 0;
        level = Number(data.nivel) || 1;
        vidas = Number(data.vidas) || 3;

        actualizarHUD();

        // Pedimos dificultad según nivel actual
        const dif = await (await fetch("difficulty.php?nivel=" + level)).json();
        aparecion = dif.aparecion ?? aparecion;
        vidaObjeto = dif.vidaObjeto ?? vidaObjeto;

        console.log("Progreso inicial:", data, "Dificultad:", dif);
    } catch (err) {
        console.error("Error cargando progreso:", err);
    }
}

function actualizarHUD() {
    document.getElementById("score").textContent = score;
    document.getElementById("level").textContent = level;
    document.getElementById("vidas").textContent = vidas;
}

function startGame() {
    if (jugando) return;
    jugando = true;
    gameOver = false;
    clearInterval(intervalo);
    intervalo = setInterval(crearObjeto, Math.max(300, aparecion - (level * 100)));
}

function resetGame() {
    jugando = false;
    gameOver = false;
    clearInterval(intervalo);
    document.getElementById("game-area").innerHTML = "";
    score = 0;
    level = 1;
    vidas = 3;
    actualizarHUD();
}

function crearObjeto() {
    const area = document.getElementById("game-area");
    const o = document.createElement("div");
    o.className = "objeto";
    o.style.left = Math.random() * (area.clientWidth - 50) + "px";
    o.style.top = Math.random() * (area.clientHeight - 50) + "px";
    area.appendChild(o);

    o.onclick = async () => {
        if (gameOver) return; // no hacer nada si ya perdiste
        o.remove();
        score++;

        // Subir de nivel cada 5 puntos
        if (score % 5 === 0) {
            level++;
            actualizarHUD();

            // Pedir nueva dificultad según nivel
            try {
                const res = await fetch("difficulty.php?nivel=" + level);
                const cfg = await res.json();
                aparecion = cfg.aparecion ?? aparecion;
                vidaObjeto = cfg.vidaObjeto ?? vidaObjeto;
                console.log("Nueva dificultad:", cfg);
            } catch (err) {
                console.error("Error obteniendo dificultad:", err);
            }

            // Reiniciar el intervalo con la nueva dificultad
            if (jugando) {
                clearInterval(intervalo);
                intervalo = setInterval(crearObjeto, Math.max(200, aparecion - (level * 50)));
            }
        } else {
            actualizarHUD();
        }
    };

    // Si no se hace clic a tiempo, pierde vida
    setTimeout(() => {
        if (area.contains(o) && !gameOver) {
            o.remove();
            vidas--;
            actualizarHUD();

            if (vidas <= 0 && !gameOver) {
                // Solo guardar UNA VEZ el progreso final
                gameOver = true;
                jugando = false;
                clearInterval(intervalo);
                alert("¡Game Over!");
                guardarProgreso("game_over").then(() => resetGame());
            }
        }
    }, vidaObjeto);
}

async function guardarProgreso(motivo = "manual") {
    try {
        const payload = {
            jugador: jugadorNombre,
            nivel: level,
            puntos: score,
            vidas,
            motivo
        };

        const res = await fetch("update-game.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        const text = await res.text();
        console.log("Respuesta cruda del servidor:", text);

        let j;
        try {
            j = JSON.parse(text);
        } catch {
            console.error("Respuesta no válida JSON, ver PHP output:", text);
            return;
        }

        console.log("Guardar progreso respuesta:", j);
    } catch (err) {
        console.error("Error guardando progreso:", err);
    }
}
