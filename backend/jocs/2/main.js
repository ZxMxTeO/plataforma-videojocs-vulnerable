let score = 0
let level = 1
let vidas = 3
let aparecion = 1000
let vidaObjeto = 3000
let jugando = false
let intervalo

function loadProgress() {
    let l = localStorage.getItem("level")
    let s = localStorage.getItem("score")
    if (l) level = parseInt(l)
    if (s) score = parseInt(s)
    document.getElementById("level").textContent = level
    document.getElementById("score").textContent = score
}

function saveProgress() {
    localStorage.setItem("level", level)
    localStorage.setItem("score", score)
}

function resetGame() {
    level = 1
    score = 0
    vidas = 3
    saveProgress()
    document.getElementById("level").textContent = level
    document.getElementById("score").textContent = score
    clearInterval(intervalo)
    document.getElementById("game-area").innerHTML = ""
    jugando = false
}

function startGame() {
    if (jugando) return
    jugando = true
    clearInterval(intervalo)
    intervalo = setInterval(crearObjeto, aparecion - (level * 80))
}

function crearObjeto() {
    let area = document.getElementById("game-area")
    let o = document.createElement("div")
    o.className = "objeto"
    o.style.left = Math.random() * (area.clientWidth - 50) + "px"
    o.style.top = Math.random() * (area.clientHeight - 50) + "px"

    o.onclick = () => {
        o.remove()
        score++
        document.getElementById("score").textContent = score
        if (score % 5 === 0) {
            level++
            document.getElementById("level").textContent = level
        }
        saveProgress()
    }

    area.appendChild(o)

    setTimeout(() => {
        if (area.contains(o)) {
            o.remove()
            vidas--
            if (vidas <= 0) {
                resetGame()
            }
        }
    }, vidaObjeto)
}

document.getElementById("start-btn").onclick = startGame

let btn = document.createElement("button")
btn.textContent = "Reiniciar"
btn.onclick = resetGame
document.getElementById("game-container").appendChild(btn)

loadProgress()
