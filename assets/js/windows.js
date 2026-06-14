/* ============================================================================
   windows.js — Gestione finestre + semaforo + Finder + riflesso schede
   ----------------------------------------------------------------------------
   Inietta il semaforo (chiudi/riduci/ingrandisci) in ogni titlebar, apre e
   chiude le finestre, gestisce massimizza/ripristina, il trascinamento, la
   navigazione tra capitoli, il controllo segmentato del Finder e l'evidenziazione
   per tag della sidebar. Espone openWin/closeWin/closeAll/focusWin su window.
   ========================================================================== */
(function () {
  /* --- Semaforo: glifi e iniezione nelle titlebar --- */
  const SVG_X   = '<svg viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M2.5 2.5l5 5M7.5 2.5l-5 5"/></svg>';
  const SVG_MIN = '<svg viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M2 5h6"/></svg>';
  const SVG_MAX = '<svg viewBox="0 0 10 10" fill="currentColor"><path d="M2.6 6.6L6.6 2.6H2.6zM7.4 3.4L3.4 7.4h4z"/></svg>';
  document.querySelectorAll('.win .titlebar').forEach(tb => {
    const lights = document.createElement('span');
    lights.className = 'lights';
    lights.innerHTML =
      '<button class="c-close" aria-label="Chiudi">' + SVG_X + '</button>' +
      '<button class="c-min" aria-label="Riduci">' + SVG_MIN + '</button>' +
      '<button class="c-max" aria-label="Ingrandisci">' + SVG_MAX + '</button>';
    tb.prepend(lights);
  });

  /* --- Stato z-index e sincronizzazione con il dock --- */
  let zTop = 20;
  function focusWin(win) { zTop += 1; win.style.zIndex = zTop; }
  function syncDock() {
    document.querySelectorAll('.dapp[data-w]').forEach(d => {
      const w = document.getElementById(d.dataset.w);
      d.classList.toggle('running', !!w && w.classList.contains('open'));
    });
  }
  /* Mantiene la finestra dentro lo schermo (sopra il dock). */
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
      if (typeof sndOpen === 'function') sndOpen();
    }
    focusWin(win);
    fitWin(win);
    syncDock();
  }
  /* Accetta sia un elemento finestra sia il suo id (correzione: il pulsante di
     chiusura della mappa passava una stringa e in precedenza andava in errore). */
  function closeWin(win) {
    if (typeof win === 'string') win = document.getElementById(win);
    if (!win) return;
    if (typeof sndClose === 'function') sndClose();
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

  /* --- Massimizza / ripristina con animazione delle coordinate --- */
  const MAXI_EASE = 'cubic-bezier(.32,.72,0,1)';
  function maxiBounds() {
    return { left: 0, top: 34, width: window.innerWidth, height: window.innerHeight - 34, radius: 0 };
  }
  function setWinBox(win, r, anim) {
    win.style.setProperty('max-height', 'none', 'important');
    win.style.transition = anim ? ('left .5s ' + MAXI_EASE + ', top .5s ' + MAXI_EASE + ', width .5s ' + MAXI_EASE + ', height .5s ' + MAXI_EASE + ', border-radius .5s ' + MAXI_EASE) : 'none';
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
      if (typeof sndOpen === 'function') sndOpen();
      target = maxiBounds();
    } else {
      target = win._restore
        ? { left: win._restore.left, top: win._restore.top, width: win._restore.width, height: win._restore.height, radius: 24 }
        : { left: start.left, top: start.top, width: start.width, height: start.height, radius: 24 };
      if (typeof sndClose === 'function') sndClose();
      if (typeof dockWake === 'function') dockWake();
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
    }, 560);
  }
  window.addEventListener('resize', () => {
    document.querySelectorAll('.win.maxi').forEach(w => setWinBox(w, maxiBounds(), false));
  });

  /* --- Collegamento dei semafori e del fuoco a ogni finestra --- */
  document.querySelectorAll('.win').forEach(win => {
    win.addEventListener('pointerdown', () => focusWin(win));
    win.querySelector('.c-close').addEventListener('click', e => { e.stopPropagation(); closeWin(win); });
    win.querySelector('.c-min').addEventListener('click', e => { e.stopPropagation(); closeWin(win); });
    win.querySelector('.c-max').addEventListener('click', e => { e.stopPropagation(); toggleMax(win); });
  });

  /* Qualsiasi elemento con [data-open] apre la finestra corrispondente. */
  document.addEventListener('click', e => {
    const t = e.target.closest('[data-open]');
    if (t) openWin(t.dataset.open);
  });

  /* --- Navigazione tra capitoli (pulsanti [data-nav], se presenti) --- */
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

  /* --- Finder: controllo segmentato Galleria / Elenco --- */
  const fgrid = document.querySelector('.fgrid');
  document.querySelectorAll('.fseg button').forEach(b => {
    b.addEventListener('click', () => {
      document.querySelectorAll('.fseg button').forEach(x => x.classList.toggle('on', x === b));
      if (fgrid) fgrid.classList.toggle('list', b.dataset.view === 'list');
    });
  });

  /* --- Finder: i tag della sidebar evidenziano le schede corrispondenti --- */
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

  /* --- Riflesso che segue il puntatore sulle schede in vetro --- */
  document.addEventListener('pointermove', e => {
    const card = e.target.closest('.lgcard');
    if (!card) return;
    const r = card.getBoundingClientRect();
    card.style.setProperty('--mx', ((e.clientX - r.left) / r.width * 100).toFixed(1) + '%');
    card.style.setProperty('--my', ((e.clientY - r.top) / r.height * 100).toFixed(1) + '%');
  });

  /* --- Trascinamento delle finestre dalla titlebar --- */
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

  /* --- Stato iniziale --- */
  syncDock();
  const first = document.querySelector('.win.open');
  if (first) { focusWin(first); fitWin(first); }

  /* Esporto le API usate dagli altri moduli (dock, centro di controllo). */
  window.openWin = openWin;
  window.closeWin = closeWin;
  window.closeAll = closeAll;
  window.focusWin = focusWin;
})();
