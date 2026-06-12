/* ============================================================
   hub.js — finestre, dock con lente d'ingrandimento, orologio
   ============================================================ */

/* ---------- orologio della menu bar ---------- */
const clock = document.getElementById('clock');
function tick() {
  const d = new Date();
  const giorno = d.toLocaleDateString('it-IT', { weekday: 'short', day: 'numeric', month: 'short' });
  const ora = d.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
  clock.textContent = giorno + '  ' + ora;
}
tick();
setInterval(tick, 10000);

/* ---------- finestre ---------- */
let zTop = 20;
const wins = [...document.querySelectorAll('.win')];

function syncDock() {
  document.querySelectorAll('.dapp[data-w]').forEach(d => {
    const w = document.getElementById(d.dataset.w);
    d.classList.toggle('running', !!w && w.classList.contains('open'));
  });
}

function focusWin(w) { w.style.zIndex = ++zTop; }

function openWin(id) {
  const w = document.getElementById(id);
  if (!w) return;
  if (!w.classList.contains('open')) {
    w.classList.remove('closing');
    w.classList.add('open');
    animaBarre(w);
  }
  focusWin(w);
  syncDock();
}

function closeWin(w) {
  w.classList.add('closing');
  setTimeout(() => { w.classList.remove('open', 'closing', 'maxi'); syncDock(); }, 280);
}

/* barre di competenza: si riempiono quando la finestra si apre */
function animaBarre(w) {
  w.querySelectorAll('.fill[data-v]').forEach(f => {
    f.style.width = '0';
    requestAnimationFrame(() => requestAnimationFrame(() => { f.style.width = f.dataset.v + '%'; }));
  });
}

wins.forEach(w => {
  w.addEventListener('pointerdown', () => focusWin(w));

  const bar = w.querySelector('.titlebar');

  /* semafori macOS, generati una volta sola */
  const lights = document.createElement('div');
  lights.className = 'lights';
  lights.innerHTML =
    '<button class="c-close" aria-label="Chiudi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>' +
    '<button class="c-min" aria-label="Riduci"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M5 12h14"/></svg></button>' +
    '<button class="c-max" aria-label="Ingrandisci"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button>';
  bar.prepend(lights);

  const [bClose, bMin, bMax] = lights.querySelectorAll('button');
  bClose.addEventListener('click', (e) => { e.stopPropagation(); closeWin(w); });
  bMin.addEventListener('click',   (e) => { e.stopPropagation(); closeWin(w); });
  bMax.addEventListener('click',   (e) => { e.stopPropagation(); w.classList.toggle('maxi'); focusWin(w); });

  /* trascinamento dalla barra del titolo */
  let sx = 0, sy = 0, ox = 0, oy = 0, dragging = false;
  bar.addEventListener('pointerdown', (e) => {
    if (e.target.closest('.lights') || w.classList.contains('maxi')) return;
    dragging = true;
    sx = e.clientX; sy = e.clientY;
    const r = w.getBoundingClientRect();
    ox = r.left; oy = r.top;
    bar.setPointerCapture(e.pointerId);
    w.style.transition = 'none';
  });
  bar.addEventListener('pointermove', (e) => {
    if (!dragging) return;
    const nx = Math.min(Math.max(ox + e.clientX - sx, -w.offsetWidth + 90), window.innerWidth - 90);
    const ny = Math.min(Math.max(oy + e.clientY - sy, 34), window.innerHeight - 60);
    w.style.left = nx + 'px';
    w.style.top = ny + 'px';
  });
  bar.addEventListener('pointerup', () => { dragging = false; w.style.transition = ''; });
});

/* ---------- dock: lente d'ingrandimento come macOS ---------- */
const dock = document.getElementById('dock');
const dockIcons = [...dock.querySelectorAll('.ai')];
dock.addEventListener('mousemove', (e) => {
  dockIcons.forEach(ic => {
    const r = ic.getBoundingClientRect();
    const d = Math.abs(e.clientX - (r.left + r.width / 2));
    const s = Math.max(1, 1.5 - d / 150);
    ic.style.setProperty('--s', s.toFixed(3));
  });
});
dock.addEventListener('mouseleave', () => {
  dockIcons.forEach(ic => ic.style.setProperty('--s', 1));
});

/* rimbalzo dell'icona quando apre la sua app */
function bounce(ic) {
  if (!ic) return;
  ic.classList.remove('bounce');
  void ic.offsetWidth;
  ic.classList.add('bounce');
  ic.addEventListener('animationend', () => ic.classList.remove('bounce'), { once: true });
}

/* card, voci di menu, icone desktop e dock aprono le finestre */
document.addEventListener('click', (e) => {
  const t = e.target.closest('[data-open]');
  if (t) { openWin(t.dataset.open); return; }
  const d = e.target.closest('.dapp[data-w]');
  if (d) {
    const w = document.getElementById(d.dataset.w);
    if (!w.classList.contains('open')) bounce(d.querySelector('.ai'));
    if (w.classList.contains('open')) focusWin(w); else openWin(d.dataset.w);
  }
});

syncDock();
animaBarre(document.getElementById('w-comp') || document.body);
