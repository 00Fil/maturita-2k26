/* ============================================================================
   apps/maps.js — App "Mappe": il viaggio dal diploma al lavoro all'estero
   ----------------------------------------------------------------------------
   Estratto dallo <script> inline di hub.php (contenuto e logica invariati,
   solo riordinati e commentati). Anima la camera tra le quattro tappe del
   percorso, sposta il puntatore, disegna il tracciato e aggiorna il pannello
   "Indicazioni". I marcatori .mk vivono dentro #mv-cam.
   ========================================================================== */
(function () {
  const map  = document.getElementById('mv-map');
  const cam  = document.getElementById('mv-cam');
  const puck = document.getElementById('mv-puck');
  const trav = document.getElementById('mv-trav');
  if (!map || !cam || !puck) return;

  /* Le quattro tappe del percorso (coordinate nel sistema 1000x640 dell'SVG).
     p = avanzamento del tracciato (0..1); m = id del marcatore collegato. */
  const stops = [
    { x: 170, y: 520, p: 0,    m: 'm0', name: 'Maturità al Cerebotani',   kind: 'Partenza', kk: 'Partenza',
      body: 'È il punto di partenza. In cinque anni al Cerebotani ho imparato a programmare e a risolvere i problemi con metodo. Da qui parte la strada che ho in mente per il dopo.' },
    { x: 390, y: 430, p: 0.30, m: 'm1', name: 'PCTO · CS Metal Europe',   kind: 'Sosta',    kk: 'Sosta lungo la strada',
      body: 'Una sosta lungo il tragitto, come il rifornimento prima di un viaggio lungo. In azienda ho lavorato sui dati, sulla comunicazione e su un e-commerce vero, fino al mio primo contratto. Qui ho capito quale direzione voglio prendere.' },
    { x: 610, y: 300, p: 0.63, m: 'm2', name: 'Università · Informatica', kind: 'Tappa',    kk: 'La prossima tappa',
      body: 'Voglio continuare a studiare informatica. Mi serve per costruire software con basi più solide e arrivare preparato al lavoro.' },
    { x: 850, y: 150, p: 1,    m: 'm3', name: 'Lavorare all\'estero',     kind: 'Arrivo',   kk: 'La meta del viaggio',
      body: 'La meta del viaggio. Voglio portare quello che ho imparato fuori dall\'Italia e lavorare nel software in un contesto internazionale.' }
  ];

  const list   = document.getElementById('nv-list');
  const elKk   = document.getElementById('nv-kk');
  const elBody = document.getElementById('nv-body');
  const elStep = document.getElementById('nv-step');
  const elEta  = document.getElementById('nv-eta');
  const elHint = document.getElementById('nv-hint');
  const n = stops.length;
  let i = 0;

  /* Costruisce una riga nell'elenco "Indicazioni" per ogni tappa. */
  for (let j = 0; j < n; j++) {
    const s = stops[j];
    const col = j === 0 ? '#34C759' : (j === n - 1 ? '#FF3B30' : '#8E8E93'); /* verde / rosso / grigio */
    const ct = (j === 0 || j === n - 1) ? '' : String(j);
    const b = document.createElement('button');
    b.className = 'dir-row';
    b.setAttribute('data-i', j);
    b.innerHTML = '<span class="dir-pin" style="--c:' + col + '">' + ct + '</span>' +
                  '<span class="dir-txt"><b>' + s.name + '</b><small>' + s.kind + '</small></span>';
    list.appendChild(b);
  }

  /* Parametri della camera (scala base, limiti di scorrimento, centro mira). */
  const sc = 1.16, VW = 1000, VH = 640, TX = 600, TY = 320;
  const minX = -300, maxX = 1300, minY = -260, maxY = 900;
  let zoom = 1;

  /* Disegna lo stato corrente: camera, puntatore, tracciato, testi, marcatori. */
  function render() {
    const s = stops[i];
    const e = sc * zoom;
    puck.setAttribute('transform', 'translate(' + s.x + ' ' + s.y + ')');
    trav.setAttribute('stroke-dashoffset', (1 - s.p).toFixed(4));
    let dx = TX - e * s.x, dy = TY - e * s.y;
    dx = Math.max(VW - e * maxX, Math.min(-e * minX, dx));
    dy = Math.max(VH - e * maxY, Math.min(-e * minY, dy));
    cam.setAttribute('transform', 'translate(' + dx.toFixed(1) + ' ' + dy.toFixed(1) + ') scale(' + e.toFixed(3) + ')');
    elKk.textContent = s.kk;
    elBody.textContent = s.body;
    elStep.textContent = (i + 1) + '/' + n;
    elEta.textContent = (i === n - 1) ? 'sei arrivato' : 'prossima: ' + stops[i + 1].kind.toLowerCase();
    elHint.textContent = (i === n - 1) ? 'Tocca per ricominciare' : 'Tocca la mappa per proseguire';
    cam.querySelectorAll('.mk').forEach(mk => mk.classList.remove('on'));
    const cur = document.getElementById(s.m);
    if (cur) cur.classList.add('on');
    Array.from(list.children).forEach((row, k) => row.className = 'dir-row' + (k === i ? ' on' : ''));
  }

  /* Vai a una tappa (ciclica). */
  function go(ni) {
    i = ((ni % n) + n) % n;
    render();
    if (typeof sndOpen === 'function') { try { sndOpen(); } catch (e) {} }
  }

  map.addEventListener('click', () => go(i + 1));
  list.addEventListener('click', e => {
    const t = e.target.closest('.dir-row');
    if (t) go(parseInt(t.getAttribute('data-i'), 10));
  });

  /* Zoom e bussola (la bussola riporta lo zoom a 1). */
  const zin = document.getElementById('mv-zin');
  const zout = document.getElementById('mv-zout');
  const comp = document.getElementById('mv-comp');
  if (zin)  zin.addEventListener('click',  ev => { ev.stopPropagation(); zoom = Math.min(1.5, zoom + 0.18); render(); });
  if (zout) zout.addEventListener('click', ev => { ev.stopPropagation(); zoom = Math.max(0.8, zoom - 0.18); render(); });
  if (comp) comp.addEventListener('click', ev => { ev.stopPropagation(); zoom = 1; render(); });

  /* Pulsante di chiusura del pannello = chiude la finestra Mappe.
     (Ora closeWin accetta anche un id: prima questa chiamata falliva.) */
  const x = document.querySelector('#w-fine .dir-x');
  if (x) x.addEventListener('click', ev => { ev.stopPropagation(); if (typeof closeWin === 'function') closeWin('w-fine'); });

  render();
})();
