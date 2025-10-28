const gameArea = document.getElementById("game-area");
const scoreDisplay = document.getElementById("score");
const levelDisplay = document.getElementById("level");
const startBtn = document.getElementById("start-btn");

let jugador = new Jugador();
let objetos = [];
let intervalo = null;

function crearObjeto() {
    const x = Math.random() * (gameArea.clientWidth - 50);
    const y = Math.random() * (gameArea.clientHeight - 50);
    const obj = new Objeto({x, y});
    gameArea.appendChild(obj.elementHTML);
    objetos.push(obj);

    // Tiempo de vida del objeto
    const tiempoVida = 3000; // 3 segundos
    const timeout = setTimeout(() => {
        if (objetos.includes(obj)) {
            // No atrapado -> resta 1 punto
            jugador.puntos = Math.max(0, jugador.puntos - 1);
            actualizarInfo();
            gameArea.removeChild(obj.elementHTML);
            objetos = objetos.filter(o => o !== obj);

            if (jugador.puntos === 0) {
                gameOver();
            }
        }
    }, tiempoVida);

    // Click en el objeto
    obj.elementHTML.addEventListener("click", () => {
        clearTimeout(timeout);
        jugador.sumarPuntos(1);
        actualizarInfo();
        gameArea.removeChild(obj.elementHTML);
        objetos = objetos.filter(o => o !== obj);
    });
}

function actualizarInfo() {
    scoreDisplay.textContent = jugador.puntos;
    levelDisplay.textContent = jugador.nivel;
}

function startGame() {
    // Reset
    jugador = new Jugador();
    actualizarInfo();
    objetos.forEach(o => gameArea.removeChild(o.elementHTML));
    objetos = [];

    if (intervalo) clearInterval(intervalo);

    intervalo = setInterval(() => {
        // Crear objetos según nivel
        const cantidad = jugador.nivel; // cada nivel genera más objetos
        for (let i = 0; i < cantidad; i++) {
            crearObjeto();
        }
    }, 1000);
}

function gameOver() {
    clearInterval(intervalo);
    alert("¡Game Over! Has llegado a 0 puntos.");
    objetos.forEach(o => {
        if (o.elementHTML.parentNode) {
            gameArea.removeChild(o.elementHTML);
        }
    });
    objetos = [];
}

startBtn.addEventListener("click", startGame);
