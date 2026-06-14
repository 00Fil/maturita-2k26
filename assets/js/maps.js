/* ============================================================================
   maps.js — App "Mappe" del desktop (finestra #w-fine)
   ----------------------------------------------------------------------------
   Estratto dallo <script> inline di hub.php. Gestisce la navigazione
   "turn-by-turn": tappe, camera animata, percorso, elenco indicazioni, zoom
   e bussola. Dipende da desktop.js (sndOpen, closeWin): caricare desktop.js PRIMA.
   ========================================================================== */
(function () {
  var map = document.getElementById("mv-map");
  var cam = document.getElementById("mv-cam");
  var puck = document.getElementById("mv-puck");
  var trav = document.getElementById("mv-trav");
  if (!map || !cam || !puck) return;

  /* Le tappe del "viaggio": dal diploma alla meta all'estero. */
  var stops = [
    { x: 170, y: 520, p: 0, m: "m0", name: "Maturit\u00e0 al Cerebotani", kind: "Partenza", kk: "Partenza", body: "\u00c8 il punto di partenza. In cinque anni al Cerebotani ho imparato a programmare e a risolvere i problemi con metodo. Da qui parte la strada che ho in mente per il dopo." },
    { x: 390, y: 430, p: 0.30, m: "m1", name: "PCTO \u00b7 CS Metal Europe", kind: "Sosta", kk: "Sosta lungo la strada", body: "Una sosta lungo il tragitto, come il rifornimento prima di un viaggio lungo. In azienda ho lavorato sui dati, sulla comunicazione e su un e-commerce vero, fino al mio primo contratto. Qui ho capito quale direzione voglio prendere." },
    { x: 610, y: 300, p: 0.63, m: "m2", name: "Universit\u00e0 \u00b7 Informatica", kind: "Tappa", kk: "La prossima tappa", body: "Voglio continuare a studiare informatica. Mi serve per costruire software con basi pi\u00f9 solide e arrivare preparato al lavoro." },
    { x: 850, y: 150, p: 1, m: "m3", name: "Lavorare all'estero", kind: "Arrivo", kk: "La meta del viaggio", body: "La meta del viaggio. Voglio portare quello che ho imparato fuori dall'Italia e lavorare nel software in un contesto internazionale." }
  ];

  var list = document.getElementById("nv-list");
  var elKk = document.getElementById("nv-kk");
  var elBody = document.getElementById("nv-body");
  var elStep = document.getElementById("nv-step");
  var elEta = document.getElementById("nv-eta");
  var elHint = document.getElementById("nv-hint");
  var i = 0, n = stops.length, j;

  /* Costruisce la lista delle tappe nel pannello indicazioni. */
  for (j = 0; j < n; j++) {
    var s = stops[j];
    var col = j === 0 ? "#34C759" : (j === n - 1 ? "#FF3B30" : "#8E8E93");
    var ct = (j === 0 || j === n - 1) ? "" : String(j);
    var b = document.createElement("button");
    b.className = "dir-row";
    b.setAttribute("data-i", j);
    b.innerHTML =
      '<span class="dir-pin" style="--c:' + col + '">' + ct + "</span>" +
      '<span class="dir-txt"><b>' + s.name + "</b><small>" + s.kind + "</small></span>";
    list.appendChild(b);
  }

  /* Parametri della camera (scala base, limiti del riquadro visibile). */
  var sc = 1.16, zoom = 1, VW = 1000, VH = 640, TX = 600, TY = 320;
  var minX = -300, maxX = 1300, minY = -260, maxY = 900;

  /* Disegna lo stato corrente: puck, percorso, camera, testi e marker attivi. */
  function render() {
    var s = stops[i], k, e = sc * zoom;
    puck.setAttribute("transform", "translate(" + s.x + " " + s.y + ")");
    trav.setAttribute("stroke-dashoffset", (1 - s.p).toFixed(4));
    var dx = TX - e * s.x, dy = TY - e * s.y;
    dx = Math.max(VW - e * maxX, Math.min(-e * minX, dx));
    dy = Math.max(VH - e * maxY, Math.min(-e * minY, dy));
    cam.setAttribute("transform", "translate(" + dx.toFixed(1) + " " + dy.toFixed(1) + ") scale(" + e.toFixed(3) + ")");
    elKk.textContent = s.kk;
    elBody.textContent = s.body;
    elStep.textContent = (i + 1) + "/" + n;
    elEta.textContent = (i === n - 1) ? "sei arrivato" : "prossima: " + stops[i + 1].kind.toLowerCase();
    elHint.textContent = (i === n - 1) ? "Tocca per ricominciare" : "Tocca la mappa per proseguire";
    var mks = cam.querySelectorAll(".mk");
    for (k = 0; k < mks.length; k++) mks[k].classList.remove("on");
    var cur = document.getElementById(s.m);
    if (cur) cur.classList.add("on");
    var rows = list.children;
    for (k = 0; k < rows.length; k++) rows[k].className = "dir-row" + (k === i ? " on" : "");
  }

  /* Va alla tappa indicata (con wrap-around) e suona la transizione. */
  function go(ni) {
    i = ((ni % n) + n) % n;
    render();
    if (typeof sndOpen === "function") { try { sndOpen(); } catch (e) {} }
  }

  map.addEventListener("click", function () { go(i + 1); });
  list.addEventListener("click", function (e) {
    var t = e.target.closest(".dir-row");
    if (!t) return;
    go(parseInt(t.getAttribute("data-i"), 10));
  });

  /* Controlli di zoom e bussola (reset a nord/zoom 1). */
  var zin = document.getElementById("mv-zin");
  var zout = document.getElementById("mv-zout");
  var comp = document.getElementById("mv-comp");
  if (zin) zin.addEventListener("click", function (ev) { ev.stopPropagation(); zoom = Math.min(1.5, zoom + 0.18); render(); });
  if (zout) zout.addEventListener("click", function (ev) { ev.stopPropagation(); zoom = Math.max(0.8, zoom - 0.18); render(); });
  if (comp) comp.addEventListener("click", function (ev) { ev.stopPropagation(); zoom = 1; render(); });

  /* La X chiude la finestra Mappe. closeWin accetta sia l'id sia l'elemento. */
  var x = document.querySelector("#w-fine .dir-x");
  if (x) x.addEventListener("click", function (ev) {
    ev.stopPropagation();
    if (typeof closeWin === "function") closeWin("w-fine");
  });

  render();
})();
