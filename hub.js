const clockEl = document.getElementById('clock');

/* Timer presentazione: 10 minuti che partono dal Centro di Controllo.
   Finche' e' attivo, l'orologio della barra mostra il countdown al posto della data. */
const PRES_DURATA_MS = 10 * 60 * 1000;
let presScadenza = null; /* timestamp (ms) di fine; null = mostra data e ora */

function tick() {
  if (presScadenza !== null) {
    let restoSec = Math.round((presScadenza - Date.now()) / 1000);
    if (restoSec < 0) restoSec = 0;
    const min = Math.floor(restoSec / 60);
    const sec = restoSec % 60;
    clockEl.textContent = min + ':' + String(sec).padStart(2, '0');
    clockEl.classList.toggle('timer-finito', restoSec === 0);
    clockEl.classList.toggle('timer-quasi', restoSec > 0 && restoSec <= 60);
    return;
  }
  const d = new Date();
  const giorno = d.toLocaleDateString('it-IT', { weekday: 'short', day: 'numeric', month: 'short' });
  const ora = d.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
  clockEl.textContent = giorno.charAt(0).toUpperCase() + giorno.slice(1) + '  ' + ora;
}

/* Avvia (o riavvia) il countdown di 10 minuti. */
function avviaTimerPresentazione() {
  presScadenza = Date.now() + PRES_DURATA_MS;
  clockEl.classList.add('timer-on');
  clockEl.classList.remove('timer-finito', 'timer-quasi');
  tick();
}

tick();
setInterval(tick, 1000);

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
    sndStart();
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
    sndOpen();
  }
  focusWin(win);
  fitWin(win);
  syncDock();
}
function closeWin(win) {
  sndClose();
  win.classList.add('closing');
  setTimeout(() => {
    win.classList.remove('open', 'closing', 'maxi');
    win.style.transition = '';
    win.style.removeProperty('width');
    win.style.removeProperty('height');
    win.style.removeProperty('max-height');
    win.style.removeProperty('border-radius');
    win._mxBusy = false;
    syncDock();
  }, 260);
}
function closeAll() {
  document.querySelectorAll('.win.open').forEach((w, i) => setTimeout(() => closeWin(w), i * 60));
}

const MAXI_EASE = 'cubic-bezier(.32,.72,0,1)';
function maxiBounds() {
  return { left: 0, top: 34, width: window.innerWidth, height: window.innerHeight - 34, radius: 0 };
}
function setWinBox(win, r, anim) {
  win.style.setProperty('max-height', 'none', 'important');
  win.style.transition = anim ? ('left .38s ' + MAXI_EASE + ', top .38s ' + MAXI_EASE + ', width .38s ' + MAXI_EASE + ', height .38s ' + MAXI_EASE + ', border-radius .38s ' + MAXI_EASE) : 'none';
  win.style.setProperty('left', r.left + 'px', 'important');
  win.style.setProperty('top', r.top + 'px', 'important');
  win.style.setProperty('width', r.width + 'px', 'important');
  win.style.setProperty('height', r.height + 'px', 'important');
  if (r.radius !== undefined) win.style.setProperty('border-radius', r.radius + 'px', 'important');
}
function toggleMax(win) {
  focusWin(win);
  if (win._mxBusy) return;
  const goMax = !win.classList.contains('maxi');
  const start = win.getBoundingClientRect();
  setWinBox(win, { left: start.left, top: start.top, width: start.width, height: start.height }, false);
  void win.offsetWidth;
  win._mxBusy = true;
  let target;
  if (goMax) {
    win._restore = { left: start.left, top: start.top, width: start.width, height: start.height };
    win.classList.add('maxi');
    sndOpen();
    target = maxiBounds();
  } else {
    target = win._restore ? { left: win._restore.left, top: win._restore.top, width: win._restore.width, height: win._restore.height, radius: 24 } : { left: start.left, top: start.top, width: start.width, height: start.height, radius: 24 };
    sndClose();
    dockWake();
  }
  requestAnimationFrame(() => setWinBox(win, target, true));
  setTimeout(() => {
    win.style.transition = '';
    if (!goMax) {
      win.classList.remove('maxi');
      win.style.removeProperty('width');
      win.style.removeProperty('height');
      win.style.removeProperty('max-height');
      win.style.removeProperty('border-radius');
    }
    win._mxBusy = false;
  }, 430);
}
window.addEventListener('resize', () => {
  document.querySelectorAll('.win.maxi').forEach(w => setWinBox(w, maxiBounds(), false));
});

document.querySelectorAll('.win').forEach(win => {
  win.addEventListener('pointerdown', () => focusWin(win));
  win.querySelector('.c-close').addEventListener('click', e => { e.stopPropagation(); closeWin(win); });
  win.querySelector('.c-min').addEventListener('click', e => { e.stopPropagation(); closeWin(win); });
  win.querySelector('.c-max').addEventListener('click', e => { e.stopPropagation(); toggleMax(win); });
});

document.addEventListener('click', e => {
  const t = e.target.closest('[data-open]');
  if (t) openWin(t.dataset.open);
});

const ORDER = ['w-pres', 'w-io', 'w-fsl', 'w-fine'];
function topChapter() {
  let best = null, z = -1;
  ORDER.forEach(id => {
    const w = document.getElementById(id);
    if (w && w.classList.contains('open') && (+w.style.zIndex || 0) >= z) { z = +w.style.zIndex || 0; best = id; }
  });
  return best || 'w-pres';
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

// Auto-hide del dock in stile macOS: resta nascosto e riappare avvicinandosi al bordo inferiore.
const dockCss = document.createElement('style');
dockCss.textContent =
  '.dock{transition:bottom .42s cubic-bezier(.32,.72,0,1),opacity .42s ease}' +
  '.dock.autohide{bottom:-82px;opacity:0;pointer-events:none}';
document.head.appendChild(dockCss);

let dockHot = false;
let dockGrace = 0;
function dockShow() { dock.classList.remove('autohide'); }
function dockHide() { if (typeof hasFullscreenApp === 'function' && !hasFullscreenApp()) { dockShow(); return; } if (!dockHot && performance.now() >= dockGrace) dock.classList.add('autohide'); }
function dockWake(ms) { dockShow(); dockGrace = performance.now() + (ms || 1500); }
document.addEventListener('pointermove', e => {
  if (e.clientY >= window.innerHeight - 80) dockShow();
  else if (typeof hasFullscreenApp === 'function' && hasFullscreenApp() && e.clientY < window.innerHeight - 110) dockHide();
  else dockShow();
});
dock.addEventListener('pointerenter', () => { dockHot = true; dockShow(); });
dock.addEventListener('pointerleave', () => { dockHot = false; dockHide(); });
setTimeout(() => { if (typeof hasFullscreenApp === 'function' && hasFullscreenApp()) dock.classList.add('autohide'); else dockShow(); }, 2800);

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
  let drag = false, sx = 0, sy = 0, ox = 0, oy = 0, ow = 0, oh = 0;
  tb.addEventListener('pointerdown', e => {
    if (e.target.closest('.lights') || win.classList.contains('maxi')) return;
    drag = true;
    const r = win.getBoundingClientRect();
    sx = e.clientX; sy = e.clientY; ox = r.left; oy = r.top; ow = r.width; oh = r.height;
    win.style.width = Math.round(ow) + 'px';
    tb.setPointerCapture(e.pointerId);
  });
  tb.addEventListener('pointermove', e => {
    if (!drag) return;
    const m = 8;
    let nx = ox + e.clientX - sx;
    let ny = oy + e.clientY - sy;
    nx = Math.min(Math.max(nx, m), Math.max(m, window.innerWidth - ow - m));
    ny = Math.min(Math.max(ny, 40), Math.max(40, window.innerHeight - oh - m));
    win.style.left = Math.round(nx) + 'px';
    win.style.top = Math.round(ny) + 'px';
  });
  const end = () => { if (drag && !win.classList.contains('maxi')) captureNormal(win); drag = false; };
  tb.addEventListener('pointerup', end);
  tb.addEventListener('pointercancel', end);
  tb.addEventListener('dblclick', e => { if (e.target.closest('.lights')) return; toggleMax(win); });
});

syncDock();
const first = document.querySelector('.win.open');
if (first) { focusWin(first); fitWin(first); }

const ccBtn = document.getElementById('ccbtn');
const ccPanel = document.getElementById('ccpanel');
function ccClose() { ccPanel.classList.remove('show'); ccBtn.classList.remove('on'); }
ccBtn.addEventListener('click', e => {
  e.stopPropagation();
  const open = ccPanel.classList.toggle('show');
  ccBtn.classList.toggle('on', open);
});
document.addEventListener('click', e => {
  if (ccPanel.classList.contains('show') && !ccPanel.contains(e.target) && !ccBtn.contains(e.target)) ccClose();
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') ccClose(); });

const wifiIc = document.getElementById('cc-wifi-ic');
const wifiSt = document.getElementById('cc-wifi-st');
const mbWifi = document.getElementById('mb-wifi');
function wifiSync() {
  const on = navigator.onLine;
  wifiIc.classList.toggle('on', on);
  wifiSt.textContent = on ? 'Connesso' : 'Offline';
  mbWifi.style.opacity = on ? '' : '.35';
}
wifiSync();
window.addEventListener('online', wifiSync);
window.addEventListener('offline', wifiSync);

const PAGE = { title: 'Maturità 2026 — Filippo Corsini', url: location.origin + location.pathname };
const copySt = document.getElementById('cc-copy-st');
function copyLink() {
  navigator.clipboard.writeText(PAGE.url).then(() => {
    copySt.textContent = 'Copiato negli appunti';
    setTimeout(() => { copySt.textContent = 'Per la commissione'; }, 1800);
  }).catch(() => {});
}
document.getElementById('cc-share').addEventListener('click', () => {
  if (navigator.share) navigator.share(PAGE).catch(() => {});
  else copyLink();
});
document.getElementById('cc-copy').addEventListener('click', copyLink);

const fullBtn = document.getElementById('cc-full');
fullBtn.addEventListener('click', () => {
  if (document.fullscreenElement) document.exitFullscreen();
  else document.documentElement.requestFullscreen().catch(() => {});
});
document.addEventListener('fullscreenchange', () => {
  fullBtn.classList.toggle('on', !!document.fullscreenElement);
});

document.getElementById('cc-pres').addEventListener('click', () => {
  ccClose();
  closeAll();
  avviaTimerPresentazione();
  if (!document.fullscreenElement) document.documentElement.requestFullscreen().catch(() => {});
  setTimeout(() => openWin('w-pres'), 520);
});
document.getElementById('cc-boot').addEventListener('click', () => { location.href = 'hub.php?boot=1'; });

const dim = document.getElementById('dim');
const briInput = document.getElementById('cc-bri');
const volInput = document.getElementById('cc-vol');
function rangeFill(el) {
  const pct = (el.value - el.min) / (el.max - el.min);
  el.style.setProperty('--val', 'calc((100% - 20px) * ' + pct.toFixed(4) + ' + 10px)');
}
function briApply() {
  rangeFill(briInput);
  dim.style.opacity = ((100 - briInput.value) / 100 * 0.82).toFixed(3);
}
briInput.step = 'any';
volInput.step = 'any';
briInput.value = localStorage.getItem('cc-bri') || 100;
briApply();
briInput.addEventListener('input', () => { briApply(); localStorage.setItem('cc-bri', briInput.value); });

volInput.value = localStorage.getItem('cc-vol') ?? 25;
rangeFill(volInput);
volInput.addEventListener('input', () => { rangeFill(volInput); localStorage.setItem('cc-vol', volInput.value); sndTick(); });
volInput.addEventListener('change', () => { tickAt = 0; sndTick(); });

let actx = null, sndBus = null, tickAt = 0;
function audio() {
  if (!actx) {
    actx = new (window.AudioContext || window.webkitAudioContext)();
    const lp = actx.createBiquadFilter();
    lp.type = 'lowpass';
    lp.frequency.value = 2400;
    lp.Q.value = 0.5;
    sndBus = actx.createGain();
    sndBus.connect(lp);
    lp.connect(actx.destination);
  }
  if (actx.state === 'suspended') actx.resume();
  return actx;
}
function note(freq, at, dur, peak) {
  const o = actx.createOscillator();
  const g = actx.createGain();
  o.type = 'sine';
  o.frequency.value = freq;
  g.gain.setValueAtTime(0.0001, at);
  g.gain.exponentialRampToValueAtTime(Math.max(peak, 0.0002), at + 0.008);
  g.gain.exponentialRampToValueAtTime(0.0001, at + dur);
  o.connect(g).connect(sndBus);
  o.start(at);
  o.stop(at + dur + 0.04);
}
function sndOpen() {
  const v = volInput.value / 100;
  if (!v) return;
  try {
    const t = audio().currentTime + 0.01;
    note(523.25, t, 0.26, v * 0.11);
    note(783.99, t + 0.014, 0.22, v * 0.05);
    note(1046.5, t + 0.014, 0.17, v * 0.022);
  } catch (e) {}
}
function sndClose() {
  const v = volInput.value / 100;
  if (!v) return;
  try {
    const t = audio().currentTime + 0.01;
    note(415.3, t, 0.2, v * 0.1);
    note(523.25, t + 0.012, 0.15, v * 0.04);
  } catch (e) {}
}
function sndTick() {
  const v = volInput.value / 100;
  if (!v) return;
  const now = performance.now();
  if (now - tickAt < 85) return;
  tickAt = now;
  try {
    const t = audio().currentTime + 0.005;
    note(987.77, t, 0.08, v * 0.13);
    note(1975.53, t, 0.05, v * 0.03);
  } catch (e) {}
}
function sndClick() {
  const v = volInput.value / 100;
  if (!v) return;
  try {
    const t = audio().currentTime + 0.005;
    note(1318.51, t, 0.045, v * 0.05);
    note(2637.02, t, 0.03, v * 0.012);
  } catch (e) {}
}
function sndStart() {
  const v = volInput.value / 100;
  if (!v) return;
  try {
    const a = audio();
    if (a.state === 'suspended') {
      const once = () => { document.removeEventListener('pointerdown', once); sndStart(); };
      document.addEventListener('pointerdown', once);
      return;
    }
    const t = a.currentTime + 0.02;
    note(349.23, t, 0.7, v * 0.07);
    note(440, t + 0.05, 0.65, v * 0.06);
    note(523.25, t + 0.1, 0.6, v * 0.055);
    note(698.46, t + 0.16, 0.55, v * 0.04);
  } catch (e) {}
}
function sndExit() {
  const v = volInput.value / 100;
  if (!v) return;
  try {
    const t = audio().currentTime + 0.01;
    note(523.25, t, 0.22, v * 0.09);
    note(392, t + 0.09, 0.24, v * 0.07);
    note(261.63, t + 0.18, 0.34, v * 0.06);
  } catch (e) {}
}

const bpct = document.getElementById('bpct');
if (navigator.getBattery) {
  navigator.getBattery().then(b => {
    const upd = () => { bpct.textContent = Math.round(b.level * 100) + '%'; };
    upd();
    b.addEventListener('levelchange', upd);
    b.addEventListener('chargingchange', upd);
  });
} else {
  bpct.remove();
}

document.addEventListener('click', e => {
  const el = e.target.closest('button, a');
  if (!el || el.closest('.lights')) return;
  sndClick();
}, true);

/* Gestione alimentazione in stile macOS: conferma di spegnimento,
   dissolvenza dello schermo e schermata di commiato scritta a mano. */
const pwrCss = document.createElement('style');
pwrCss.textContent =
  '#pwrdlg{position:fixed;inset:0;z-index:7000;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.2);opacity:0;pointer-events:none;transition:opacity .22s ease}' +
  '#pwrdlg.show{opacity:1;pointer-events:auto}' +
  '#pwrdlg .box{width:264px;background:rgba(244,244,247,.86);backdrop-filter:blur(30px) saturate(1.6);-webkit-backdrop-filter:blur(30px) saturate(1.6);border-radius:14px;border:1px solid rgba(255,255,255,.55);box-shadow:0 24px 64px rgba(0,0,0,.4);padding:20px 18px 16px;text-align:center;transform:scale(.9);transition:transform .26s cubic-bezier(.2,1.45,.45,1)}' +
  '#pwrdlg.show .box{transform:scale(1)}' +
  '#pwrdlg .pico{width:42px;height:42px;margin:0 auto 10px;color:#48484e}' +
  '#pwrdlg .pico svg{width:100%;height:100%}' +
  '#pwrdlg h3{font-size:13px;font-weight:700;color:#1d1d1f;margin:0 0 5px;letter-spacing:-.01em}' +
  '#pwrdlg p{font-size:11px;font-weight:500;color:rgba(60,60,67,.72);line-height:1.45;margin:0 0 14px}' +
  '#pwrdlg .row{display:flex;gap:8px}' +
  '#pwrdlg .row button{flex:1;height:30px;border:none;border-radius:8px;font:inherit;font-size:12px;font-weight:600;cursor:pointer;transition:transform .15s ease,filter .15s ease}' +
  '#pwrdlg .row button:hover{filter:brightness(1.04)}' +
  '#pwrdlg .row button:active{transform:scale(.96)}' +
  '#pwr-no{background:rgba(255,255,255,.92);color:#1d1d1f;box-shadow:0 1px 2px rgba(0,0,0,.12)}' +
  '#pwr-si{background:#0a84ff;color:#fff;box-shadow:0 1px 2px rgba(0,0,0,.18)}' +
  '#shut{position:fixed;inset:0;z-index:8000;background:#000;display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity 1.2s cubic-bezier(.4,0,.2,1)}' +
  '#shut.on{opacity:1;pointer-events:auto}' +
  '#shut .gwrap{display:flex;flex-direction:column;align-items:center;gap:28px;transition:opacity 1.1s cubic-bezier(.4,0,.2,1),filter 1.1s cubic-bezier(.4,0,.2,1)}' +
  '#shut .gtx{font-size:clamp(56px,11vw,116px);font-weight:600;letter-spacing:-.015em;line-height:1;background:linear-gradient(100deg,#fff 0%,#fff 38%,#a8c4ff 46%,#e0bbff 50%,#ffb8d6 54%,#fff 62%,#fff 100%);background-size:240% 100%;background-position:120% 0;-webkit-background-clip:text;background-clip:text;color:transparent;opacity:0;transform:scale(.96);filter:blur(14px);transition:opacity 1.8s cubic-bezier(.25,.1,.25,1),transform 2.8s cubic-bezier(.25,.1,.25,1),filter 1.8s cubic-bezier(.25,.1,.25,1)}' +
  '#shut.draw .gtx{opacity:1;transform:scale(1);filter:blur(0);animation:gsheen 5.5s cubic-bezier(.4,0,.2,1) 1.8s infinite}' +
  '#shut .gsub{font-size:15px;font-weight:500;letter-spacing:.01em;color:#86868b;opacity:0;transform:translateY(10px);transition:opacity 1.3s cubic-bezier(.16,1,.3,1),transform 1.3s cubic-bezier(.16,1,.3,1)}' +
  '#shut.draw .gsub{opacity:1;transform:none;transition-delay:1.4s}' +
  '#shut .gspin{position:relative;width:30px;height:30px;opacity:0;transition:opacity 1s cubic-bezier(.4,0,.2,1)}' +
  '#shut.draw .gspin{opacity:1;transition-delay:2.4s}' +
  '#shut .gspin i{position:absolute;left:50%;top:50%;width:3px;height:9px;margin:-15px 0 0 -1.5px;border-radius:1.5px;background:#fff;transform-origin:1.5px 15px;animation:gspin .9s linear infinite}' +
  '#shut .gspin i:nth-child(1){transform:rotate(0deg);animation-delay:-.7875s}' +
  '#shut .gspin i:nth-child(2){transform:rotate(45deg);animation-delay:-.675s}' +
  '#shut .gspin i:nth-child(3){transform:rotate(90deg);animation-delay:-.5625s}' +
  '#shut .gspin i:nth-child(4){transform:rotate(135deg);animation-delay:-.45s}' +
  '#shut .gspin i:nth-child(5){transform:rotate(180deg);animation-delay:-.3375s}' +
  '#shut .gspin i:nth-child(6){transform:rotate(225deg);animation-delay:-.225s}' +
  '#shut .gspin i:nth-child(7){transform:rotate(270deg);animation-delay:-.1125s}' +
  '#shut .gspin i:nth-child(8){transform:rotate(315deg);animation-delay:0s}' +
  '#shut.end .gwrap{opacity:0;filter:blur(10px)}' +
  '@keyframes gspin{0%{opacity:1}100%{opacity:.12}}' +
  '@keyframes gsheen{0%{background-position:120% 0}60%{background-position:-80% 0}100%{background-position:-80% 0}}';
document.head.appendChild(pwrCss);

const pwrDlg = document.createElement('div');
pwrDlg.id = 'pwrdlg';
pwrDlg.innerHTML =
  '<div class="box">' +
  '<div class="pico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M12 3v9"/><path d="M17.66 6.34a8 8 0 1 1-11.32 0"/></svg></div>' +
  '<h3 id="pwr-t"></h3><p id="pwr-m"></p>' +
  '<div class="row"><button type="button" id="pwr-no">Annulla</button><button type="button" id="pwr-si"></button></div>' +
  '</div>';
document.body.appendChild(pwrDlg);

const shut = document.createElement('div');
shut.id = 'shut';
shut.innerHTML =
  '<div class="gwrap">' +
  '<div class="gtx">Grazie</div>' +
  '<div class="gsub">La presentazione \u00e8 terminata</div>' +
  '<div class="gspin"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>' +
  '</div>';
document.body.appendChild(shut);

const pwrT = document.getElementById('pwr-t');
const pwrM = document.getElementById('pwr-m');
const pwrNo = document.getElementById('pwr-no');
const pwrSi = document.getElementById('pwr-si');
let pwrFn = null;

function pwrAsk(titolo, testo, azione, fn) {
  pwrT.textContent = titolo;
  pwrM.textContent = testo;
  pwrSi.textContent = azione;
  pwrFn = fn;
  pwrDlg.classList.add('show');
}
pwrNo.addEventListener('click', () => pwrDlg.classList.remove('show'));
pwrDlg.addEventListener('click', e => { if (e.target === pwrDlg) pwrDlg.classList.remove('show'); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') pwrDlg.classList.remove('show'); });
pwrSi.addEventListener('click', () => {
  pwrDlg.classList.remove('show');
  if (pwrFn) pwrFn();
});

function fadeOff(poi, attesa) {
  ccClose();
  sndExit();
  closeAll();
  shut.classList.add('on');
  setTimeout(poi, attesa);
}

function spegni() {
  fadeOff(() => {
    shut.classList.add('draw');
    setTimeout(() => shut.classList.add('end'), 5400);
    setTimeout(() => { location.replace('logout.php'); }, 6600);
  }, 1250);
}

function riavvia() {
  fadeOff(() => { location.replace('hub.php?boot=1'); }, 1150);
}

const shutBtn = document.querySelector('.mitem.exit');
if (shutBtn) {
  shutBtn.addEventListener('click', e => {
    e.preventDefault();
    pwrAsk('Vuoi spegnere il computer adesso?', 'La sessione della presentazione verr\u00e0 chiusa.', 'Spegni', spegni);
  });
}
const rebBtn = document.querySelector('.mitem.reboot');
if (rebBtn) {
  rebBtn.addEventListener('click', e => {
    e.preventDefault();
    pwrAsk('Vuoi riavviare il computer adesso?', 'Il desktop ripartir\u00e0 dalla schermata di avvio.', 'Riavvia', riavvia);
  });
}

/* ============================================================
   Moduli UI specifici del desktop: Spotlight + Mappe
   Spostati qui per mantenere hub.php pulito e lasciare un solo punto
   JavaScript per il comportamento delle applicazioni.
   ============================================================ */
(function(){
  var spot=document.getElementById('spot');
  if(!spot)return;
  var box=spot.querySelector('.spot-box');
  var type=spot.querySelector('.spot-type');
  var TEXT='Le parole non sono mai neutre';
  var timer=null;
  function openSpot(){
    if(spot.classList.contains('on'))return;
    spot.classList.add('on');
    spot.setAttribute('aria-hidden','false');
    if(typeof sndOpen==='function'){try{sndOpen();}catch(e){}}
    type.textContent='';
    var i=0;
    clearInterval(timer);
    timer=setInterval(function(){
      type.textContent=TEXT.slice(0,++i);
      if(i>=TEXT.length)clearInterval(timer);
    },48);
  }
  function closeSpot(){
    if(!spot.classList.contains('on'))return;
    spot.classList.remove('on');
    spot.setAttribute('aria-hidden','true');
    clearInterval(timer);
    if(typeof sndClose==='function'){try{sndClose();}catch(e){}}
  }
  document.addEventListener('click',function(e){
    var t=e.target.closest('[data-spot]');
    if(t){e.preventDefault();openSpot();return;}
    if(spot.classList.contains('on')&&!box.contains(e.target))closeSpot();
  });
  document.addEventListener('keydown',function(e){if(e.key==='Escape')closeSpot();});
})();



/* ============================================================
   App Note reale dark: attività dal curriculum + polaroid/post-it.
   ============================================================ */
(function(){
  const app=document.querySelector('[data-notes-app]');
  if(!app) return;
  const notes=[
    {
      id:'curriculum', filter:'curriculum', tags:['scuola'], title:'Curriculum', meta:'Documento', label:'Percorso personale',
      image:'assets/notes/curriculum-cover.png', kind:'polaroid', caption:'Curriculum dello studente',
      body:['Documento sintetico del percorso scolastico e delle attività svolte fuori dall’aula.', 'Raccoglie formazione, PCTO, competenze di indirizzo e attività extrascolastiche senza trasformarle in racconto celebrativo.'],
      chips:['Tecnico - Informatica','ITIS “Cerebotani” - Lonato','In lavorazione']
    },
    {
      id:'pcto', filter:'pcto', tags:['scuola'], title:'CS Metal Europe', meta:'PCTO', label:'Formazione scuola-lavoro',
      image:'assets/notes/pcto-cs-metal.png', kind:'polaroid pcto', caption:'CS Metal Europe',
      body:['Esperienza svolta presso CS METAL EUROPE S.R.L. come attività realizzata in ambiente lavorativo.', 'Il valore della nota è preciso: scuola e azienda entrano in contatto, e le competenze tecniche vengono osservate nel contesto reale di una struttura produttiva.'],
      chips:['CS METAL EUROPE S.R.L.','Ambiente lavorativo','Scuola-lavoro']
    },
    {
      id:'sportly', filter:'projects', tags:['oratorio'], title:'Sportly', meta:'Book. Play. Connect.', label:'Concept digitale',
      image:'assets/notes/sportly.png', kind:'polaroid wide', caption:'Identità progetto', link:'https://gpoi.denuvo.studio', linkLabel:'Apri progetto GPOI',
      body:['Concept visivo e digitale collegato al mondo dello sport e della prenotazione.', 'La nota resta breve: un supporto creativo per mostrare attenzione a identità, interfaccia e presentazione del progetto.'],
      chips:['Book','Play','Connect']
    },
    {
      id:'cello', filter:'extra', tags:['arte'], title:'Violoncello', meta:'Musica', label:'Attività musicale',
      image:'assets/notes/violoncello.png', kind:'photo-note cello', caption:'Scuola di Musica “Elia Marini”',
      body:['Corso di Violoncello presso la Banda Musicale - Scuola di Musica “Elia Marini” di Calcinato.', 'Nel curriculum questa attività descrive un percorso musicale continuativo, legato a studio, ascolto, esercizio e disciplina dello strumento.'],
      chips:['Violoncello','Calcinato','Scuola di musica']
    },
    {
      id:'educatore', filter:'extra', tags:['oratorio'], title:'Animatore ed educatore', meta:'Oratorio', label:'Cittadinanza attiva',
      image:'assets/notes/animatore-educatore.png', kind:'photo-note group', caption:'Attività con i ragazzi',
      body:['Attività di animatore ed educatore presso l’Oratorio di Bedizzole.', 'Consiste nel partecipare alla vita dell’oratorio con responsabilità educative, organizzative e relazionali verso bambini e ragazzi.'],
      chips:['Oratorio di Bedizzole','Educazione','Gruppo']
    },
    {
      id:'volontariato', filter:'extra', tags:['oratorio'], title:'Festa del Sorriso', meta:'Volontariato', label:'Tornei dei Roncai',
      image:'assets/notes/volontariato-festa-sorriso.png', kind:'polaroid volontariato', caption:'Volontariato',
      body:['Volontariato presso la Festa del Sorriso e i Tornei dei Roncai, collegato alle attività dell’Oratorio di Bedizzole.', 'L’esperienza riguarda il supporto pratico e organizzativo durante iniziative locali, con presenza nei periodi indicati dal curriculum.'],
      chips:['Festa del Sorriso','Tornei dei Roncai','Volontariato']
    },
    {
      id:'concorso', filter:'culture', tags:['arte'], title:'Volo tra le Righe', meta:'Contest', label:'Concorso letterario',
      image:'assets/notes/volo-tra-le-righe.png', kind:'polaroid poster', caption:'Giovani lettori', link:'https://volo.denuvo.studio', linkLabel:'Apri Volo tra le Righe',
      body:['Partecipazione al contest letterario “Volo tra le Righe 3.0”.', 'Il curriculum riporta l’acquisizione dell’attestato di partecipazione: una nota culturale, legata a lettura, scrittura e confronto creativo.'],
      chips:['Letterario','Attestato','Biblioteca']
    }
  ];
  const list=app.querySelector('[data-notes-list]');
  const screen=app.querySelector('[data-note-screen]');
  const title=app.querySelector('[data-notes-title]');
  const count=app.querySelector('[data-notes-count]');
  let filter='all', current='curriculum', large=false, showPoints=true;
  const folderNames={all:'Tutte le note', curriculum:'Curriculum', pcto:'PCTO', projects:'Progetti', extra:'Attività extra', culture:'Cultura', scuola:'#scuola', oratorio:'#oratorio', arte:'#arte'};
  function visible(){return notes.filter(n=>filter==='all'||n.filter===filter||n.tags.includes(filter));}
  function esc(s){return String(s).replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));}
  function visual(n){
    if(n.image) return `<figure class="nr-visual ${esc(n.kind)}" data-missing="Immagine da inserire"><img src="${esc(n.image)}" alt="${esc(n.caption)}" onerror="this.closest('figure').classList.add('image-missing');this.remove();"><div class="nr-visual-art nr-fallback"><span>${esc(n.caption)}</span></div><figcaption>${esc(n.caption)}</figcaption></figure>`;
    return `<figure class="nr-visual ${esc(n.kind)}"><div class="nr-visual-art"><span>${esc(n.caption)}</span></div><figcaption>${esc(n.caption)}</figcaption></figure>`;
  }
  function renderList(){
    const rows=visible(); if(!rows.some(n=>n.id===current)) current=rows[0]?.id||notes[0].id;
    title.textContent=folderNames[filter]||'Note'; count.textContent=rows.length+(rows.length===1?' nota':' note');
    list.innerHTML=rows.map(n=>`<button class="nr-row ${n.id===current?'active':''}" data-note="${esc(n.id)}" type="button"><b>${esc(n.title)}</b><span>${esc(n.meta)}</span><p>${esc(n.body[0])}</p></button>`).join('');
  }
  function renderNote(){
    const n=notes.find(x=>x.id===current)||notes[0];
    screen.classList.remove('switching'); void screen.offsetWidth; screen.classList.add('switching');
    screen.classList.toggle('large', large);
    screen.innerHTML=`<div class="nr-note-date">${esc(n.meta)}</div><div class="nr-note-layout">${visual(n)}<section class="nr-note-copy"><span class="nr-note-label">${esc(n.label)}</span><h2>${esc(n.title)}</h2><div class="nr-note-body">${n.body.map(p=>`<p>${esc(p)}</p>`).join('')}</div>${n.link?`<a class="nr-project-link" href="${esc(n.link)}" target="_blank" rel="noopener">${esc(n.linkLabel||'Apri progetto')}</a>`:''}<div class="nr-points ${showPoints?'show':''}">${n.chips.map(p=>`<div><span></span>${esc(p)}</div>`).join('')}</div></section></div>`;
  }
  function sync(){app.querySelectorAll('[data-filter]').forEach(b=>b.classList.toggle('selected',b.dataset.filter===filter)); app.querySelector('[data-note-action="font"]')?.classList.toggle('active',large); app.querySelector('[data-note-action="check"]')?.classList.toggle('active',showPoints); app.querySelector('[data-note-action="focus"]')?.classList.toggle('active',app.classList.contains('focus'));}
  function render(){renderList(); renderNote(); sync();}
  app.addEventListener('click',e=>{
    const f=e.target.closest('[data-filter]'); if(f){filter=f.dataset.filter; render(); sndClick?.(); return;}
    const row=e.target.closest('[data-note]'); if(row){current=row.dataset.note; render(); sndOpen?.(); return;}
    const act=e.target.closest('[data-note-action]'); if(act){const a=act.dataset.noteAction; if(a==='font') large=!large; if(a==='check') showPoints=!showPoints; if(a==='focus') app.classList.toggle('focus'); render(); sndClick?.(); return;}
    const prev=e.target.closest('[data-note-prev]'); if(prev){const rows=visible(); const i=rows.findIndex(n=>n.id===current); current=rows[(i-1+rows.length)%rows.length]?.id||current; render(); sndClick?.();}
  });
  render();
})();



/* ============================================================
   PCTO Calendar — due anni di tirocinio, eventi mensili e dettaglio.
   ============================================================ */
(function(){
  const app=document.querySelector('[data-pcto-calendar]');
  if(!app) return;
  const months=['gennaio','febbraio','marzo','aprile','maggio','giugno','luglio','agosto','settembre','ottobre','novembre','dicembre'];
  const weekdays=['lun','mar','mer','gio','ven','sab','dom'];
  const data={
    2024:{
      key:'2024', yearLabel:'A.S. 2023/2024', className:'3ª I', month:3, year:2024, start:'2024-04-08', end:'2024-04-27', tutorSchool:'Savarino Annamaria', tutorCompany:'Delia Pea', hours:'120 ore',
      title:'Aprile 2024', subtitle:'3ª I · dal 08/04 al 27/04',
      events:[
        {id:'azienda-24', color:'#0A84FF', range:['2024-04-08','2024-04-12'], label:'Ufficio commerciale', title:'Ingresso in azienda e contesto', body:'Avvio del tirocinio presso CS Metal Europe: ufficio commerciale e logistico, affiancamento al tutor, osservazione dell’organizzazione aziendale e del collegamento tra ufficio vendite e magazzino.', bullets:['azienda: taglio su misura di acciai Proterial','uffici rinnovati su due piani','gestionale per comunicare con il magazzino']},
        {id:'dati-24', color:'#34C759', range:['2024-04-15','2024-04-19'], label:'Dati e grafica', title:'Fogli di calcolo, contenuti e invito', body:'Lavoro su formattazione e struttura di fogli di calcolo, creazione di contenuti promozionali e ideazione dell’invito per un evento aziendale.', bullets:['Excel per dati e tabelle','Adobe Photoshop e Illustrator','prime regole di scrittura efficace per marketing']},
        {id:'web-24', color:'#FF9500', range:['2024-04-22','2024-04-27'], label:'WordPress e blog', title:'Sito web aziendale e comunicazione', body:'Intervento sul sito aziendale tramite WordPress: sistemazione di problematiche, progettazione di una sezione blog e osservazione del lavoro commerciale.', bullets:['WordPress, HTML e CSS','blog aziendale','TeamSystem / Embyon come gestionale osservato']}
      ]
    },
    2025:{
      key:'2025', yearLabel:'A.S. 2024/2025', className:'4ª I', month:10, year:2024, start:'2024-11-11', end:'2024-11-30', tutorSchool:'Ottelli Manuele', tutorCompany:'Corrado Patriarchi', hours:'120 ore',
      title:'Novembre 2024', subtitle:'4ª I · dal 11/11 al 30/11',
      events:[
        {id:'magazzino-25', color:'#0A84FF', range:['2024-11-11','2024-11-16'], label:'Inventario', title:'Prima settimana: magazzino e inventario', body:'Attività in magazzino con DPI, controllo tra pezzi registrati nel sistema informatico e pezzi realmente presenti, verifica di spostamenti o tagli e annotazione delle rettifiche.', bullets:['guanti e metro per misurare i pezzi','rettifiche su foglio Excel','contatto con produzione e magazzino']},
        {id:'ufficio-25', color:'#BF5AF2', range:['2024-11-18','2024-11-23'], label:'WordPress', title:'Ufficio commerciale/logistico', body:'Passaggio in ufficio con postazione personale: aggiornamento del sito aziendale in WordPress, inserimento di articoli nella sezione blog e modifiche grafiche.', bullets:['WordPress per sito e blog','ufficio commerciale e logistico','osservazione richieste clienti e ordini']},
        {id:'social-25', color:'#FF2D55', range:['2024-11-25','2024-11-30'], label:'Social e Canva', title:'Comunicazione aziendale', body:'Creazione di contenuti per Instagram e LinkedIn, lavoro con Canva e maggiore attenzione a leggibilità, chiarezza e tono dei testi destinati ai lettori.', bullets:['Canva per contenuti online','post Instagram e LinkedIn','newsletter e testi più chiari']}
      ]
    }
  };
  let current='2024';
  let activeEvent=null;
  const grid=app.querySelector('[data-pcto-grid]');
  const detail=app.querySelector('[data-pcto-detail]');
  const title=app.querySelector('[data-pcto-title]');
  const subtitle=app.querySelector('[data-pcto-subtitle]');
  const mini=app.querySelector('[data-pcto-mini]');
  function parseDate(v){ const [y,m,d]=v.split('-').map(Number); return new Date(y,m-1,d); }
  function iso(d){ return d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-'+String(d.getDate()).padStart(2,'0'); }
  function inRange(day,ev){ const x=parseDate(day), a=parseDate(ev.range[0]), b=parseDate(ev.range[1]); return x>=a && x<=b; }
  function firstMonday(year,month){ const d=new Date(year,month,1); const offset=(d.getDay()+6)%7; d.setDate(d.getDate()-offset); return d; }
  function monthDays(period){ const first=firstMonday(period.year,period.month); const days=[]; for(let i=0;i<42;i++){ const d=new Date(first); d.setDate(first.getDate()+i); days.push(d); } return days; }
  function renderGrid(period){
    const days=monthDays(period);
    grid.innerHTML=weekdays.map(w=>`<div class="pcto-weekday">${w}</div>`).join('') + days.map(d=>{
      const day=iso(d);
      const faded=d.getMonth()!==period.month;
      const today=day===period.end;
      const evs=period.events.filter(ev=>inRange(day,ev));
      const eventLines=evs.map(ev=>{
        const start=day===ev.range[0];
        const end=day===ev.range[1];
        const label=start ? ev.label : '';
        return `<span class="pcto-day-range ${start?'start':''} ${end?'end':''} ${ev.id===activeEvent?'active':''}" style="--ev:${ev.color}" data-event="${ev.id}" title="${ev.label}">${label}</span>`;
      }).join('');
      return `<button class="pcto-day ${faded?'muted':''} ${today?'today':''}" data-day="${day}" type="button"><b>${d.getDate()}</b><span class="pcto-day-events">${eventLines}</span></button>`;
    }).join('');
  }
  function renderDetail(period, ev){
    detail.innerHTML=`<div class="pcto-detail-top"><span>${period.yearLabel}</span><h3>${period.className} · ${period.hours}</h3><p>${period.subtitle}</p></div><div class="pcto-tutors"><div><b>Tutor scuola</b><span>${period.tutorSchool}</span></div><div><b>Tutor azienda</b><span>${period.tutorCompany}</span></div></div><article class="pcto-event-card" style="--ev:${ev.color}"><span class="pcto-event-range">${ev.range[0].split('-').reverse().join('/')} — ${ev.range[1].split('-').reverse().join('/')}</span><h2>${ev.title}</h2><p>${ev.body}</p><ul>${ev.bullets.map(b=>`<li>${b}</li>`).join('')}</ul></article>`;
  }
  function renderMini(period){
    mini.innerHTML=`<div class="mini-head"><b>${months[period.month]} ${period.year}</b><span>${period.className}</span></div><div class="mini-strip">${period.events.map(ev=>`<button type="button" data-event="${ev.id}" style="--ev:${ev.color}"><span></span>${ev.label}</button>`).join('')}</div>`;
  }
  function sync(){
    const period=data[current];
    if(!activeEvent || !period.events.some(e=>e.id===activeEvent)) activeEvent=period.events[0].id;
    const ev=period.events.find(e=>e.id===activeEvent) || period.events[0];
    title.textContent=period.title;
    subtitle.textContent=period.subtitle;
    app.querySelectorAll('[data-pcto-year]').forEach(b=>b.classList.toggle('active',b.dataset.pctoYear===current));
    renderGrid(period); renderMini(period); renderDetail(period,ev);
    app.querySelectorAll(`[data-event="${ev.id}"]`).forEach(el=>el.classList.add('active'));
  }
  app.addEventListener('click',e=>{
    const y=e.target.closest('[data-pcto-year]'); if(y){current=y.dataset.pctoYear; activeEvent=null; sync(); sndClick?.(); return;}
    const ev=e.target.closest('[data-event]'); if(ev){activeEvent=ev.dataset.event; sync(); sndOpen?.(); return;}
    if(e.target.closest('[data-pcto-prev]')||e.target.closest('[data-pcto-next]')){current=current==='2024'?'2025':'2024'; activeEvent=null; sync(); sndClick?.();}
  });
  sync();
})();

/* Finder dark: tooltip flottante sopra a tutto e azioni reali */
(function(){
  const finder=document.querySelector('.finder-window');
  if(!finder) return;
  let tip=document.querySelector('.finder-floating-desc');
  if(!tip){
    tip=document.createElement('div');
    tip.className='finder-floating-desc';
    tip.setAttribute('aria-hidden','true');
    tip.innerHTML='<b></b><span></span>';
    document.body.appendChild(tip);
  }
  function place(btn){
    const r=btn.getBoundingClientRect();
    const tr=tip.getBoundingClientRect();
    let left=r.left + r.width/2 - tr.width/2;
    let top=r.bottom + 10;
    left=Math.max(14, Math.min(left, window.innerWidth - tr.width - 14));
    if(top + tr.height > window.innerHeight - 18) top = r.top - tr.height - 10;
    tip.style.left=Math.round(left)+'px';
    tip.style.top=Math.round(top)+'px';
  }
  function hide(){
    tip.classList.remove('show');
    tip.setAttribute('aria-hidden','true');
  }
  finder.querySelectorAll('.finder-app-icon').forEach(btn=>{
    btn.addEventListener('mouseenter',()=>{
      const name=btn.querySelector('b')?.textContent || 'Applicazione';
      tip.querySelector('b').textContent=name;
      tip.querySelector('span').textContent=btn.dataset.desc || '';
      tip.classList.add('show');
      tip.setAttribute('aria-hidden','false');
      requestAnimationFrame(()=>place(btn));
    });
    btn.addEventListener('mousemove',()=>place(btn));
    btn.addEventListener('mouseleave',hide);
    btn.addEventListener('click',e=>{
      if(btn.dataset.act==='trash'){
        e.preventDefault();
        if(typeof closeAll==='function') closeAll();
      }
      hide();
    });
  });
  window.addEventListener('scroll',hide,true);
  window.addEventListener('resize',hide);
})();

/* Dock visibility guard: resta sempre visibile se nessuna app è a schermo intero */
(function(){
  const d=document.getElementById('dock');
  if(!d) return;
  function keepDockState(){
    if(typeof hasFullscreenApp === 'function' && !hasFullscreenApp()) d.classList.remove('autohide');
  }
  document.addEventListener('click',()=>setTimeout(keepDockState,60),true);
  window.addEventListener('resize',keepDockState);
  setInterval(keepDockState,500);
  keepDockState();
})();

/* Window manager stability override: dimensioni stabili + Finder sempre presente */
const FINDER_ID = 'w-pres';
function isFinder(win){ return !!win && win.id === FINDER_ID; }
function hasFullscreenApp(){
  return Array.from(document.querySelectorAll('.win.open.maxi')).some(w => !isFinder(w));
}
function rectFromElement(win){
  const r = win.getBoundingClientRect();
  return {
    left: Math.round(r.left),
    top: Math.round(r.top),
    width: Math.round(r.width),
    height: Math.round(r.height),
    radius: 24
  };
}
function captureNormal(win){
  if(!win || win.classList.contains('maxi')) return;
  const r = rectFromElement(win);
  if(r.width > 80 && r.height > 80) win._normalRect = r;
}
function ensureNormalRect(win){
  if(!win._normalRect){
    const r = rectFromElement(win);
    win._normalRect = { left:r.left, top:r.top, width:r.width, height:r.height, radius:24 };
  }
  return win._normalRect;
}
function syncFinderVisibility(){
  const finder = document.getElementById(FINDER_ID);
  if(!finder) return;
  if(!hasFullscreenApp()){
    finder.classList.remove('closing');
    if(!finder.classList.contains('open')) finder.classList.add('open');
    if(!finder.style.zIndex) focusWin(finder);
    syncDock();
    if(typeof dockShow === 'function') dockShow();
  }
}
function fitWin(win){
  if(!win || win.classList.contains('maxi')) return;
  requestAnimationFrame(() => {
    const r = win.getBoundingClientRect();
    const limit = window.innerHeight - 82;
    if(r.bottom > limit) win.style.top = Math.max(40, Math.round(limit - r.height)) + 'px';
    captureNormal(win);
  });
}
function openWin(id){
  const win = document.getElementById(id);
  if(!win) return;
  if(!win.classList.contains('open')){
    win.classList.remove('closing');
    win.classList.add('open');
    sndOpen();
  }
  focusWin(win);
  if(!win.classList.contains('maxi')) captureNormal(win);
  fitWin(win);
  syncDock();
  syncFinderVisibility();
}
function closeWin(win){
  if(!win) return;
  if(isFinder(win) && !hasFullscreenApp()){
    win.classList.remove('closing');
    win.classList.add('open');
    focusWin(win);
    syncDock();
    if(typeof dockShow === 'function') dockShow();
    return;
  }
  sndClose();
  if(!win.classList.contains('maxi')) captureNormal(win);
  win.classList.add('closing');
  setTimeout(() => {
    win.classList.remove('open','closing','maxi');
    win.style.transition = '';
    win.style.removeProperty('max-height');
    win.style.removeProperty('border-radius');
    win._mxBusy = false;
    syncDock();
    syncFinderVisibility();
  },260);
}
function closeAll(){
  document.querySelectorAll('.win.open').forEach((w,i)=>{
    if(isFinder(w)) return;
    setTimeout(()=>closeWin(w), i*60);
  });
  setTimeout(syncFinderVisibility, 340);
}
function toggleMax(win){
  if(!win) return;
  focusWin(win);
  if(win._mxBusy) return;
  const goMax = !win.classList.contains('maxi');
  const start = rectFromElement(win);
  win._mxBusy = true;
  setWinBox(win, start, false);
  void win.offsetWidth;
  let target;
  if(goMax){
    win._normalRect = { left:start.left, top:start.top, width:start.width, height:start.height, radius:24 };
    win.classList.add('maxi');
    sndOpen();
    target = maxiBounds();
  } else {
    target = ensureNormalRect(win);
    target = { left:target.left, top:target.top, width:target.width, height:target.height, radius:24 };
    sndClose();
    if(typeof dockWake === 'function') dockWake();
  }
  requestAnimationFrame(()=>setWinBox(win, target, true));
  setTimeout(()=>{
    win.style.transition='';
    if(!goMax){
      win.classList.remove('maxi');
      setWinBox(win, target, false);
      win.style.transition='';
      captureNormal(win);
    }
    win._mxBusy=false;
    syncDock();
    syncFinderVisibility();
  },430);
}
window.addEventListener('resize',()=>{
  document.querySelectorAll('.win.maxi').forEach(w=>setWinBox(w, maxiBounds(), false));
  syncFinderVisibility();
});
document.querySelectorAll('.win').forEach(w=>{ if(!w.classList.contains('maxi')) captureNormal(w); });
setInterval(syncFinderVisibility, 500);
syncFinderVisibility();

/* Maps Navigator — percorso animato con spiegazioni */
(function(){
  const app=document.querySelector('[data-maps-navigator]');
  if(!app) return;
  const cam=app.querySelector('#maps-cam');
  const puck=app.querySelector('#maps-puck');
  const progress=app.querySelector('#maps-route-progress');
  const stopsBox=app.querySelector('[data-maps-stops]');
  const dots=app.querySelector('[data-maps-dots]');
  const recent=app.querySelector('[data-maps-recent]');
  const title=app.querySelector('[data-maps-title]');
  const kicker=app.querySelector('[data-maps-kicker]');
  const copy=app.querySelector('[data-maps-copy]');
  const icon=app.querySelector('[data-maps-icon]');
  const eta=app.querySelector('[data-maps-eta]');
  const sub=app.querySelector('[data-maps-sub]');
  if(!cam||!stopsBox||!dots){return;} /* app navigatore svuotata: niente da inizializzare */
  const stops=[
    {x:132,y:565,p:0,icon:'→',kicker:'Partenza',title:'FSL · PCTO',eta:'Inizio percorso',sub:'esperienza concreta · primo contatto col lavoro',copy:'Il viaggio parte dal PCTO: un’esperienza pratica che trasforma la scuola in metodo, responsabilità e contatto reale con il mondo professionale.',explain:'Prima tappa: lavoro, metodo, responsabilità.'},
    {x:392,y:438,p:.33,icon:'↱',kicker:'Tra poco',title:'Diploma',eta:'Tappa di consolidamento',sub:'maturità · competenze · consapevolezza',copy:'Il diploma chiude il percorso scolastico e diventa il ponte: non solo un traguardo, ma la prova di aver costruito basi tecniche e personali.',explain:'Il diploma come passaggio verso scelte più grandi.'},
    {x:636,y:272,p:.66,icon:'↑',kicker:'Prosegui',title:'Università a Brescia',eta:'Direzione Brescia',sub:'Ingegneria Informatica · basi solide',copy:'La prossima direzione è Ingegneria Informatica a Brescia: approfondire software, sistemi e progettazione per crescere con basi più solide.',explain:'Studio verticale per diventare più forte nel software.'},
    {x:1015,y:62,p:1,icon:'✈',kicker:'Arrivo',title:'Opportunità all’estero',eta:'Sogno America',sub:'carriera internazionale · software',copy:'La meta più ambiziosa è spostarmi, cercare opportunità all’estero e costruire una carriera nel software in un contesto internazionale: il sogno è l’America.',explain:'Meta finale: America, lavoro e crescita internazionale.'}
  ];
  let current=0, zoom=1;
  stopsBox.innerHTML=stops.map((s,i)=>`<button class="maps-stop-line ${i===0?'active':''}" data-maps-step="${i}" type="button"><span>${i===0?'●':i}</span><b>${s.title}</b></button>`).join('');
  dots.innerHTML=stops.map((_,i)=>`<button class="maps-dot ${i===0?'on':''}" data-maps-step="${i}" type="button" aria-label="Tappa ${i+1}"></button>`).join('');
  function render(){
    const s=stops[current];
    const scale=1.07*zoom;
    const dx=650-scale*s.x, dy=360-scale*s.y;
    cam.style.transform=`translate(${dx.toFixed(1)}px,${dy.toFixed(1)}px) scale(${scale.toFixed(3)})`;
    puck.setAttribute('transform',`translate(${s.x} ${s.y})`);
    progress.style.strokeDashoffset=(1-s.p).toFixed(4);
    title.textContent=s.title; kicker.textContent=s.kicker; copy.textContent=s.copy; icon.textContent=s.icon;
    eta.textContent=s.eta; sub.textContent=s.sub; recent.textContent=s.title;
    app.querySelectorAll('[data-maps-step]').forEach(el=>el.classList.toggle('on', +el.dataset.mapsStep===current));
    app.querySelectorAll('.maps-stop-line').forEach((el,i)=>{el.classList.toggle('active',i===current);el.classList.toggle('done',i<current);});
    app.querySelectorAll('.maps-dot').forEach((el,i)=>{el.classList.toggle('on',i===current);el.classList.toggle('done',i<current);});
    app.querySelectorAll('.maps-marker').forEach((el,i)=>{el.classList.toggle('on',i===current);el.classList.toggle('done',i<current);});
  }
  function go(i){current=(i+stops.length)%stops.length; render(); try{sndOpen?.()}catch(e){} }
  app.addEventListener('click',e=>{
    const st=e.target.closest('[data-maps-step]'); if(st){go(+st.dataset.mapsStep); return;}
    if(e.target.closest('[data-maps-next]')){go(current+1); return;}
    const z=e.target.closest('[data-maps-zoom]'); if(z){zoom=Math.max(.82,Math.min(1.45,zoom+(z.dataset.mapsZoom==='in'?.14:-.14))); render(); return;}
    if(e.target.closest('[data-maps-reset]')){zoom=1; render(); return;}
    if(e.target.closest('.maps-map')) go(current+1);
  });
  render();
})();

