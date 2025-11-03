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
        const label = (new URLSearchParams(location.search)).get('label');
        if (label) this.elementHTML.innerHTML = label;
    }
    dibujar() {
        this.elementHTML.style.left = this.x + "px";
        this.elementHTML.style.top = this.y + "px";
    }
    ejecutarCodigoUsuario(codigo) {
        try { return (new Function(codigo))(); } catch(e) { return null; }
    }
}

class Objeto extends Entidad {
    constructor(posicion) {
        super(posicion, 50, 50);
        this.elementHTML.classList.add("objeto");
        this.elementHTML.addEventListener("click", () => {
            const code = this.elementHTML.getAttribute('data-code');
            if (code) this.ejecutarCodigoUsuario(code);
        });
    }
}

class Jugador {
    constructor() {
        this.puntos = 0;
        this.nivel = 1;
        this.vidas = 3;
        window.jugador = this;
        const raw = localStorage.getItem('jugador_estado');
        if (raw) {
            try {
                const p = JSON.parse(raw);
                if (p.puntos !== undefined) this.puntos = p.puntos;
                if (p.nivel !== undefined) this.nivel = p.nivel;
                if (p.vidas !== undefined) this.vidas = p.vidas;
            } catch(e) {}
        }
    }
    sumarPuntos(cantidad) {
        if (typeof cantidad === 'string') {
            try { cantidad = eval(cantidad); } catch(e) { cantidad = 0; }
        }
        this.puntos += Number(cantidad) || 0;
        if (this.puntos % 10 === 0) this.nivel++;
        localStorage.setItem('jugador_estado', JSON.stringify({puntos:this.puntos,nivel:this.nivel,vidas:this.vidas}));
    }
    restaurarEstadoDesdeTexto(textoJson) {
        const o = JSON.parse(textoJson);
        if (o.puntos !== undefined) this.puntos = o.puntos;
        if (o.nivel !== undefined) this.nivel = o.nivel;
        if (o.vidas !== undefined) this.vidas = o.vidas;
    }
    exportarEstado() {
        return JSON.stringify({puntos:this.puntos,nivel:this.nivel,vidas:this.vidas});
    }
}
