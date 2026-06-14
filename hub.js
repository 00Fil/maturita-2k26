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

const ORDER = ['w-pres', 'w-io', 'w-skills', 'w-fsl', 'w-fine'];
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
function dockHide() { if (!dockHot && performance.now() >= dockGrace) dock.classList.add('autohide'); }
function dockWake(ms) { dockShow(); dockGrace = performance.now() + (ms || 1500); }
document.addEventListener('pointermove', e => {
  if (e.clientY >= window.innerHeight - 80) dockShow();
  else if (e.clientY < window.innerHeight - 110) dockHide();
});
dock.addEventListener('pointerenter', () => { dockHot = true; dockShow(); });
dock.addEventListener('pointerleave', () => { dockHot = false; dockHide(); });
setTimeout(() => dock.classList.add('autohide'), 2800);

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
  const end = () => { drag = false; };
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

(function(){
  var map=document.getElementById('mv-map');
  var cam=document.getElementById('mv-cam');
  var puck=document.getElementById('mv-puck');
  var trav=document.getElementById('mv-trav');
  if(!map||!cam||!puck)return;
  var stops=[
    {x:170,y:520,p:0,m:'m0',name:'Maturità al Cerebotani',kind:'Partenza',kk:'Partenza',body:'È il punto di partenza. In cinque anni al Cerebotani ho imparato a programmare e a risolvere i problemi con metodo. Da qui parte la strada che ho in mente per il dopo.'},
    {x:390,y:430,p:0.30,m:'m1',name:'PCTO · CS Metal Europe',kind:'Sosta',kk:'Sosta lungo la strada',body:'Una sosta lungo il tragitto, come il rifornimento prima di un viaggio lungo. In azienda ho lavorato sui dati, sulla comunicazione e su un e-commerce vero, fino al mio primo contratto. Qui ho capito quale direzione voglio prendere.'},
    {x:610,y:300,p:0.63,m:'m2',name:'Università · Informatica',kind:'Tappa',kk:'La prossima tappa',body:'Voglio continuare a studiare informatica. Mi serve per costruire software con basi più solide e arrivare preparato al lavoro.'},
    {x:850,y:150,p:1,m:'m3',name:'Lavorare all\'estero',kind:'Arrivo',kk:'La meta del viaggio',body:'La meta del viaggio. Voglio portare quello che ho imparato fuori dall\'Italia e lavorare nel software in un contesto internazionale.'}
  ];
  var list=document.getElementById('nv-list'),elKk=document.getElementById('nv-kk'),elBody=document.getElementById('nv-body'),elStep=document.getElementById('nv-step'),elEta=document.getElementById('nv-eta'),elHint=document.getElementById('nv-hint');
  var i=0,n=stops.length,j;
  for(j=0;j<n;j++){
    var s=stops[j];
    var col=j===0?'#34C759':(j===n-1?'#FF3B30':'#8E8E93');
    var ct=(j===0||j===n-1)?'':String(j);
    var b=document.createElement('button');
    b.className='dir-row';b.setAttribute('data-i',j);
    b.innerHTML='<span class="dir-pin" style="--c:'+col+'">'+ct+'</span><span class="dir-txt"><b>'+s.name+'</b><small>'+s.kind+'</small></span>';
    list.appendChild(b);
  }
  var sc=1.16,zoom=1,VW=1000,VH=640,TX=600,TY=320,minX=-300,maxX=1300,minY=-260,maxY=900;
  function render(){
    var s=stops[i],k,e=sc*zoom;
    puck.setAttribute('transform','translate('+s.x+' '+s.y+')');
    trav.setAttribute('stroke-dashoffset',(1-s.p).toFixed(4));
    var dx=TX-e*s.x, dy=TY-e*s.y;
    dx=Math.max(VW-e*maxX,Math.min(-e*minX,dx));
    dy=Math.max(VH-e*maxY,Math.min(-e*minY,dy));
    cam.setAttribute('transform','translate('+dx.toFixed(1)+' '+dy.toFixed(1)+') scale('+e.toFixed(3)+')');
    elKk.textContent=s.kk;elBody.textContent=s.body;
    elStep.textContent=(i+1)+'/'+n;
    elEta.textContent=(i===n-1)?'sei arrivato':'prossima: '+stops[i+1].kind.toLowerCase();
    elHint.textContent=(i===n-1)?'Tocca per ricominciare':'Tocca la mappa per proseguire';
    var mks=cam.querySelectorAll('.mk');for(k=0;k<mks.length;k++)mks[k].classList.remove('on');
    var cur=document.getElementById(s.m);if(cur)cur.classList.add('on');
    var rows=list.children;for(k=0;k<rows.length;k++)rows[k].className='dir-row'+(k===i?' on':'');
  }
  function go(ni){i=((ni%n)+n)%n;render();if(typeof sndOpen==='function'){try{sndOpen();}catch(e){}}}
  map.addEventListener('click',function(){go(i+1);});
  list.addEventListener('click',function(e){var t=e.target.closest('.dir-row');if(!t)return;go(parseInt(t.getAttribute('data-i'),10));});
  var zin=document.getElementById('mv-zin'),zout=document.getElementById('mv-zout'),comp=document.getElementById('mv-comp');
  if(zin)zin.addEventListener('click',function(ev){ev.stopPropagation();zoom=Math.min(1.5,zoom+0.18);render();});
  if(zout)zout.addEventListener('click',function(ev){ev.stopPropagation();zoom=Math.max(0.8,zoom-0.18);render();});
  if(comp)comp.addEventListener('click',function(ev){ev.stopPropagation();zoom=1;render();});
  var x=document.querySelector('#w-fine .dir-x');
  if(x)x.addEventListener('click',function(ev){ev.stopPropagation();if(typeof closeWin==='function')closeWin(document.getElementById('w-fine'));});
  render();
})();

/* ============================================================
   App Note funzionale: cartelle, tag, note, toolbar e checklist reali
   ============================================================ */
(function(){
  const app = document.querySelector('[data-notes-app]');
  if (!app) return;
  const notes = [
    { id:'identity', title:'Chi sono', filter:'featured', tags:['method'], meta:'Oggi', state:'Nota fissata', kicker:'Identità',
      summary:'<mark>Capisco come funzionano le cose, poi le costruisco.</mark> Parto dalla curiosità, studio il problema e lo trasformo in qualcosa che funzioni davvero.',
      main:['Non mi interessa solo saper usare gli strumenti: voglio capire il perché dietro le cose, dal codice alla comunicazione.', 'Il mio modo di lavorare è osservare, smontare, progettare e poi rifinire finché il risultato non sembra naturale.'],
      sideTitle:'La frase guida', side:'Belle fuori, solide dentro, utili per qualcuno.', minis:[['Metodo','Curiosità, ordine e attenzione ai dettagli.'],['Carattere','Determinazione, responsabilità e voglia di arrivare fino in fondo.']], checks:['Spiegare con chiarezza','Costruire con metodo','Curare i dettagli','Portare a termine'] },
    { id:'school', title:'Il mio percorso', filter:'school', tags:['method'], meta:'Scuola', state:'Formazione', kicker:'Cerebotani · Informatica',
      summary:'Ho scelto Informatica e Telecomunicazioni perché volevo entrare in un mondo concreto: software, sistemi, reti e progettazione.',
      main:['All’IIS Cerebotani ho imparato che la tecnologia non è solo codice: è metodo, documentazione e capacità di spiegare quello che si fa.', 'Le materie tecniche mi hanno abituato a ragionare per sistemi: capire le parti, collegarle e farle funzionare insieme.'],
      sideTitle:'Cosa resta', side:'Il valore non è memorizzare strumenti, ma imparare a imparare strumenti nuovi.', minis:[['Competenze','Applicazioni, reti, sistemi e gestione progetto.'],['Comunicazione','Documentare e presentare un lavoro tecnico.']], checks:['Reti e sistemi','Sviluppo applicazioni','Documentazione','Progetti'] },
    { id:'pcto', title:'CS Metal Europe', filter:'work', tags:['pcto','method'], meta:'PCTO', state:'Esperienza reale', kicker:'Scuola-lavoro',
      summary:'Due anni nello stesso contesto aziendale mi hanno fatto vedere come un lavoro tecnico diventa utile quando entra in un processo reale.',
      main:['Ho lavorato su dati di produzione, immagini, sito, blog, magazzino e comunicazione.', 'La cosa più importante è stata capire che anche una tabella, una frase o una foto sono scelte progettuali se devono aiutare qualcuno a capire meglio.'],
      sideTitle:'Lezione chiave', side:'Scrivere vuol dire pensare a chi legge. Costruire software vuol dire pensare a chi lo userà.', minis:[['Azienda','CS Metal Europe, Bedizzole.'],['Risultato','Un progetto concreto, collegato al mondo del lavoro.']], checks:['Dati produzione','Comunicazione','Magazzino','E-commerce'] },
    { id:'projects', title:'Progetti personali', filter:'work', tags:['method','community'], meta:'Dal 2024', state:'In costruzione', kicker:'Fuori dal programma',
      summary:'I progetti personali sono il punto in cui la scuola incontra la mia iniziativa: prendo una necessità reale e provo a farla diventare software.',
      main:['Il gestionale per l’oratorio di Bedizzole nasce da un bisogno concreto e mi ha costretto a ragionare su persone, flussi e responsabilità.', 'denuvo.studio è lo spazio in cui raccolgo ciò che costruisco e dove provo a unire codice, identità visiva e cura del dettaglio.'],
      sideTitle:'Approccio', side:'Prima il problema, poi la struttura, poi l’interfaccia. Il codice arriva quando l’idea è chiara.', minis:[['Gestionale','Software per oratorio e attività locali.'],['Denuvo','Portfolio, ricerca visiva e sviluppo web.']], checks:['Analisi bisogno','UI ordinata','Backend solido','Deploy'] },
    { id:'outside', title:'Fuori dall’aula', filter:'life', tags:['community'], meta:'Esperienze', state:'Comunità', kicker:'Persone e responsabilità',
      summary:'Fuori dalla scuola ho imparato a collaborare, parlare con persone diverse e prendermi cura di qualcosa che non riguarda solo me.',
      main:['Volontariato, Torneo dei Roncai, Festa del Sorriso e animazione in oratorio mi hanno insegnato presenza e affidabilità.', 'Il concorso Volo tra le Righe mi ha mostrato che anche creatività e racconto possono diventare un progetto strutturato.'],
      sideTitle:'Cosa mi ha dato', side:'La tecnologia serve di più quando nasce da attenzione verso le persone.', minis:[['Volontariato','Organizzazione, squadra, disponibilità.'],['Concorso','Playlist narrativa e comunicazione creativa.']], checks:['Collaborare','Organizzare','Comunicare','Aiutare'] },
    { id:'music', title:'Musica e disciplina', filter:'life', tags:['method'], meta:'Violoncello', state:'Costanza', kicker:'Dal 2018',
      summary:'Il violoncello mi ha insegnato che migliorare non è un colpo di fortuna: è ripetizione, ascolto e correzione continua.',
      main:['Studiare uno strumento significa accettare che il dettaglio conta: postura, tempo, suono, intenzione.', 'È la stessa disciplina che provo a portare nei progetti: iterare, ascoltare il risultato, correggere.'],
      sideTitle:'Metodo musicale', side:'Ripeti, ascolta, correggi. Poi ripeti meglio.', minis:[['Pazienza','Non tutto funziona al primo tentativo.'],['Precisione','Il dettaglio cambia la qualità percepita.']], checks:['Esercizio','Ascolto','Correzione','Costanza'] },
    { id:'future', title:'Dove voglio andare', filter:'featured', tags:['method'], meta:'Dopo il diploma', state:'Direzione', kicker:'Prossima tappa',
      summary:'Voglio continuare a crescere nel software, costruendo prodotti curati, solidi e comprensibili anche fuori dal contesto scolastico.',
      main:['La direzione è continuare con l’informatica, rafforzare le basi e imparare a lavorare in contesti più grandi e internazionali.', 'Mi interessa il punto in cui tecnologia, design e utilità si incontrano: quando un prodotto non solo funziona, ma comunica qualità.'],
      sideTitle:'Obiettivo', side:'Diventare una persona capace di costruire software che abbia identità, struttura e impatto.', minis:[['Studio','Basi più solide e visione ampia.'],['Lavoro','Contesti reali, internazionali, ambiziosi.']], checks:['Studiare informatica','Crescere nel design','Lavorare su prodotti reali','Pensare internazionale'] }
  ];
  const list = app.querySelector('[data-notes-list]');
  const screen = app.querySelector('[data-note-screen]');
  const title = app.querySelector('[data-notes-title]');
  const count = app.querySelector('[data-notes-count]');
  const state = app.querySelector('[data-note-state]');
  let filter = 'all', current = 'identity', textSize = 'normal', grid = false, checklist = false;
  function visible(){ return notes.filter(n => filter === 'all' || n.filter === filter || n.tags.includes(filter) || (filter === 'featured' && ['identity','future','pcto'].includes(n.id))); }
  function renderList(){
    const rows = visible();
    if (!rows.some(n => n.id === current)) current = rows[0]?.id || notes[0].id;
    title.textContent = filter === 'all' ? 'Tutte iCloud' : (app.querySelector(`[data-filter="${filter}"] b`)?.textContent || app.querySelector(`[data-filter="${filter}"]`)?.textContent || 'Note');
    count.textContent = rows.length + (rows.length === 1 ? ' nota' : ' note');
    list.innerHTML = rows.map(n => `<button class="note-preview ${n.id===current?'on':''}" data-note="${n.id}" type="button"><b>${n.title}</b><small>${n.meta}</small><p>${strip(n.summary)}</p></button>`).join('');
  }
  function strip(s){ return s.replace(/<[^>]+>/g,''); }
  function renderNote(){
    const n = notes.find(x => x.id === current) || notes[0];
    state.textContent = n.state;
    screen.dataset.size = textSize;
    screen.dataset.view = grid ? 'grid' : 'page';
    screen.innerHTML = `<div class="note-topline"><span>14 giugno 2026 · ${n.meta}</span><span class="copy-status" data-copy-status>Copiato</span></div><span class="note-kicker">${n.kicker}</span><h2>${n.title}</h2><p class="note-summary">${n.summary}</p><div class="note-layout"><div class="note-main-card">${n.main.map(p=>`<p>${p}</p>`).join('')}<div class="note-check-panel ${checklist?'on':''}"><ul class="note-check-list">${n.checks.map((c,i)=>`<li><button type="button" data-check="${i}" aria-label="Completa ${c}"></button>${c}</li>`).join('')}</ul></div></div><div class="note-side"><div class="note-side-card"><b>${n.sideTitle}</b><p>${n.side}</p></div><div class="note-mini-grid">${n.minis.map(m=>`<div class="note-mini-card"><b>${m[0]}</b><p>${m[1]}</p></div>`).join('')}</div></div></div>`;
  }
  function render(){ renderList(); renderNote(); syncButtons(); }
  function syncButtons(){
    app.querySelectorAll('[data-filter]').forEach(b => b.classList.toggle('on', b.dataset.filter === filter));
    app.querySelector('[data-note-action="font"]')?.classList.toggle('on', textSize === 'large');
    app.querySelector('[data-note-action="grid"]')?.classList.toggle('on', grid);
    app.querySelector('[data-note-action="check"]')?.classList.toggle('on', checklist);
    app.querySelector('[data-note-action="focus"]')?.classList.toggle('on', app.classList.contains('focus'));
  }
  app.addEventListener('click', e => {
    const f = e.target.closest('[data-filter]');
    if (f) { filter = f.dataset.filter; render(); sndClick?.(); return; }
    const row = e.target.closest('[data-note]');
    if (row) { current = row.dataset.note; render(); sndOpen?.(); return; }
    const chk = e.target.closest('[data-check]');
    if (chk) { chk.closest('li').classList.toggle('done'); sndClick?.(); return; }
    const act = e.target.closest('[data-note-action]');
    if (act) {
      const a = act.dataset.noteAction;
      if (a === 'font') textSize = textSize === 'large' ? 'normal' : 'large';
      if (a === 'grid') grid = !grid;
      if (a === 'check') checklist = !checklist;
      if (a === 'focus') app.classList.toggle('focus');
      if (a === 'share') {
        const n = notes.find(x => x.id === current);
        navigator.clipboard?.writeText(strip(n.summary)).catch(()=>{});
        const st = app.querySelector('[data-copy-status]');
        if (st) { st.classList.add('show'); setTimeout(()=>st.classList.remove('show'), 950); }
        sndClick?.();
        return;
      }
      render(); sndClick?.(); return;
    }
    const prev = e.target.closest('[data-note-prev]');
    if (prev) {
      const rows = visible(); const i = rows.findIndex(n => n.id === current);
      current = rows[(i - 1 + rows.length) % rows.length]?.id || current;
      render(); sndClick?.();
    }
  });
  render();
})();

