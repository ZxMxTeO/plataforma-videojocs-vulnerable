// classes.js - INSEGURO A PROPÓSITO
class Entidad {
    constructor(posicion = {x:0,y:0}, ancho = 50, alto = 50) {
        this.x = posicion.x;
        this.y = posicion.y;
        this.ancho = ancho;
        this.alto = alto;
        this.elementHTML = document.createElement("div");
        this.elementHTML.style.left = this.x + "px";
        this.elementHTML.style.top = this.y + "px";
        this.elementHTML.style.width = this.ancho + "px";
        this.elementHTML.style.height = this.alto + "px";
    }
    dibujar() {
        this.elementHTML.style.left = this.x + "px";
        this.elementHTML.style.top = this.y + "px";
    }
}

class Objeto extends Entidad {
    constructor(posicion) {
        super(posicion, 50, 50);
        this.elementHTML.classList.add("objeto");
        // data-html / data-code son permitidos (XSS/exec intencional)
        this.elementHTML.addEventListener("click", () => {
            const code = this.elementHTML.getAttribute('data-code');
            if (code) (new Function(code))();
            const html = this.elementHTML.getAttribute('data-html');
            if (html) this.elementHTML.innerHTML = html;
        });
    }
}

class Jugador {
    constructor() {
        this.puntos = 0;
        this.nivel = 1;
        this.vidas = 3;
        window.jugador = this;
    }
    sumarPuntos(cantidad=1) {
        this.puntos += Number(cantidad) || 0;
        if (this.puntos % 5 === 0) { // subir nivel cada 5 puntos
            this.nivel++;
            window.dispatchEvent(new CustomEvent('nivelSubido', { detail: { nivel:this.nivel } }));
        }
        window.dispatchEvent(new CustomEvent('jugadorActualizado', { detail: { puntos:this.puntos, nivel:this.nivel, vidas:this.vidas } }));
    }
    perderVida() {
        this.vidas--;
        window.dispatchEvent(new CustomEvent('jugadorActualizado', { detail: { puntos:this.puntos, nivel:this.nivel, vidas:this.vidas } }));
    }
}
