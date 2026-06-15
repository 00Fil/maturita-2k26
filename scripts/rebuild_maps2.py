from pathlib import Path
root = Path('/data/maturita-work/maturita-2k26-main')

# ---------------------------------------------------------------------------
# 1) hub.php  —  clean Apple Maps "Indicazioni" window
# ---------------------------------------------------------------------------
ph = (root/'hub.php').read_text()
ps = ph.index('<section class="win a-blue maps-window" id="w-fine"')
sp = ph.index('<div id="spot"', ps)
pe = ph.rindex('</section>', ps, sp) + len('</section>')

section = '''<section class="win a-blue maps-window" id="w-fine" style="left:9%;top:6.5%;width:1020px">
  <div class="titlebar maps-titlebar"><span class="wt">Dove voglio andare — Mappe</span></div>
  <div class="maps-app" data-maps-navigator>

    <aside class="mw-side" aria-label="Indicazioni del percorso">
      <div class="mw-side-head">
        <button class="mw-back" type="button" aria-label="Indietro"><svg viewBox="0 0 24 24"><path d="m15 5-7 7 7 7"/></svg></button>
        <h2>Indicazioni</h2>
      </div>

      <div class="mw-modes" role="tablist" aria-label="Tipo di percorso">
        <button class="active" type="button" aria-label="In auto"><svg viewBox="0 0 24 24"><path d="M5 11l1.5-4.5A2 2 0 0 1 8.4 5h7.2a2 2 0 0 1 1.9 1.5L19 11M5 11h14v5a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-1H8v1a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1z"/><circle cx="7.5" cy="14" r="1"/><circle cx="16.5" cy="14" r="1"/></svg></button>
        <button type="button" aria-label="A piedi"><svg viewBox="0 0 24 24"><circle cx="12" cy="4.5" r="1.8"/><path d="M11 9l-2 4 2 1 1 5M13 9l1 3 3 1M11 9h2l1.5 1"/></svg></button>
        <button type="button" aria-label="Mezzi pubblici"><svg viewBox="0 0 24 24"><rect x="6" y="4" width="12" height="13" rx="3"/><path d="M6 12h12M9 20l-1.5 2M15 20l1.5 2"/><circle cx="9" cy="15" r="1"/><circle cx="15" cy="15" r="1"/></svg></button>
      </div>

      <div class="mw-route">
        <div class="mw-route-line"></div>
        <div class="mw-endpoint"><span class="mw-pt start"></span><div><small>Da</small><b>Oggi · Maturità</b></div></div>
        <div class="mw-endpoint"><span class="mw-pt end"></span><div><small>A</small><b>Estero · America</b></div></div>
      </div>

      <div class="mw-steps-head">Passaggi</div>
      <div class="mw-steps" data-maps-stops aria-label="Tappe del percorso"></div>
    </aside>

    <main class="mw-map-wrap" aria-label="Mappa del percorso">
      <svg class="maps-map mw-map" viewBox="0 0 1200 760" preserveAspectRatio="xMidYMid slice" role="img" aria-label="Percorso: FSL, Diploma, Università a Brescia, estero">
        <defs>
          <linearGradient id="mw-water" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#17324e"/><stop offset="1" stop-color="#0d2236"/></linearGradient>
          <filter id="mw-pin-sh" x="-50%" y="-50%" width="200%" height="200%"><feDropShadow dx="0" dy="3" stdDeviation="3" flood-color="#000" flood-opacity=".35"/></filter>
        </defs>

        <g id="maps-cam">
          <rect x="-200" y="-200" width="1600" height="1160" fill="#23262c"/>
          <path d="M0 150 C220 120 420 175 600 150 C820 120 1010 60 1200 90 L1200 -60 L0 -60 Z" fill="url(#mw-water)"/>
          <path d="M150 760 C150 600 250 560 300 470 C360 360 300 300 360 210" fill="none" stroke="#2c4d33" stroke-width="150" stroke-linecap="round" opacity=".5"/>
          <path d="M820 760 C880 640 980 600 1080 560 C1180 520 1220 470 1240 420" fill="none" stroke="#2c4d33" stroke-width="130" stroke-linecap="round" opacity=".45"/>
          <g stroke-linecap="round" fill="none">
            <path d="M-40 520 C220 470 360 430 520 360 C700 282 860 250 1240 170" stroke="#3a3e45" stroke-width="9"/>
            <path d="M40 700 C260 600 380 560 520 470 C700 360 880 320 1240 250" stroke="#33373d" stroke-width="6"/>
            <path d="M260 760 C300 600 420 470 540 400 C660 330 720 250 760 120" stroke="#33373d" stroke-width="6"/>
            <path d="M700 760 C760 600 820 470 900 380 C980 290 1040 220 1080 120" stroke="#2f333a" stroke-width="5"/>
          </g>
          <path id="maps-route-base" d="M180 628 C300 560 360 548 470 486 C600 414 660 372 770 300 C880 228 980 150 1040 96" fill="none" stroke="#0a4da8" stroke-width="16" stroke-linecap="round" stroke-linejoin="round" opacity=".55"/>
          <path id="maps-route" d="M180 628 C300 560 360 548 470 486 C600 414 660 372 770 300 C880 228 980 150 1040 96" fill="none" stroke="#0a84ff" stroke-width="11" stroke-linecap="round" stroke-linejoin="round"/>
          <path id="maps-route-progress" pathLength="1" d="M180 628 C300 560 360 548 470 486 C600 414 660 372 770 300 C880 228 980 150 1040 96" fill="none" stroke="#34c759" stroke-width="11" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="1" stroke-dashoffset="1"/>

          <g class="mw-pin" data-maps-step="0" transform="translate(180 628)"><path class="pin-body" d="M0 6 C-13 6 -22 -3 -22 -15 C-22 -27 -12 -36 0 -36 C12 -36 22 -27 22 -15 C22 -3 13 6 0 6 Z" fill="#34c759" filter="url(#mw-pin-sh)"/><circle cx="0" cy="-15" r="9" fill="#fff"/><text x="0" y="-11" text-anchor="middle" class="pin-num">1</text></g>
          <g class="mw-pin" data-maps-step="1" transform="translate(470 486)"><path class="pin-body" d="M0 6 C-13 6 -22 -3 -22 -15 C-22 -27 -12 -36 0 -36 C12 -36 22 -27 22 -15 C22 -3 13 6 0 6 Z" fill="#0a84ff" filter="url(#mw-pin-sh)"/><circle cx="0" cy="-15" r="9" fill="#fff"/><text x="0" y="-11" text-anchor="middle" class="pin-num">2</text></g>
          <g class="mw-pin" data-maps-step="2" transform="translate(770 300)"><path class="pin-body" d="M0 6 C-13 6 -22 -3 -22 -15 C-22 -27 -12 -36 0 -36 C12 -36 22 -27 22 -15 C22 -3 13 6 0 6 Z" fill="#5856d6" filter="url(#mw-pin-sh)"/><circle cx="0" cy="-15" r="9" fill="#fff"/><text x="0" y="-11" text-anchor="middle" class="pin-num">3</text></g>
          <g class="mw-pin" data-maps-step="3" transform="translate(1040 96)"><path class="pin-body" d="M0 6 C-13 6 -22 -3 -22 -15 C-22 -27 -12 -36 0 -36 C12 -36 22 -27 22 -15 C22 -3 13 6 0 6 Z" fill="#ff453a" filter="url(#mw-pin-sh)"/><circle cx="0" cy="-15" r="9" fill="#fff"/><text x="0" y="-11" text-anchor="middle" class="pin-num">4</text></g>

          <g id="maps-puck" transform="translate(180 628)"><circle r="15" fill="#0a84ff" opacity=".18"/><circle r="8" fill="#0a84ff" stroke="#fff" stroke-width="3"/></g>
        </g>
      </svg>

      <div class="mw-banner" aria-live="polite">
        <div class="mw-banner-icon" data-maps-icon>→</div>
        <div class="mw-banner-text"><span data-maps-kicker>Partenza</span><b data-maps-title>FSL · PCTO</b><p data-maps-copy>Il primo punto del viaggio.</p></div>
      </div>

      <div class="mw-zoom" aria-label="Zoom mappa">
        <button data-maps-zoom="in" type="button" aria-label="Avvicina">+</button>
        <span></span>
        <button data-maps-zoom="out" type="button" aria-label="Allontana">−</button>
      </div>

      <div class="mw-sheet">
        <div class="mw-sheet-text"><b data-maps-eta>Inizio percorso</b><small data-maps-sub>FSL / PCTO</small></div>
        <div class="mw-dots" data-maps-dots aria-label="Avanzamento"></div>
        <button class="mw-go" data-maps-next type="button">Avanti</button>
      </div>
    </main>

  </div>
</section>'''
ph = ph[:ps] + section + ph[pe:]
(root/'hub.php').write_text(ph)

# ---------------------------------------------------------------------------
# 2) hub.js  —  clean navigator logic (static map, animated route + puck)
# ---------------------------------------------------------------------------
js = (root/'hub.js').read_text()
js_s = js.index('/* Maps Navigator')
js_e = js.index('})();', js_s) + len('})();')

nav = '''/* Maps Navigator — percorso di vita animato, stile Apple Maps */
(function(){
  const app=document.querySelector('[data-maps-navigator]');
  if(!app) return;
  const cam=app.querySelector('#maps-cam');
  const puck=app.querySelector('#maps-puck');
  const progress=app.querySelector('#maps-route-progress');
  const stopsBox=app.querySelector('[data-maps-stops]');
  const dots=app.querySelector('[data-maps-dots]');
  const title=app.querySelector('[data-maps-title]');
  const kicker=app.querySelector('[data-maps-kicker]');
  const copy=app.querySelector('[data-maps-copy]');
  const icon=app.querySelector('[data-maps-icon]');
  const eta=app.querySelector('[data-maps-eta]');
  const sub=app.querySelector('[data-maps-sub]');
  if(!cam||!stopsBox||!dots) return;

  const stops=[
    {x:180,y:628,p:0,   icon:'→', kicker:'Partenza',   title:'FSL · PCTO',            eta:'Inizio percorso',      sub:'esperienza concreta', tag:'FSL',     copy:'Il viaggio parte dal PCTO: la prima esperienza concreta di lavoro, dove la scuola diventa metodo e responsabilità.'},
    {x:470,y:486,p:.34, icon:'↱', kicker:'Tappa 2',     title:'Diploma',               eta:'Maturità conclusa',    sub:'basi solide',         tag:'Diploma', copy:'Il diploma chiude il percorso scolastico: un traguardo e allo stesso tempo il punto di partenza per le scelte successive.'},
    {x:770,y:300,p:.67, icon:'↑', kicker:'Tappa 3',     title:'Università a Brescia',  eta:'Ingegneria Informatica', sub:'studio del software', tag:'Brescia', copy:'La direzione è Ingegneria Informatica a Brescia: approfondire software, sistemi e progettazione per crescere con basi più forti.'},
    {x:1040,y:96,p:1,   icon:'⚑', kicker:'Arrivo',      title:'Carriera all’estero',   eta:'Sogno America',        sub:'contesto internazionale', tag:'Estero', copy:'La meta più ambiziosa: spostarmi e cercare opportunità nel software all’estero, con il sogno di arrivare in America.'}
  ];

  let current=0, zoom=1;
  stopsBox.innerHTML=stops.map((s,i)=>`<button class="mw-step${i===0?' active':''}" data-maps-step="${i}" type="button"><span class="mw-step-num">${i+1}</span><span class="mw-step-txt"><b>${s.title}</b><small>${s.sub}</small></span></button>`).join('');
  dots.innerHTML=stops.map((_,i)=>`<button class="mw-dot${i===0?' on':''}" data-maps-step="${i}" type="button" aria-label="Tappa ${i+1}"></button>`).join('');

  function render(){
    const s=stops[current];
    cam.style.transform=`scale(${zoom.toFixed(3)})`;
    puck.setAttribute('transform',`translate(${s.x} ${s.y})`);
    progress.style.strokeDashoffset=(1-s.p).toFixed(4);
    title.textContent=s.title; kicker.textContent=s.kicker; copy.textContent=s.copy;
    icon.textContent=s.icon; eta.textContent=s.eta; sub.textContent=s.sub;
    app.querySelectorAll('.mw-step').forEach((el,i)=>{el.classList.toggle('active',i===current);el.classList.toggle('done',i<current);});
    app.querySelectorAll('.mw-dot').forEach((el,i)=>{el.classList.toggle('on',i===current);el.classList.toggle('done',i<current);});
    app.querySelectorAll('.mw-pin').forEach((el,i)=>{el.classList.toggle('on',i===current);el.classList.toggle('done',i<current);});
  }
  function go(i){ current=(i+stops.length)%stops.length; render(); try{sndOpen&&sndOpen();}catch(e){} }

  app.addEventListener('click',e=>{
    const st=e.target.closest('[data-maps-step]'); if(st){ go(+st.dataset.mapsStep); return; }
    if(e.target.closest('[data-maps-next]')){ go(current+1); return; }
    const z=e.target.closest('[data-maps-zoom]'); if(z){ zoom=Math.max(1,Math.min(1.5, zoom+(z.dataset.mapsZoom==='in'?.12:-.12))); render(); return; }
    if(e.target.closest('.mw-pin')){ const g=e.target.closest('.mw-pin'); go(+g.dataset.mapsStep); return; }
  });
  render();
})();'''
js = js[:js_s] + nav + js[js_e:]
(root/'hub.js').write_text(js)

# ---------------------------------------------------------------------------
# 3) macos.css  —  replace whole maps block with a clean, minimal one
# ---------------------------------------------------------------------------
css = (root/'macos.css').read_text()
cs = css.index('/* Maps Navigator rebuild')
css_new_block = '''/* ============================================================
   Maps — Apple Maps "Indicazioni" (dark, minimale)
   ============================================================ */
.maps-window{ background:rgba(20,21,24,.82)!important; border-radius:24px!important;
  box-shadow:var(--shadow-win), inset 0 0 0 1px rgba(255,255,255,.08)!important; }
.maps-titlebar{ height:44px; background:rgba(30,31,35,.72); box-shadow:0 1px 0 rgba(255,255,255,.06); }
.maps-titlebar .wt{ color:rgba(245,245,247,.72); }

.maps-app{
  flex:1 1 auto; min-height:0; height:min(648px, calc(100vh - 150px));
  display:grid; grid-template-columns:296px minmax(0,1fr);
  overflow:hidden; background:#16181c; color:#f5f5f7;
  font-family:"SF Pro Display",-apple-system,BlinkMacSystemFont,"Helvetica Neue",Arial,sans-serif;
}
.win.maxi .maps-app{ height:100%; }
.maps-app button{ font:inherit; cursor:pointer; }

/* ---------- Sidebar (Indicazioni) ---------- */
.mw-side{ display:flex; flex-direction:column; min-height:0; padding:14px 12px 12px;
  background:#1c1d21; border-right:1px solid rgba(255,255,255,.07); }
.mw-side-head{ display:flex; align-items:center; gap:8px; margin-bottom:14px; }
.mw-back{ width:30px; height:30px; flex:none; border:0; border-radius:50%;
  display:flex; align-items:center; justify-content:center;
  background:rgba(255,255,255,.08); color:#f5f5f7; transition:background .18s ease, transform .22s var(--spring); }
.mw-back:hover{ background:rgba(255,255,255,.16); } .mw-back:active{ transform:scale(.9); }
.mw-back svg{ width:17px; height:17px; fill:none; stroke:currentColor; stroke-width:2.2; stroke-linecap:round; stroke-linejoin:round; }
.mw-side-head h2{ font-size:21px; font-weight:800; letter-spacing:-.02em; }

.mw-modes{ display:grid; grid-template-columns:repeat(3,1fr); gap:4px; padding:3px;
  background:rgba(255,255,255,.07); border-radius:11px; margin-bottom:16px; }
.mw-modes button{ height:34px; border:0; border-radius:8px; background:transparent; color:rgba(245,245,247,.66);
  display:flex; align-items:center; justify-content:center; transition:background .18s ease, color .18s ease, transform .22s var(--spring); }
.mw-modes button svg{ width:21px; height:21px; fill:none; stroke:currentColor; stroke-width:1.7; stroke-linecap:round; stroke-linejoin:round; }
.mw-modes button:active{ transform:scale(.92); }
.mw-modes button.active{ background:rgba(255,255,255,.16); color:#fff; box-shadow:0 1px 4px rgba(0,0,0,.25); }

.mw-route{ position:relative; padding:4px 4px 4px 6px; margin-bottom:18px; }
.mw-route-line{ position:absolute; left:12px; top:20px; bottom:20px; width:2px; background:rgba(255,255,255,.14); border-radius:2px; }
.mw-endpoint{ display:flex; align-items:center; gap:12px; padding:7px 0; }
.mw-pt{ width:13px; height:13px; flex:none; border-radius:50%; position:relative; z-index:1; box-shadow:0 0 0 3px #1c1d21; }
.mw-pt.start{ background:#34c759; } .mw-pt.end{ background:#ff453a; }
.mw-endpoint small{ display:block; font-size:11px; font-weight:700; letter-spacing:.02em; color:rgba(245,245,247,.42); }
.mw-endpoint b{ display:block; font-size:14px; font-weight:680; letter-spacing:-.01em; }

.mw-steps-head{ font-size:12px; font-weight:800; letter-spacing:.05em; text-transform:uppercase;
  color:rgba(245,245,247,.42); padding:0 4px 8px; }
.mw-steps{ display:flex; flex-direction:column; gap:2px; overflow-y:auto; min-height:0; padding-right:2px; }
.mw-steps::-webkit-scrollbar{ width:6px; }
.mw-steps::-webkit-scrollbar-thumb{ background:rgba(255,255,255,.16); border-radius:3px; }
.mw-step{ display:flex; align-items:center; gap:12px; width:100%; text-align:left;
  border:0; border-radius:12px; background:transparent; color:#f5f5f7; padding:10px 10px;
  transition:background .18s ease, transform .2s var(--spring); }
.mw-step:hover{ background:rgba(255,255,255,.06); }
.mw-step:active{ transform:scale(.985); }
.mw-step.active{ background:rgba(10,132,255,.16); }
.mw-step-num{ width:24px; height:24px; flex:none; border-radius:50%; display:flex; align-items:center; justify-content:center;
  font-size:12px; font-weight:800; color:#fff; background:rgba(255,255,255,.18); transition:background .2s ease; }
.mw-step.active .mw-step-num{ background:#0a84ff; }
.mw-step.done .mw-step-num{ background:#34c759; }
.mw-step-txt b{ display:block; font-size:14px; font-weight:680; letter-spacing:-.01em; }
.mw-step-txt small{ display:block; font-size:12px; font-weight:560; color:rgba(245,245,247,.5); margin-top:1px; }

/* ---------- Map ---------- */
.mw-map-wrap{ position:relative; min-width:0; overflow:hidden; background:#23262c; }
.maps-map{ position:absolute; inset:0; width:100%; height:100%; display:block; }
.mw-map-wrap::after{ content:""; position:absolute; inset:0; pointer-events:none;
  background:linear-gradient(180deg, rgba(0,0,0,.16), transparent 22%, transparent 78%, rgba(0,0,0,.18)); }
#maps-cam{ transform-box:fill-box; transform-origin:50% 50%; transition:transform 1s cubic-bezier(.32,.72,0,1); }
#maps-puck{ transition:transform 1s cubic-bezier(.32,.72,0,1); }
#maps-route-progress{ transition:stroke-dashoffset 1s cubic-bezier(.32,.72,0,1); }
.mw-pin{ cursor:pointer; transition:transform .4s var(--spring); transform-box:fill-box; transform-origin:50% 100%; }
.mw-pin .pin-body{ transition:opacity .3s ease; }
.mw-pin:not(.on):not(.done) .pin-body{ opacity:.78; }
.mw-pin .pin-num{ font-size:11px; font-weight:800; fill:#1d1d1f; }
.mw-pin.on{ transform:scale(1.18); }

/* Turn banner (verde, come la navigazione Apple) */
.mw-banner{ position:absolute; left:16px; top:16px; width:300px; max-width:calc(100% - 86px); z-index:5;
  display:flex; gap:13px; padding:14px 16px; border-radius:20px; color:#fff;
  background:linear-gradient(180deg,#31ba53,#159b3c);
  box-shadow:0 18px 44px rgba(8,82,30,.36), inset 0 1px 0 rgba(255,255,255,.32);
  animation:mwIn .5s cubic-bezier(.32,.72,0,1) both; }
.mw-banner-icon{ width:46px; height:46px; flex:none; display:flex; align-items:center; justify-content:center;
  font-size:38px; font-weight:800; line-height:1; text-shadow:0 2px 4px rgba(0,0,0,.2); }
.mw-banner-text span{ display:block; font-size:11px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; color:rgba(255,255,255,.8); }
.mw-banner-text b{ display:block; margin-top:2px; font-size:21px; font-weight:850; letter-spacing:-.03em; line-height:1.05; }
.mw-banner-text p{ margin:6px 0 0; font-size:12.5px; line-height:1.4; font-weight:520; color:rgba(255,255,255,.94); }
@keyframes mwIn{ from{ opacity:0; transform:translateX(-14px); } to{ opacity:1; transform:none; } }

/* Zoom control */
.mw-zoom{ position:absolute; right:15px; top:16px; z-index:5; display:flex; flex-direction:column; width:38px;
  border-radius:12px; overflow:hidden; background:rgba(40,42,47,.82); backdrop-filter:blur(14px) saturate(1.6);
  -webkit-backdrop-filter:blur(14px) saturate(1.6); box-shadow:0 10px 28px rgba(0,0,0,.3), inset 0 0 0 1px rgba(255,255,255,.1); }
.mw-zoom button{ height:38px; border:0; background:transparent; color:#f5f5f7; font-size:21px; font-weight:400;
  display:flex; align-items:center; justify-content:center; transition:background .16s ease, transform .2s var(--spring); }
.mw-zoom button:hover{ background:rgba(255,255,255,.12); } .mw-zoom button:active{ transform:scale(.9); }
.mw-zoom span{ height:1px; background:rgba(255,255,255,.12); margin:0 8px; }

/* Bottom sheet */
.mw-sheet{ position:absolute; left:16px; right:16px; bottom:16px; z-index:5; display:flex; align-items:center; gap:14px;
  padding:12px 14px 12px 18px; border-radius:20px; background:rgba(34,36,41,.86);
  backdrop-filter:blur(18px) saturate(1.7); -webkit-backdrop-filter:blur(18px) saturate(1.7);
  box-shadow:0 18px 46px rgba(0,0,0,.34), inset 0 0 0 1px rgba(255,255,255,.09); color:#f5f5f7;
  animation:mwSheetIn .5s cubic-bezier(.32,.72,0,1) both; }
@keyframes mwSheetIn{ from{ opacity:0; transform:translateY(16px); } to{ opacity:1; transform:none; } }
.mw-sheet-text{ min-width:0; }
.mw-sheet-text b{ display:block; font-size:18px; font-weight:820; letter-spacing:-.025em; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.mw-sheet-text small{ display:block; margin-top:1px; font-size:12px; font-weight:600; color:rgba(245,245,247,.55); }
.mw-dots{ display:flex; gap:8px; margin-left:auto; }
.mw-dot{ width:10px; height:10px; border:0; border-radius:50%; padding:0; background:rgba(235,235,245,.28);
  transition:transform .25s var(--spring), background .2s ease; }
.mw-dot:hover{ transform:scale(1.2); }
.mw-dot.done{ background:#34c759; }
.mw-dot.on{ background:#0a84ff; transform:scale(1.3); }
.mw-go{ flex:none; border:0; border-radius:13px; background:#0a84ff; color:#fff; padding:10px 18px;
  font-size:14px; font-weight:780; letter-spacing:-.01em; box-shadow:0 6px 18px rgba(10,132,255,.34);
  transition:transform .2s var(--spring), filter .16s ease; }
.mw-go:hover{ filter:brightness(1.07); } .mw-go:active{ transform:scale(.94); }

/* Responsive */
@media(max-width:820px){
  .maps-window{ width:94vw!important; }
  .maps-app{ grid-template-columns:1fr; }
  .mw-side{ display:none; }
  .mw-banner{ width:auto; left:12px; right:64px; }
}
'''
css = css[:cs] + css_new_block
(root/'macos.css').write_text(css)

print('Rebuild v2 complete')
print('hub.php', len(ph), 'hub.js', len(js), 'macos.css', len(css))
