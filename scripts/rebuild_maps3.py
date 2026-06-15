# -*- coding: utf-8 -*-
from pathlib import Path
root = Path('/data/maturita-work/maturita-2k26-main')

DOT = '\u00b7'      # ·
DASH = '\u2014'     # —
UP = '\u2191'
UPR = '\u2197'
FLAG = '\u25c9'

# ===========================================================================
# 1) hub.php  —  Apple Maps: panoramica completa (meet) + targhette + nav
# ===========================================================================
ph = (root/'hub.php').read_text()
ps = ph.index('<section class="win a-blue maps-window" id="w-fine"')
sp = ph.index('<div id="spot"', ps)
pe = ph.rindex('</section>', ps, sp) + len('</section>')

def pin(i, x, y, num, label, side, lw):
    if side == 'r':
        lg = 'translate(26 -31)'
        rect = '<rect x="0" y="-13" width="%d" height="26" rx="8"/>' % lw
        txt = '<text x="12" y="5" text-anchor="start">%s</text>' % label
    else:
        lg = 'translate(-26 -31)'
        rect = '<rect x="%d" y="-13" width="%d" height="26" rx="8"/>' % (-lw, lw)
        txt = '<text x="-12" y="5" text-anchor="end">%s</text>' % label
    label_group = '<g class="mw-label"><g transform="%s">%s%s</g></g>' % (lg, rect, txt)
    head = '<g class="mw-pin" data-maps-step="%d" transform="translate(%d %d)">' % (i, x, y)
    body = ('<g class="mw-pin-rot">'
        '<ellipse class="pin-shadow" cx="0" cy="2" rx="9" ry="3.5"/>'
        '<path class="pin-body" d="M0 0 C-7 -13 -16 -19 -16 -31 A16 16 0 1 1 16 -31 C16 -19 7 -13 0 0 Z"/>'
        '<circle class="pin-dot" cx="0" cy="-31" r="10"/>'
        '<text class="pin-num" x="0" y="-27" text-anchor="middle">%d</text>'
        '</g>') % num
    return head + body + label_group + '</g>'

p1 = pin(0, 185, 605, 1, 'FSL ' + DOT + ' PCTO', 'r', 116)
p2 = pin(1, 470, 470, 2, 'Diploma', 'r', 86)
p3 = pin(2, 770, 300, 3, 'Universit\u00e0 ' + DASH + ' Brescia', 'r', 184)
p4 = pin(3, 1015, 120, 4, 'Estero ' + DASH + ' America', 'l', 158)

section = '''<section class="win a-blue maps-window" id="w-fine" style="left:9%;top:6.5%;width:1020px">
  <div class="titlebar maps-titlebar"><span class="wt">Dove voglio andare \u2014 Mappe</span></div>
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
        <div class="mw-endpoint"><span class="mw-pt start"></span><div><small>Da</small><b>Oggi \u00b7 Maturit\u00e0</b></div></div>
        <div class="mw-endpoint"><span class="mw-pt end"></span><div><small>A</small><b>Estero \u00b7 America</b></div></div>
      </div>

      <div class="mw-steps-head">Passaggi</div>
      <div class="mw-steps" data-maps-stops aria-label="Tappe del percorso"></div>
    </aside>

    <main class="mw-map-wrap" aria-label="Mappa del percorso">
      <svg class="maps-map mw-map" viewBox="0 0 1200 760" preserveAspectRatio="xMidYMid meet" role="img" aria-label="Percorso: FSL, Diploma, Universit\u00e0 a Brescia, estero">
        <defs>
          <linearGradient id="mw-water" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#16334e"/><stop offset="1" stop-color="#102739"/></linearGradient>
          <filter id="mw-pin-sh" x="-60%" y="-60%" width="220%" height="220%"><feDropShadow dx="0" dy="3" stdDeviation="3" flood-color="#000" flood-opacity=".35"/></filter>
        </defs>

        <rect class="mw-bg" x="-600" y="-600" width="2400" height="1960" fill="#23262c"/>
        <g id="maps-cam">
          <path d="M-600 150 C220 120 420 175 600 150 C820 120 1010 60 1800 90 L1800 -600 L-600 -600 Z" fill="url(#mw-water)"/>
          <path d="M120 760 C140 600 250 560 300 470 C360 360 300 300 360 210" fill="none" stroke="#2b4a32" stroke-width="150" stroke-linecap="round" opacity=".5"/>
          <path d="M860 760 C900 640 1000 600 1100 560 C1200 520 1260 470 1320 420" fill="none" stroke="#2b4a32" stroke-width="130" stroke-linecap="round" opacity=".45"/>
          <g stroke-linecap="round" fill="none">
            <path d="M-200 540 C200 480 360 440 520 360 C720 262 880 230 1400 150" stroke="#3a3e45" stroke-width="9"/>
            <path d="M40 760 C260 600 380 560 540 460 C720 350 900 300 1400 230" stroke="#33373d" stroke-width="6"/>
            <path d="M300 760 C340 600 460 470 580 400 C700 330 760 250 800 120" stroke="#33373d" stroke-width="5"/>
          </g>

          <path id="maps-route-base" d="M185 605 C320 540 360 530 470 470 C600 400 660 370 770 300 C880 230 960 170 1015 120" fill="none" stroke="#0a4da8" stroke-width="16" stroke-linecap="round" stroke-linejoin="round" opacity=".5"/>
          <path id="maps-route" d="M185 605 C320 540 360 530 470 470 C600 400 660 370 770 300 C880 230 960 170 1015 120" fill="none" stroke="#0a84ff" stroke-width="11" stroke-linecap="round" stroke-linejoin="round"/>
          <path id="maps-route-progress" pathLength="1" d="M185 605 C320 540 360 530 470 470 C600 400 660 370 770 300 C880 230 960 170 1015 120" fill="none" stroke="#64b5ff" stroke-width="11" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="1" stroke-dashoffset="1"/>

          PIN1 PIN2 PIN3 PIN4

          <g id="maps-puck" transform="translate(185 605)"><g class="puck-rot"><circle class="puck-halo" r="22"/><circle class="puck-dot" r="9"/><path class="puck-arrow" d="M0 -17 L10 7 L0 1 L-10 7 Z"/></g></g>
        </g>
      </svg>

      <div class="mw-banner" aria-live="polite">
        <div class="mw-banner-icon" data-maps-icon>\u2197</div>
        <div class="mw-banner-text"><span data-maps-kicker>Panoramica</span><b data-maps-title>Il mio percorso</b><p data-maps-copy>Tocca una tappa o premi Vai per iniziare.</p></div>
      </div>

      <div class="mw-zoom" aria-label="Zoom mappa">
        <button data-maps-zoom="in" type="button" aria-label="Avvicina">+</button>
        <span></span>
        <button data-maps-zoom="out" type="button" aria-label="Allontana">\u2212</button>
      </div>

      <div class="mw-sheet">
        <div class="mw-sheet-text"><b data-maps-eta>Il mio percorso</b><small data-maps-sub>4 tappe</small></div>
        <div class="mw-dots" data-maps-dots aria-label="Avanzamento"></div>
        <button class="mw-go go-start" data-maps-go type="button">Vai</button>
      </div>
    </main>

  </div>
</section>'''
section = section.replace('PIN1', p1).replace('PIN2', p2).replace('PIN3', p3).replace('PIN4', p4)
ph = ph[:ps] + section + ph[pe:]
(root/'hub.php').write_text(ph)

# ===========================================================================
# 2) hub.js  —  overview (panoramica) + navigazione orientata
# ===========================================================================
js = (root/'hub.js').read_text()
js_s = js.index('/* Maps Navigator')
js_e = js.index('})();', js_s) + len('})();')

nav = r'''/* Maps Navigator — percorso di vita, stile Apple Maps (panoramica + navigazione orientata) */
(function(){
  const app=document.querySelector('[data-maps-navigator]');
  if(!app) return;
  const cam=app.querySelector('#maps-cam');
  const puck=app.querySelector('#maps-puck');
  const puckRot=app.querySelector('#maps-puck .puck-rot');
  const progress=app.querySelector('#maps-route-progress');
  const stopsBox=app.querySelector('[data-maps-stops]');
  const dots=app.querySelector('[data-maps-dots]');
  const title=app.querySelector('[data-maps-title]');
  const kicker=app.querySelector('[data-maps-kicker]');
  const copy=app.querySelector('[data-maps-copy]');
  const icon=app.querySelector('[data-maps-icon]');
  const eta=app.querySelector('[data-maps-eta]');
  const sub=app.querySelector('[data-maps-sub]');
  const goBtn=app.querySelector('[data-maps-go]');
  if(!cam||!stopsBox||!dots) return;

  const stops=[
    {x:185,y:605,p:0,   kicker:'Partenza', title:'FSL · PCTO',           eta:'FSL · PCTO',           sub:'2025 · Esperienza sul campo', copy:'Il viaggio parte dal PCTO: la prima esperienza concreta di lavoro, dove la scuola diventa metodo e responsabilità.'},
    {x:470,y:470,p:.34, kicker:'Tappa 2',  title:'Diploma',              eta:'Diploma',              sub:'2026 · Maturità',            copy:'Il diploma chiude il percorso scolastico: un traguardo e allo stesso tempo il punto di partenza per le scelte successive.'},
    {x:770,y:300,p:.67, title:'Università a Brescia',  kicker:'Tappa 3', eta:'Università a Brescia', sub:'Ingegneria Informatica',     copy:'La direzione è Ingegneria Informatica a Brescia: approfondire software, sistemi e progettazione per crescere con basi più solide.'},
    {x:1015,y:120,p:1,  kicker:'Arrivo',   title:'Carriera all’estero',  eta:'Estero · America',     sub:'Il sogno: contesto internazionale', copy:'La meta più ambiziosa: spostarmi e cercare opportunità nel software all’estero, con il sogno di arrivare in America.'}
  ];

  let current=0, navMode=false, userZoom=1;
  const last=stops.length-1;

  stopsBox.innerHTML=stops.map((s,i)=>`<button class="mw-step${i===0?' active':''}" data-maps-step="${i}" type="button"><span class="mw-step-num">${i+1}</span><span class="mw-step-txt"><b>${s.title}</b><small>${s.sub}</small></span></button>`).join('');
  dots.innerHTML=stops.map((_,i)=>`<button class="mw-dot${i===0?' on':''}" data-maps-step="${i}" type="button" aria-label="Tappa ${i+1}"></button>`).join('');

  function headingDeg(i){
    // angolo della direzione di marcia: dal punto corrente al successivo (ultimo: segmento precedente)
    let a=stops[i], b;
    if(i<last){ b=stops[i+1]; } else { b=stops[i]; a=stops[i-1]; }
    const ang=Math.atan2(b.y-a.y, b.x-a.x)*180/Math.PI;
    return -90-ang; // ruota la scena così che la direzione punti in alto
  }

  function render(){
    const s=stops[current];
    app.classList.toggle('nav', navMode);

    if(navMode){
      const rot=headingDeg(current), S=1.95, cx=600, cy=545;
      cam.style.transform=`translate(${cx}px, ${cy}px) rotate(${rot}deg) scale(${S}) translate(${-s.x}px, ${-s.y}px)`;
      app.querySelectorAll('.mw-pin-rot').forEach(g=>g.setAttribute('transform',`rotate(${-rot})`));
      if(puckRot) puckRot.setAttribute('transform',`rotate(${-rot})`);
    } else {
      cam.style.transform=`translate(600px,380px) scale(${userZoom}) translate(-600px,-380px)`;
      app.querySelectorAll('.mw-pin-rot').forEach(g=>g.setAttribute('transform',''));
      if(puckRot) puckRot.setAttribute('transform','');
    }

    puck.setAttribute('transform',`translate(${s.x} ${s.y})`);
    progress.style.strokeDashoffset=(1-s.p).toFixed(4);

    // testi banner
    if(navMode){
      if(current<last){ kicker.textContent='In direzione di'; title.textContent=stops[current+1].title; copy.textContent='Prosegui verso la prossima tappa del percorso.'; icon.textContent='↑'; }
      else { kicker.textContent='Arrivo'; title.textContent=s.title; copy.textContent='Sei arrivato a destinazione.'; icon.textContent='◉'; }
    } else {
      kicker.textContent=s.kicker; title.textContent=s.title; copy.textContent=s.copy; icon.textContent='↗';
    }
    eta.textContent=s.eta; sub.textContent=s.sub;

    // pulsante: Vai (verde) / Avanti (blu) / Fine (rosso)
    goBtn.classList.remove('go-start','go-next','go-end');
    if(!navMode){ goBtn.textContent='Vai'; goBtn.classList.add('go-start'); }
    else if(current<last){ goBtn.textContent='Avanti'; goBtn.classList.add('go-next'); }
    else { goBtn.textContent='Fine'; goBtn.classList.add('go-end'); }

    app.querySelectorAll('.mw-step').forEach((el,i)=>{el.classList.toggle('active',i===current);el.classList.toggle('done',i<current);});
    app.querySelectorAll('.mw-dot').forEach((el,i)=>{el.classList.toggle('on',i===current);el.classList.toggle('done',i<current);});
    app.querySelectorAll('.mw-pin').forEach((el,i)=>{el.classList.toggle('on',i===current);el.classList.toggle('done',i<current);});
  }

  function go(i){ current=Math.max(0,Math.min(last,i)); render(); try{sndOpen&&sndOpen();}catch(e){} }

  goBtn.addEventListener('click',()=>{
    if(!navMode){ navMode=true; current=0; }
    else if(current<last){ current++; }
    else { navMode=false; current=0; userZoom=1; }
    render();
  });

  app.addEventListener('click',e=>{
    if(e.target.closest('[data-maps-go]')) return;
    const st=e.target.closest('[data-maps-step]'); if(st){ go(+st.dataset.mapsStep); return; }
    const z=e.target.closest('[data-maps-zoom]'); if(z){ if(!navMode){ userZoom=Math.max(1,Math.min(1.6, userZoom+(z.dataset.mapsZoom==='in'?.14:-.14))); render(); } return; }
    const pin=e.target.closest('.mw-pin'); if(pin){ go(+pin.dataset.mapsStep); return; }
  });

  render();
})();'''
js = js[:js_s] + nav + js[js_e:]
(root/'hub.js').write_text(js)

# ===========================================================================
# 3) macos.css  —  riscrittura completa del blocco mappe (tutto blu, nav)
# ===========================================================================
css = (root/'macos.css').read_text()
idx = css.index('Maps \u2014 Apple Maps "Indicazioni"')
cs = css.rindex('/*', 0, idx)
css_new = '''/* ============================================================
   Maps \u2014 Apple Maps (dark) · panoramica + navigazione orientata
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
.mw-pt.start{ background:#8e8e93; } .mw-pt.end{ background:#0a84ff; }
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
.mw-step.active .mw-step-num,.mw-step.done .mw-step-num{ background:#0a84ff; }
.mw-step-txt b{ display:block; font-size:14px; font-weight:680; letter-spacing:-.01em; }
.mw-step-txt small{ display:block; font-size:12px; font-weight:560; color:rgba(245,245,247,.5); margin-top:1px; }

/* ---------- Map ---------- */
.mw-map-wrap{ position:relative; min-width:0; overflow:hidden; background:#23262c; }
.maps-map{ position:absolute; inset:0; width:100%; height:100%; display:block; }
.mw-map-wrap::after{ content:""; position:absolute; inset:0; pointer-events:none;
  background:linear-gradient(180deg, rgba(0,0,0,.16), transparent 20%, transparent 80%, rgba(0,0,0,.2)); }
#maps-cam{ transform-box:view-box; transform-origin:0 0;
  transition:transform 1.05s cubic-bezier(.32,.72,0,1); }
#maps-puck{ transition:transform 1.05s cubic-bezier(.32,.72,0,1); }
#maps-puck .puck-rot{ transition:transform 1.05s cubic-bezier(.32,.72,0,1); }
#maps-route-progress{ transition:stroke-dashoffset 1.05s cubic-bezier(.32,.72,0,1); }

/* tutti i pin BLU, niente colori da gioco */
.mw-pin{ cursor:pointer; }
.mw-pin-rot{ transition:transform 1.05s cubic-bezier(.32,.72,0,1), opacity .3s ease; }
.mw-pin .pin-body{ fill:#0a84ff; filter:url(#mw-pin-sh); transition:fill .25s ease; }
.mw-pin .pin-dot{ fill:#fff; }
.mw-pin .pin-num{ font-size:12px; font-weight:800; fill:#0a3d7a; }
.mw-pin .pin-shadow{ fill:#000; opacity:.18; }
.mw-pin:not(.on):not(.done) .pin-body{ fill:#3a93e6; }
.mw-pin.on .pin-body{ fill:#0a84ff; }
.mw-pin.on .mw-pin-rot{ transform:scale(1.16); }

/* targhette (labels) — visibili in panoramica, nascoste in navigazione */
.mw-label rect{ fill:rgba(28,30,36,.92); stroke:rgba(255,255,255,.14); stroke-width:1; }
.mw-label text{ fill:#fff; font-size:14px; font-weight:680; }
.mw-label{ transition:opacity .3s ease; }
.maps-app.nav .mw-label{ opacity:0; pointer-events:none; }

/* puck: punto in panoramica, freccia di marcia in navigazione */
.puck-halo{ fill:#0a84ff; opacity:.18; }
.puck-dot{ fill:#0a84ff; stroke:#fff; stroke-width:3; }
.puck-arrow{ fill:#0a84ff; stroke:#fff; stroke-width:2.5; stroke-linejoin:round; opacity:0; }
.maps-app.nav .puck-dot{ opacity:0; }
.maps-app.nav .puck-arrow{ opacity:1; }

/* Banner: neutro in panoramica, verde in navigazione (come Apple) */
.mw-banner{ position:absolute; left:16px; top:16px; width:300px; max-width:calc(100% - 86px); z-index:5;
  display:flex; gap:13px; padding:14px 16px; border-radius:20px; color:#fff;
  background:rgba(34,36,41,.86); backdrop-filter:blur(18px) saturate(1.7); -webkit-backdrop-filter:blur(18px) saturate(1.7);
  box-shadow:0 16px 40px rgba(0,0,0,.34), inset 0 0 0 1px rgba(255,255,255,.09);
  transition:background .4s ease, box-shadow .4s ease;
  animation:mwIn .5s cubic-bezier(.32,.72,0,1) both; }
.maps-app.nav .mw-banner{ background:linear-gradient(180deg,#31ba53,#159b3c);
  box-shadow:0 18px 44px rgba(8,82,30,.4), inset 0 1px 0 rgba(255,255,255,.32); }
.mw-banner-icon{ width:46px; height:46px; flex:none; display:flex; align-items:center; justify-content:center;
  font-size:34px; font-weight:800; line-height:1; text-shadow:0 2px 4px rgba(0,0,0,.2); }
.mw-banner-text span{ display:block; font-size:11px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; color:rgba(255,255,255,.74); }
.mw-banner-text b{ display:block; margin-top:2px; font-size:21px; font-weight:850; letter-spacing:-.03em; line-height:1.05; }
.mw-banner-text p{ margin:6px 0 0; font-size:12.5px; line-height:1.4; font-weight:520; color:rgba(255,255,255,.92); }
@keyframes mwIn{ from{ opacity:0; transform:translateX(-14px); } to{ opacity:1; transform:none; } }

/* Zoom */
.mw-zoom{ position:absolute; right:15px; top:16px; z-index:5; display:flex; flex-direction:column; width:38px;
  border-radius:12px; overflow:hidden; background:rgba(40,42,47,.82); backdrop-filter:blur(14px) saturate(1.6);
  -webkit-backdrop-filter:blur(14px) saturate(1.6); box-shadow:0 10px 28px rgba(0,0,0,.3), inset 0 0 0 1px rgba(255,255,255,.1);
  transition:opacity .3s ease; }
.maps-app.nav .mw-zoom{ opacity:.4; }
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
.mw-sheet-text b{ display:block; font-size:17px; font-weight:820; letter-spacing:-.025em; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.mw-sheet-text small{ display:block; margin-top:1px; font-size:12px; font-weight:600; color:rgba(245,245,247,.55); }
.mw-dots{ display:flex; gap:8px; margin-left:auto; }
.mw-dot{ width:10px; height:10px; border:0; border-radius:50%; padding:0; background:rgba(235,235,245,.28);
  transition:transform .25s var(--spring), background .2s ease; }
.mw-dot:hover{ transform:scale(1.2); }
.mw-dot.done,.mw-dot.on{ background:#0a84ff; }
.mw-dot.on{ transform:scale(1.3); }
.mw-go{ flex:none; border:0; border-radius:13px; color:#fff; padding:10px 20px;
  font-size:14px; font-weight:800; letter-spacing:-.01em; transition:transform .2s var(--spring), filter .16s ease; }
.mw-go:hover{ filter:brightness(1.08); } .mw-go:active{ transform:scale(.94); }
.mw-go.go-start{ background:#34c759; box-shadow:0 6px 18px rgba(52,199,89,.34); }
.mw-go.go-next{ background:#0a84ff; box-shadow:0 6px 18px rgba(10,132,255,.34); }
.mw-go.go-end{ background:#ff453a; box-shadow:0 6px 18px rgba(255,69,58,.34); }

@media(max-width:820px){
  .maps-window{ width:94vw!important; }
  .maps-app{ grid-template-columns:1fr; }
  .mw-side{ display:none; }
  .mw-banner{ width:auto; left:12px; right:64px; }
}
'''
css = css[:cs] + css_new
(root/'macos.css').write_text(css)

print('Rebuild v3 OK')
print('hub.php', len(ph), '| hub.js', len(js), '| macos.css', len(css))
