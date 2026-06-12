const clockEl = document.getElementById('clock');
function tick() {
  const d = new Date();
  const giorno = d.toLocaleDateString('it-IT', { weekday: 'short', day: 'numeric', month: 'short' });
  const ora = d.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
  clockEl.textContent = giorno.charAt(0).toUpperCase() + giorno.slice(1) + '  ' + ora;
}
tick();
setInterval(tick, 10000);

const boot = document.getElementById('boot');
if (boot) {
  const fill = boot.querySelector('.bbar span');
  const logo = boot.querySelector('img');
  const t0 = performance.now();
  const DUR = 2300;
  const MAXWAIT = 6000;
  let ready = !logo;
  let shown = 0;
  const fallback = () => {
    const s = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    s.setAttribute('viewBox', '0 0 56 56');
    s.setAttribute('class', 'bfall');
    s.innerHTML = '<use href="#i-cap"/>';
    logo.replaceWith(s);
  };
  if (logo) {
    if (logo.complete) {
      if (logo.naturalWidth === 0) fallback();
      ready = true;
    } else {
      logo.addEventListener('load', () => { ready = true; });
      logo.addEventListener('error', () => { fallback(); ready = true; });
    }
  }
  const frame = now => {
    const el = now - t0;
    const t = Math.min(1, el / DUR);
    const eased = t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
    let target = eased * 100;
    if (!ready && el < MAXWAIT) target = Math.min(target, 88);
    shown += (target - shown) * 0.14;
    if (target >= 100 && shown > 99.4) shown = 100;
    fill.style.width = shown.toFixed(2) + '%';
    if (shown < 100) { requestAnimationFrame(frame); return; }
    boot.classList.add('done');
    document.body.classList.remove('booting');
    setTimeout(() => boot.remove(), 750);
  };
  requestAnimationFrame(frame);
}

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

let zTop = 20;
function focusWin(win) { zTop += 1; win.style.zIndex = zTop; }
function syncDock() {
  document.querySelectorAll('.dapp[data-w]').forEach(d => {
    const w = document.getElementById(d.dataset.w);
    d.classList.toggle('running', !!w && w.classList.contains('open'));
  });
}
function fitWin(win) {
  requestAnimationFrame(() => {
    const r = win.getBoundingClientRect();
    const limit = window.innerHeight - 82;
    if (r.bottom > limit) win.style.top = Math.max(40, Math.round(limit - r.height)) + 'px';
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
  fitWin(win);
  syncDock();
}
function closeWin(win) {
  win.classList.add('closing');
  setTimeout(() => { win.classList.remove('open', 'closing', 'maxi'); syncDock(); }, 260);
}
function closeAll() {
  document.querySelectorAll('.win.open').forEach((w, i) => setTimeout(() => closeWin(w), i * 60));
}

document.querySelectorAll('.win').forEach(win => {
  win.addEventListener('pointerdown', () => focusWin(win));
  win.querySelector('.c-close').addEventListener('click', e => { e.stopPropagation(); closeWin(win); });
  win.querySelector('.c-min').addEventListener('click', e => { e.stopPropagation(); closeWin(win); });
  win.querySelector('.c-max').addEventListener('click', e => { e.stopPropagation(); win.classList.toggle('maxi'); focusWin(win); });
});

document.addEventListener('click', e => {
  const t = e.target.closest('[data-open]');
  if (t) openWin(t.dataset.open);
});

const ORDER = ['w-pres', 'w-io', 'w-fsl', 'w-skills', 'w-prog', 'w-coll', 'w-fine'];
function topChapter() {
  let best = null, z = -1;
  ORDER.forEach(id => {
    const w = document.getElementById(id);
    if (w && w.classList.contains('open') && (+w.style.zIndex || 0) >= z) { z = +w.style.zIndex || 0; best = id; }
  });
  return best || 'w-coll';
}
document.querySelectorAll('[data-nav]').forEach(b => {
  b.addEventListener('click', () => {
    const i = ORDER.indexOf(topChapter());
    const n = b.dataset.nav === 'next' ? (i + 1) % ORDER.length : (i - 1 + ORDER.length) % ORDER.length;
    openWin(ORDER[n]);
  });
});

const fgrid = document.querySelector('.fgrid');
document.querySelectorAll('.fseg button').forEach(b => {
  b.addEventListener('click', () => {
    document.querySelectorAll('.fseg button').forEach(x => x.classList.toggle('on', x === b));
    fgrid.classList.toggle('list', b.dataset.view === 'list');
  });
});

let hlTimer = null;
document.querySelectorAll('.sidebar [data-tag]').forEach(b => {
  b.addEventListener('click', () => {
    document.querySelectorAll('.fitem').forEach(c => {
      c.classList.toggle('hl', (c.dataset.tag || '') === b.dataset.tag);
    });
    clearTimeout(hlTimer);
    hlTimer = setTimeout(() => {
      document.querySelectorAll('.fitem.hl').forEach(c => c.classList.remove('hl'));
    }, 1900);
  });
});

const dock = document.getElementById('dock');
const dockIcons = Array.from(dock.querySelectorAll('.dapp .ai'));
const DOCK_GROW = 0.8;
const DOCK_RANGE = 160;
const LERP = 0.25;
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

dock.querySelectorAll('.dapp').forEach(d => {
  const btn = d.querySelector('.ai');
  btn.addEventListener('click', () => {
    btn.classList.remove('bounce');
    void btn.offsetWidth;
    btn.classList.add('bounce');
    if (d.dataset.w) openWin(d.dataset.w);
    if (d.dataset.act === 'trash') closeAll();
  });
});

document.addEventListener('pointermove', e => {
  const card = e.target.closest('.lgcard');
  if (!card) return;
  const r = card.getBoundingClientRect();
  card.style.setProperty('--mx', ((e.clientX - r.left) / r.width * 100).toFixed(1) + '%');
  card.style.setProperty('--my', ((e.clientY - r.top) / r.height * 100).toFixed(1) + '%');
});

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

document.querySelectorAll('.win').forEach(win => {
  const tb = win.querySelector('.titlebar');
  let drag = false, sx = 0, sy = 0, ox = 0, oy = 0;
  tb.addEventListener('pointerdown', e => {
    if (e.target.closest('.lights') || win.classList.contains('maxi')) return;
    drag = true;
    const r = win.getBoundingClientRect();
    sx = e.clientX; sy = e.clientY; ox = r.left; oy = r.top;
    tb.setPointerCapture(e.pointerId);
  });
  tb.addEventListener('pointermove', e => {
    if (!drag) return;
    win.style.left = Math.round(ox + e.clientX - sx) + 'px';
    win.style.top = Math.max(36, Math.round(oy + e.clientY - sy)) + 'px';
  });
  const end = () => { drag = false; };
  tb.addEventListener('pointerup', end);
  tb.addEventListener('pointercancel', end);
});

syncDock();
const first = document.querySelector('.win.open');
if (first) { focusWin(first); fitWin(first); }
