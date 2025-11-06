// --------- Pantalla del Joc ---------
const pantalla = document.querySelector("#pantalla");
const infoPartida = document.querySelector("#infoPartida");
const pantallaAmple = window.innerWidth;
const pantallaAlt = window.innerHeight;
const fotogrames = 1000 / 60; // 60 FPS aproximadament

// ---------- Variables per la gestió de la partida --------
const maxPunts = (window.config && window.config.puntuacio_final) || 100;
const vectorAsteroides = [];
const vectorEnemics = [];
const maxAsteroides = 100;

// 🔹 Control del joc
let jocAcabat = false;
let intervalJoc = null;
let partidaGuardada = false;

// ---- Objecte Jugador ----
const jugador = new Jugador(
  window.nomUsuari,
  window.config.vides || 3,
  window.config.velocitat || 5,
  { x: 100, y: 300 },
  150,
  100
);
pantalla.append(jugador.elementHTML);

// ---- Vector d'objectes Enemic ----
for (let i = 0; i < (window.config.enemics || 3); i++) {
  let posX = pantallaAmple + 50;
  let posY = Math.floor(Math.random() * (pantallaAlt - 50));
  let velocitat = Math.floor(Math.random() * 5) + 1;
  vectorEnemics.push(
    new Enemic(jugador, velocitat, { x: posX, y: posY }, 50, 50)
  );
  pantalla.append(vectorEnemics[i].elementHTML);
}

// ---- Vector d'objectes Asteroides ----
for (let i = 0; i < maxAsteroides; i++) {
  let posX = Math.floor(Math.random() * pantallaAmple - 3);
  let posY = Math.floor(Math.random() * pantallaAlt - 3);
  let velocitat = Math.floor(Math.random() * 10) + 1;
  vectorAsteroides.push(new Asteroide(velocitat, { x: posX, y: posY }, 3, 3));
  pantalla.append(vectorAsteroides[i].elementHTML);
}

// ------- Informació de la partida -------
infoPartida.innerHTML = "";
const elementNom = document.createElement("p");
const elementNivell = document.createElement("p");
const elementVides = document.createElement("p");
const elementEnemics = document.createElement("p");
const elementPunts = document.createElement("p");
const elementKills = document.createElement("p");

elementNom.textContent = `Jugador: ${jugador.nom}`;
elementNivell.textContent = `Nivel: ${nivell}`;
elementVides.textContent = `Vidas: ${window.config.vides ?? "?"}`;
elementEnemics.textContent = `Enemigos: ${window.config.enemics ?? "?"}`;
elementPunts.textContent = `Puntos: ${jugador.punts}`;
elementKills.textContent = `Kills: ${jugador.derribats}`;

infoPartida.append(
  elementNom,
  elementNivell,
  elementVides,
  elementEnemics,
  elementPunts,
  elementKills
);

// ----- Esdeveniments de teclat -----
window.addEventListener("keydown", (event) => {
  if (jocAcabat) return; // ❌ No fer res si la partida ha acabat
  switch (event.code) {
    case "ArrowUp":
      jugador.y -= jugador.velocitat;
      break;
    case "ArrowDown":
      jugador.y += jugador.velocitat;
      break;
  }
});

// ----- Funció per guardar la partida -----
function guardarPartida(finalitzat = false) {
  if (partidaGuardada) return; // Evita múltiples inserts
  partidaGuardada = true;

  const dades = {
    usuari_id: window.usuariId,
    joc_id: 1,
    nivell_jugat: nivell,
    puntuacio_obtinguda: jugador.punts,
    vidas: jugador.vides,
  };

  fetch("./index.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ guardar_partida: true, ...dades }),
  })
    .then((r) => r.json())
    .then((res) => console.log("💾 Partida guardada:", res))
    .catch((err) => console.error("Error guardando partida:", err));
}

// ----- Comprovació de Col·lisions -----
function comprovarCollisions() {
  if (jocAcabat) return;

  vectorEnemics.forEach((enemic) => {
    if (
      jugador.x <= enemic.x + enemic.ample &&
      jugador.x + jugador.ample >= enemic.x &&
      jugador.y <= enemic.y + enemic.alt &&
      jugador.y + jugador.alt >= enemic.y
    ) {
      enemic.x = pantallaAmple + enemic.ample;
      jugador.punts += Math.floor(10 * (1 + (nivell - 1) * 0.1));
      jugador.derribats++;
      elementPunts.textContent = `Puntos: ${jugador.punts}`;
      elementKills.textContent = `Kills: ${jugador.derribats}`;

      if (jugador.punts >= maxPunts) {
        jocAcabat = true;
        jugador.velocitat = 0;
        vectorEnemics.forEach((e) => (e.velocitat = 0));

        guardarPartida(true);

        alert("¡Nivel superado! 🎉");

        const seguentNivell = nivell + 1;
        console.log("🔍 Comprovant si existeix nivell", seguentNivell);

        fetch(
          `http://192.168.1.144/backend/api.php/jocs/1/nivells/${seguentNivell}`
        )
          .then((res) => (res.ok ? res.json() : null))
          .then((data) => {
            const nivellData = Array.isArray(data) ? data[0] : data;

            if (!nivellData || !nivellData.puntuacio_final) {
              alert(
                "🎮 ¡Felicidades! Has completado todos los niveles 🏆"
              );
              setTimeout(
                () => (window.location.href = "./../../plataforma.php"),
                4000
              );
              return;
            }

            // ✅ Si existeix el següent nivell
            fetch("./index.php", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({
                usuari_id: window.usuariId,
                nivell_actual: seguentNivell,
              }),
            })
              .then((r) => r.json())
              .then((d) => {
                if (d.ok) {
                  alert("Passant al següent nivell...");
                  window.location.href = `./index.php?nivell=${seguentNivell}`;
                } else {
                  alert("Error en actualitzar el nivell!");
                }
              });
          })
          .catch((err) => {
            console.error("Error comprobando el siguiente nivel:", err);
            alert("🎮 ¡Felicidades! Has completado todos los niveles 🏆");
            setTimeout(
              () => (window.location.href = "./../../plataforma.php"),
              4000
            );
          });
      }
    }
  });
}

// ----- Bucle d'animació -----
intervalJoc = setInterval(() => {
  if (jocAcabat) return;

  comprovarCollisions();

  elementVides.textContent = `Vides: ${jugador.vides}`;

  // 🔴 Si el jugador es queda sense vides
  if (jugador.vides <= 0 && !jocAcabat) {
    jocAcabat = true;
    jugador.velocitat = 0;
    vectorEnemics.forEach((e) => (e.velocitat = 0));

    guardarPartida(true);

    alert("💀 ¡Has perdido! Buena suerte a la proxima");
    clearInterval(intervalJoc);

    setTimeout(() => {
      window.location.href = `./index.php?nivell=${nivell}`;
    }, 3000);
    return;
  }

  jugador.dibuixar();
  jugador.moure();

  vectorEnemics.forEach((enemic) => {
    enemic.dibuixar();
    enemic.moure();
  });

  vectorAsteroides.forEach((asteroide) => {
    asteroide.dibuixar();
    asteroide.moure();
  });
}, fotogrames);
