/* ============================================================
   hub.js — il comportamento del desktop
   finestre, dock con lente, semafori, drag, sheen Liquid Glass
   e il fumetto delle card del Curriculum
   ============================================================ */

/* ---------- orologio della menu bar ---------- */
const clockEl = document.getElementById('clock');
function tick() {
  const d = new Date();
  const giorno = d.toLocaleDateString('it-IT', { weekday: 'short', day: 'numeric', month: 'short' });
  const ora = d.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
  clockEl.textContent = giorno.charAt(0).toUpperCase() + giorno.slice(1) + '  ' + ora;
}
tick();
setInterval(tick, 10000);

/* ---------- semafori: generati una volta sola ---------- */
const SVG_X    = '<svg viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M2.5 2.5l5 5M7.5 2.5l-5 5"/></svg>';
const SVG_MIN  = '<svg viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M2 5h6"/></svg>';
const SVG_MAX  = '<svg viewBox="0 0 10 10" fill="currentColor"><path d="M2.6 6.6L6.6 2.6H2.6zM7.4 3.4L3.4 7.4h4z"/></svg>';
document.querySelectorAll('.win .titlebar').forEach(tb => {
  const lights = document.createElement('span');
  lights.className = 'lights';
  lights.innerHTML =
    '<button class="c-close" aria-label="Chiudi">' + SVG_X + '</button>' +
    '<button class="c-min" aria-label="Riduci">' + SVG_MIN + '</button>' +
    '<button class="c-max" aria-label="Ingrandisci">' + SVG_MAX + '</button>';
  tb.prepend(lights);
});

/* ---------- finestre: apri, chiudi, porta in primo piano ---------- */
let zTop = 20;
function focusWin(win) { zTop += 1; win.style.zIndex = zTop; }
function syncDock() {
  document.querySelectorAll('.dapp[data-w]').forEach(d => {
    const w = document.getElementById(d.dataset.w);
    d.classList.toggle('running', !!w && w.classList.contains('open'));
  });
}
function openWin(id) {
  const win = document.getElementById(id);
  if (!win) return;
  if (!win.classList.contains('open')) {
    win.classList.remove('closing');
    win.classList.add('open');
  }
  focusWin(win);
  syncDock();
}
function closeWin(win) {
  win.classList.add('closing');
  setTimeout(() => { win.classList.remove('open', 'closing', 'maxi'); syncDock(); }, 260);
}

document.querySelectorAll('.win').forEach(win => {
  win.addEventListener('pointerdown', () => focusWin(win));
  win.querySelector('.c-close').addEventListener('click', e => { e.stopPropagation(); closeWin(win); });
  win.querySelector('.c-min').addEventListener('click', e => { e.stopPropagation(); closeWin(win); });
  win.querySelector('.c-max').addEventListener('click', e => { e.stopPropagation(); win.classList.toggle('maxi'); focusWin(win); });
});

/* click delegato: tutto ciò che ha data-open apre una finestra */
document.addEventListener('click', e => {
  const t = e.target.closest('[data-open]');
  if (t) openWin(t.dataset.open);
});

/* ---------- drag delle finestre dalla titlebar ---------- */
document.querySelectorAll('.win').forEach(win => {
  const tb = win.querySelector('.titlebar');
  let sx = 0, sy = 0, ox = 0, oy = 0, dragging = false;
  tb.addEventListener('pointerdown', e => {
    if (e.target.closest('.lights') || win.classList.contains('maxi')) return;
    dragging = true;
    sx = e.clientX; sy = e.clientY;
    const r = win.getBoundingClientRect();
    ox = r.left; oy = r.top;
    tb.setPointerCapture(e.pointerId);
  });
  tb.addEventListener('pointermove', e => {
    if (!dragging) return;
    const x = Math.min(Math.max(ox + e.clientX - sx, -win.offsetWidth + 90), innerWidth - 90);
    const y = Math.min(Math.max(oy + e.clientY - sy, 34), innerHeight - 60);
    win.style.left = x + 'px';
    win.style.top = y + 'px';
  });
  const stop = () => { dragging = false; };
  tb.addEventListener('pointerup', stop);
  tb.addEventListener('pointercancel', stop);
});

/* ---------- dock: lente con campana cosinusoidale (rAF + lerp) ---------- */
const dock = document.getElementById('dock');
const dockIcons = Array.from(dock.querySelectorAll('.dapp .ai'));
const DOCK_GROW = 0.8;     // crescita massima (+80%)
const DOCK_RANGE = 160;    // raggio d'influenza in px
const LERP = 0.25;         // morbidezza dell'inseguimento
let targetS = dockIcons.map(() => 1);
let currentS = dockIcons.map(() => 1);
let rafOn = false;

function dockFrame() {
  let still = true;
  dockIcons.forEach((ic, i) => {
    currentS[i] += (targetS[i] - currentS[i]) * LERP;
    if (Math.abs(targetS[i] - currentS[i]) > 0.002) still = false;
    ic.style.setProperty('--s', currentS[i].toFixed(3));
  });
  if (still && targetS.every(s => s === 1) && currentS.every(s => Math.abs(s - 1) < 0.003)) {
    rafOn = false;
    return;
  }
  requestAnimationFrame(dockFrame);
}
function wakeDock() { if (!rafOn) { rafOn = true; requestAnimationFrame(dockFrame); } }

dock.addEventListener('pointermove', e => {
  dockIcons.forEach((ic, i) => {
    const r = ic.getBoundingClientRect();
    const d = Math.abs(e.clientX - (r.left + r.width / 2));
    targetS[i] = d < DOCK_RANGE ? 1 + DOCK_GROW * (0.5 + 0.5 * Math.cos(Math.PI * d / DOCK_RANGE)) : 1;
  });
  wakeDock();
});
dock.addEventListener('pointerleave', () => {
  targetS = targetS.map(() => 1);
  wakeDock();
});

/* click sul dock: rimbalzo + apertura */
dock.querySelectorAll('.dapp').forEach(d => {
  const btn = d.querySelector('.ai');
  btn.addEventListener('click', () => {
    btn.classList.remove('bounce');
    void btn.offsetWidth;
    btn.classList.add('bounce');
    if (d.dataset.w) openWin(d.dataset.w);
  });
});

/* ---------- Liquid Glass: lo sheen segue il puntatore ---------- */
document.addEventListener('pointermove', e => {
  const card = e.target.closest('.lgcard');
  if (!card) return;
  const r = card.getBoundingClientRect();
  card.style.setProperty('--mx', ((e.clientX - r.left) / r.width * 100).toFixed(1) + '%');
  card.style.setProperty('--my', ((e.clientY - r.top) / r.height * 100).toFixed(1) + '%');
});

/* ---------- le card del Curriculum parlano in hover ---------- */
document.querySelectorAll('.favcard[data-say]').forEach(card => {
  const frasi = card.dataset.say.split('|').filter(Boolean);
  const bubble = card.querySelector('.favsay');
  if (!bubble || frasi.length === 0) return;
  let i = Math.floor(Math.random() * frasi.length);
  bubble.textContent = frasi[i];
  card.addEventListener('mouseenter', () => {
    bubble.textContent = frasi[i % frasi.length];
    i += 1;
  });
});

/* ---------- stato iniziale ---------- */
syncDock();
const first = document.querySelector('.win.open');
if (first) focusWin(first);
