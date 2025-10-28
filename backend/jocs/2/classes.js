// Clase base para objetos
class Entidad {
    constructor(posicion = {x:0, y:0}, ancho = 50, alto = 50) {
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

// Objeto que hay que atrapar
class Objeto extends Entidad {
    constructor(posicion) {
        super(posicion, 50, 50);
        this.elementHTML.classList.add("objeto");
    }
}

// Jugador (solo puntos y niveles)
class Jugador {
    constructor() {
        this.puntos = 0;
        this.nivel = 1;
    }

    sumarPuntos(cantidad) {
        this.puntos += cantidad;
        if (this.puntos % 10 === 0) {
            this.nivel++;
        }
    }
}
