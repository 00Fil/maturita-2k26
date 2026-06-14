/* ============================================================================
   control-center.js — Centro di Controllo + batteria + spegnimento
   ----------------------------------------------------------------------------
   Pannello in vetro con Wi-Fi, condivisione/copia link, modalità presentazione,
   schermo intero, riavvio, e i cursori di luminosità e volume (persistiti in
   localStorage). Gestisce anche il dialogo di conferma e la schermata di
   spegnimento. Tutto lo stile vive nel design system: qui solo il comportamento.
   ========================================================================== */
(function () {
  const $ = id => document.getElementById(id);

  /* --- Apertura/chiusura del pannello --- */
  const ccBtn = $('ccbtn'), ccPanel = $('ccpanel'), dim = $('dim');
  function ccOpen() { ccPanel && ccPanel.classList.add('on'); dim && dim.classList.add('cc'); }
  function ccClose() { ccPanel && ccPanel.classList.remove('on'); dim && dim.classList.remove('cc'); }
  if (ccBtn) ccBtn.addEventListener('click', e => { e.stopPropagation(); ccPanel.classList.contains('on') ? ccClose() : ccOpen(); });
  document.addEventListener('click', e => {
    if (ccPanel && ccPanel.classList.contains('on') && !ccPanel.contains(e.target) && e.target !== ccBtn) ccClose();
  });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') ccClose(); });

  /* --- Stato Wi-Fi (online/offline reale del browser) --- */
  const wifiSt = $('cc-wifi-st'), wifiIc = $('cc-wifi-ic'), mbWifi = $('mb-wifi'), ccWifi = $('cc-wifi');
  function wifiSync() {
    const on = navigator.onLine;
    if (ccWifi) ccWifi.classList.toggle('off', !on);
    if (wifiSt) wifiSt.textContent = on ? 'Connesso' : 'Non in linea';
    if (wifiIc) wifiIc.style.opacity = on ? '1' : '.4';
    if (mbWifi) mbWifi.style.opacity = on ? '1' : '.4';
  }
  window.addEventListener('online', wifiSync);
  window.addEventListener('offline', wifiSync);
  wifiSync();
  if (ccWifi) ccWifi.addEventListener('click', () => ccWifi.classList.toggle('off'));

  /* --- Condividi / Copia link della presentazione --- */
  const PAGE = location.href.split('#')[0];
  const ccShare = $('cc-share'), ccCopy = $('cc-copy'), ccCopySt = $('cc-copy-st');
  if (ccShare) ccShare.addEventListener('click', async () => {
    if (navigator.share) { try { await navigator.share({ title: document.title, url: PAGE }); } catch (e) {} }
    else if (ccCopy) ccCopy.click();
  });
  if (ccCopy) ccCopy.addEventListener('click', async () => {
    try { await navigator.clipboard.writeText(PAGE); if (ccCopySt) { ccCopySt.textContent = 'Copiato!'; setTimeout(() => ccCopySt.textContent = 'Copia link', 1600); } } catch (e) {}
  });

  /* --- Schermo intero --- */
  const ccFull = $('cc-full');
  function goFull() { const el = document.documentElement; if (el.requestFullscreen) el.requestFullscreen().catch(() => {}); }
  if (ccFull) ccFull.addEventListener('click', goFull);

  /* --- Modalità presentazione: chiude tutto, avvia il timer, va a schermo
     intero e apre la prima scheda --- */
  const ccPres = $('cc-pres');
  if (ccPres) ccPres.addEventListener('click', () => {
    ccClose();
    if (typeof closeAll === 'function') closeAll();
    if (typeof avviaTimerPresentazione === 'function') avviaTimerPresentazione();
    goFull();
    setTimeout(() => { if (typeof openWin === 'function') openWin('w-pres'); }, 520);
  });

  /* --- Riavvio (torna alla sequenza di boot) --- */
  const ccBoot = $('cc-boot');
  if (ccBoot) ccBoot.addEventListener('click', () => { location.href = 'hub.php?boot=1'; });

  /* --- Cursori luminosità / volume --- */
  function rangeFill(input) {
    const min = +input.min || 0, max = +input.max || 100;
    const val = (input.value - min) / (max - min) * 100;
    input.style.setProperty('--val', val + '%');
  }
  const briInput = $('cc-bri');
  if (briInput) {
    const apply = () => { rangeFill(briInput); if (dim) dim.style.setProperty('--bri-dim', (1 - briInput.value / 100) * 0.55); localStorage.setItem('cc-bri', briInput.value); };
    const saved = localStorage.getItem('cc-bri'); if (saved !== null) briInput.value = saved;
    briInput.addEventListener('input', apply);
    apply();
  }
  const volInput = $('cc-vol');
  if (volInput) {
    const saved = localStorage.getItem('cc-vol'); if (saved !== null) volInput.value = saved;
    const apply = () => { rangeFill(volInput); localStorage.setItem('cc-vol', volInput.value); };
    volInput.addEventListener('input', () => { apply(); if (typeof sndTick === 'function') sndTick(); });
    volInput.addEventListener('change', () => { apply(); if (typeof sndTickForce === 'function') sndTickForce(); });
    apply();
  }

  /* --- Indicatore batteria --- */
  const bpct = $('bpct');
  if (bpct && navigator.getBattery) {
    navigator.getBattery().then(b => {
      const upd = () => { bpct.textContent = Math.round(b.level * 100) + '%'; };
      upd(); b.addEventListener('levelchange', upd);
    }).catch(() => {});
  }

  /* --- Spegnimento / riavvio con conferma --- */
  function pwrAsk(kind) {
    ccClose();
    let dlg = $('pwrdlg');
    if (!dlg) {
      dlg = document.createElement('div');
      dlg.id = 'pwrdlg';
      dlg.innerHTML =
        '<div class="pwr-card">' +
          '<svg viewBox="0 0 56 56"><use href="#i-cap"/></svg>' +
          '<b id="pwr-q"></b>' +
          '<div class="pwr-acts">' +
            '<button class="pwr-no">Annulla</button>' +
            '<button class="pwr-yes"></button>' +
          '</div>' +
        '</div>';
      document.body.appendChild(dlg);
    }
    const yes = dlg.querySelector('.pwr-yes'), no = dlg.querySelector('.pwr-no');
    $('pwr-q').textContent = kind === 'reboot' ? 'Vuoi riavviare la presentazione?' : 'Vuoi terminare la presentazione?';
    yes.textContent = kind === 'reboot' ? 'Riavvia' : 'Spegni';
    dlg.classList.add('on');
    const close = () => dlg.classList.remove('on');
    no.onclick = close;
    yes.onclick = () => { close(); kind === 'reboot' ? riavvia() : spegni(); };
  }

  /* Schermata nera di commiato prima del logout. */
  function spegni() {
    if (typeof sndExit === 'function') sndExit();
    let shut = $('shut');
    if (!shut) {
      shut = document.createElement('div');
      shut.id = 'shut';
      shut.innerHTML = '<b>Grazie</b><span>La presentazione è terminata</span><i class="shut-spin"></i>';
      document.body.appendChild(shut);
    }
    requestAnimationFrame(() => shut.classList.add('on'));
    setTimeout(() => { location.href = 'logout.php'; }, 6600);
  }
  function riavvia() { if (typeof sndExit === 'function') sndExit(); setTimeout(() => location.href = 'hub.php?boot=1', 360); }

  /* Voci di menu "Riavvia" ed "Esci". */
  const exit = document.querySelector('.mitem.exit'), reboot = document.querySelector('.mitem.reboot');
  if (exit) exit.addEventListener('click', e => { e.preventDefault(); pwrAsk('shut'); });
  if (reboot) reboot.addEventListener('click', e => { e.preventDefault(); pwrAsk('reboot'); });
})();
