# -*- coding: utf-8 -*-
from pathlib import Path
root = Path('/data/maturita-work/maturita-2k26-main')

# ===========================================================================
# 1) hub.php
# ===========================================================================
ph = (root/'hub.php').read_text()
ps = ph.index('<section class="win a-blue maps-window" id="w-fine"')
sp = ph.index('<div id="spot"', ps)
pe = ph.rindex('</section>', ps, sp) + len('</section>')

def marker(i, x, y, num, label, side, lw):
    delay = '%.2f' % (0.07 * i)
    if side == 'r':
        lg = 'translate(24 -33)'
        rect = '<rect x="0" y="-15" width="%d" height="30" rx="10"/>' % lw
        dot = '<circle class="lbl-dot" cx="15" cy="0" r="3.2"/>'
        txt = '<text x="28" y="5" text-anchor="start">%s</text>' % label
    else:
        lg = 'translate(-24 -33)'
        rect = '<rect x="%d" y="-15" width="%d" height="30" rx="10"/>' % (-lw, lw)
        dot = '<circle class="lbl-dot" cx="-15" cy="0" r="3.2"/>'
        txt = '<text x="-28" y="5" text-anchor="end">%s</text>' % label
    label_g = '<g class="mw-label"><g transform="%s">%s%s%s</g></g>' % (lg, rect, dot, txt)
    head = '<g class="mw-pin" data-step-pin="%d" transform="translate(%d %d)">' % (i, x, y)
    body = ('<g class="mw-pin-drop" style="animation-delay:%ss">'
        '<g class="mw-pin-rot">'
        '<ellipse class="pin-ground" cx="0" cy="1" rx="7" ry="2.6"/>'
        '<circle class="pin-halo" cx="0" cy="-33" r="19"/>'
        '<path class="pin-body" d="M0 0 C-5 -16 -15 -20 -15 -33 A15 15 0 1 1 15 -33 C15 -20 5 -16 0 0 Z"/>'
        '<circle class="pin-core" cx="0" cy="-33" r="8"/>'
        '<text class="pin-num" x="0" y="-29" text-anchor="middle">%d</text>'
        '</g></g>') % (delay, num)
    return head + body + label_g + '</g>'

m1 = marker(0, 150, 650, 1, 'Cerebotani', 'r', 150)
m2 = marker(1, 360, 560, 2, 'FSL · PCTO', 'r', 132)
m3 = marker(2, 560, 430, 3, 'Diploma', 'r', 112)
m4 = marker(3, 790, 280, 4, 'Università · Brescia', 'r', 210)
m5 = marker(4, 1020, 130, 5, 'Estero · America', 'l', 188)
markers = m1 + m2 + m3 + m4 + m5

route = 'M150 650 C250 605 300 595 360 560 C440 525 470 500 560 430 C650 360 700 340 790 280 C880 220 960 175 1020 130'

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
        <div class="mw-endpoint"><span class="mw-pt start"></span><div><small>Da</small><b>Cerebotani \u00b7 Lonato</b></div></div>
        <div class="mw-endpoint"><span class="mw-pt end"></span><div><small>A</small><b>Estero \u00b7 America</b></div></div>
      </div>

      <div class="mw-routecard" aria-label="Durata del percorso">
        <div class="mw-rc-main">
          <b>5 anni</b>
          <span>Arrivo previsto \u00b7 2031 \u00b7 obiettivo America</span>
          <em>Percorso pi\u00f9 ambizioso</em>
        </div>
        <div class="mw-rc-info" aria-hidden="true">i</div>
      </div>
    </aside>

    <main class="mw-map-wrap" data-maps-stage aria-label="Mappa del percorso \u2014 tocca per proseguire">
      <svg class="maps-map mw-map" viewBox="0 0 1200 760" preserveAspectRatio="xMidYMid meet" role="img" aria-label="Percorso di vita">
        <defs>
          <linearGradient id="mw-water" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#16334e"/><stop offset="1" stop-color="#102739"/></linearGradient>
          <filter id="mw-pin-sh" x="-70%" y="-70%" width="240%" height="240%"><feDropShadow dx="0" dy="4" stdDeviation="3.5" flood-color="#000" flood-opacity=".4"/></filter>
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

          <path id="maps-route-base" d="ROUTE" fill="none" stroke="#0a4da8" stroke-width="16" stroke-linecap="round" stroke-linejoin="round" opacity=".5"/>
          <path id="maps-route" d="ROUTE" fill="none" stroke="#0a84ff" stroke-width="11" stroke-linecap="round" stroke-linejoin="round"/>
          <path id="maps-route-progress" pathLength="1" d="ROUTE" fill="none" stroke="#7cc4ff" stroke-width="11" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="1" stroke-dashoffset="1"/>

          MARKERS

          <g id="maps-puck" transform="translate(150 650)"><g class="puck-rot"><circle class="puck-halo" r="22"/><circle class="puck-dot" r="9"/><path class="puck-arrow" d="M0 -17 L10 7 L0 1 L-10 7 Z"/></g></g>
        </g>
      </svg>

      <div class="mw-banner" data-maps-banner aria-live="polite">
        <div class="mw-banner-icon" data-maps-icon>\u2197</div>
        <div class="mw-banner-text" data-maps-bannertext><span data-maps-kicker>Percorso</span><b data-maps-title>5 anni di crescita</b><p data-maps-copy>Tocca la mappa per avviare la navigazione.</p></div>
      </div>

      <div class="mw-zoom" aria-label="Zoom mappa">
        <button data-maps-zoom="in" type="button" aria-label="Avvicina">+</button>
        <span></span>
        <button data-maps-zoom="out" type="button" aria-label="Allontana">\u2212</button>
      </div>

      <div class="mw-hint" data-maps-hint>Tocca la mappa per iniziare</div>
    </main>

  </div>
</section>'''
section = section.replace('ROUTE', route).replace('MARKERS', markers)
ph = ph[:ps] + section + ph[pe:]
(root/'hub.php').write_text(ph)

# ===========================================================================
# 2) hub.js
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
  const stage=app.querySelector('[data-maps-stage]');
  const title=app.querySelector('[data-maps-title]');
  const kicker=app.querySelector('[data-maps-kicker]');
  const copy=app.querySelector('[data-maps-copy]');
  const icon=app.querySelector('[data-maps-icon]');
  const bannerText=app.querySelector('[data-maps-bannertext]');
  const hint=app.querySelector('[data-maps-hint]');
  const pins=Array.from(app.querySelectorAll('.mw-pin'));
  if(!cam||!stage) return;

  // tappe reali (p = avanzamento lungo il percorso 0..1)
  const stops=[
    {x:150,y:650,p:0},
    {x:360,y:560,p:.17},
    {x:560,y:430,p:.42},
    {x:790,y:280,p:.70},
    {x:1020,y:130,p:1}
  ];

  // viste di navigazione: si parte poco PRIMA del Diploma (punto virtuale)
  const seq=[
    {x:505,y:462,p:.36, tx:560,ty:430, active:2, icon:'↑', kicker:'Tra poco',        title:'Diploma',                copy:'Tra poche centinaia di metri raggiungi il Diploma, il primo grande traguardo del percorso.'},
    {x:560,y:430,p:.42, tx:790,ty:280, active:2, icon:'◉', kicker:'Tappa raggiunta',  title:'Diploma',                copy:'Maturità conseguita. Prosegui dritto verso l’Università di Brescia.'},
    {x:790,y:280,p:.70, tx:1020,ty:130,active:3, icon:'↑', kicker:'Prosegui dritto',  title:'Università · Brescia',   copy:'Ingegneria Informatica: qui costruisci basi solide nel software e nei sistemi.'},
    {x:1020,y:130,p:1,  tx:1250,ty:-20, active:4, icon:'⚑', kicker:'Sei arrivato',     title:'Estero · America',       copy:'Destinazione raggiunta: una carriera all’estero, con il sogno America.'}
  ];

  let vi=-1;            // -1 = panoramica; 0..3 = navigazione
  let userZoom=1;
  const last=seq.length-1;

  function heading(x,y,tx,ty){ return -90 - Math.atan2(ty-y, tx-x)*180/Math.PI; }
  function flash(el){ if(!el) return; el.classList.remove('mw-flash'); void el.offsetWidth; el.classList.add('mw-flash'); }

  function render(){
    const navMode = vi>=0;
    app.classList.toggle('nav', navMode);

    if(navMode){
      const v=seq[vi];
      const rot=heading(v.x,v.y,v.tx,v.ty), S=1.95, cx=600, cy=548;
      cam.style.transform=`translate(${cx}px, ${cy}px) rotate(${rot}deg) scale(${S}) translate(${-v.x}px, ${-v.y}px)`;
      pins.forEach((g,i)=>{ const r=g.querySelector('.mw-pin-rot'); let t=`rotate(${-rot})`; if(i===v.active) t+=' scale(1.18)'; r.setAttribute('transform',t); });
      if(puckRot) puckRot.setAttribute('transform',`rotate(${-rot})`);
      puck.setAttribute('transform',`translate(${v.x} ${v.y})`);
      progress.style.strokeDashoffset=(1-v.p).toFixed(4);
      kicker.textContent=v.kicker; title.textContent=v.title; copy.textContent=v.copy; icon.textContent=v.icon;
      pins.forEach((g,i)=>{ g.classList.toggle('on', i===v.active); g.classList.toggle('done', stops[i].p < v.p-0.02 && i!==v.active); });
      hint.textContent = vi<last ? 'Tocca per proseguire' : 'Tocca per rivedere la panoramica';
    } else {
      cam.style.transform=`translate(600px,380px) scale(${userZoom}) translate(-600px,-380px)`;
      pins.forEach(g=>{ g.querySelector('.mw-pin-rot').setAttribute('transform',''); g.classList.remove('on','done'); });
      if(puckRot) puckRot.setAttribute('transform','');
      puck.setAttribute('transform',`translate(${stops[0].x} ${stops[0].y})`);
      progress.style.strokeDashoffset='1';
      kicker.textContent='Percorso'; title.textContent='5 anni di crescita'; copy.textContent='Tocca la mappa per avviare la navigazione.'; icon.textContent='↗';
      hint.textContent='Tocca la mappa per iniziare';
    }
    flash(bannerText); flash(hint);
  }

  function advance(){
    if(vi>=last) { vi=-1; userZoom=1; }
    else vi++;
    render();
  }

  stage.addEventListener('click', e=>{
    const z=e.target.closest('[data-maps-zoom]');
    if(z){ if(vi<0){ userZoom=Math.max(1, Math.min(1.6, userZoom+(z.dataset.mapsZoom==='in'?.14:-.14))); render(); } return; }
    advance();
    try{ sndOpen&&sndOpen(); }catch(err){}
  });

  render();
})();'''
js = js[:js_s] + nav + js[js_e:]
(root/'hub.js').write_text(js)

# ===========================================================================
# 3) macos.css
# ===========================================================================
css = (root/'macos.css').read_text()
idx = css.index('Apple Maps (dark)')
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
  display:grid; grid-template-columns:300px minmax(0,1fr);
  overflow:hidden; background:#16181c; color:#f5f5f7;
  font-family:"SF Pro Display",-apple-system,BlinkMacSystemFont,"Helvetica Neue",Arial,sans-serif;
}
.win.maxi .maps-app{ height:100%; }
.maps-app button{ font:inherit; cursor:pointer; }

/* ---------- Sidebar ---------- */
.mw-side{ display:flex; flex-direction:column; min-height:0; padding:14px 14px 16px;
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

.mw-route{ position:relative; padding:4px 4px 4px 6px; margin-bottom:16px; }
.mw-route-line{ position:absolute; left:12px; top:20px; bottom:20px; width:2px; background:rgba(255,255,255,.14); border-radius:2px; }
.mw-endpoint{ display:flex; align-items:center; gap:12px; padding:7px 0; }
.mw-pt{ width:13px; height:13px; flex:none; border-radius:50%; position:relative; z-index:1; box-shadow:0 0 0 3px #1c1d21; }
.mw-pt.start{ background:#8e8e93; } .mw-pt.end{ background:#0a84ff; }
.mw-endpoint small{ display:block; font-size:11px; font-weight:700; letter-spacing:.02em; color:rgba(245,245,247,.42); }
.mw-endpoint b{ display:block; font-size:14px; font-weight:680; letter-spacing:-.01em; }

/* Single route card (stile Apple, selezionato) */
.mw-routecard{ display:flex; align-items:flex-start; gap:10px; margin-top:auto;
  padding:16px 16px; border-radius:18px; color:#fff;
  background:linear-gradient(180deg,#0a90ff,#0a78f0);
  box-shadow:0 12px 30px rgba(10,120,240,.34), inset 0 1px 0 rgba(255,255,255,.25); }
.mw-rc-main{ flex:1; min-width:0; }
.mw-rc-main b{ display:block; font-size:30px; font-weight:860; letter-spacing:-.03em; line-height:1; }
.mw-rc-main span{ display:block; margin-top:7px; font-size:12.5px; font-weight:560; color:rgba(255,255,255,.9); }
.mw-rc-main em{ display:inline-block; margin-top:10px; font-style:normal; font-size:12px; font-weight:760;
  padding:3px 9px; border-radius:8px; background:rgba(255,255,255,.18); }
.mw-rc-info{ width:24px; height:24px; flex:none; border-radius:50%; display:flex; align-items:center; justify-content:center;
  font-size:13px; font-weight:800; font-style:italic; color:#fff; background:rgba(255,255,255,.22); }

/* ---------- Map ---------- */
.mw-map-wrap{ position:relative; min-width:0; overflow:hidden; background:#23262c; cursor:pointer; }
.maps-map{ position:absolute; inset:0; width:100%; height:100%; display:block; }
.mw-map-wrap::after{ content:""; position:absolute; inset:0; pointer-events:none;
  background:linear-gradient(180deg, rgba(0,0,0,.18), transparent 18%, transparent 82%, rgba(0,0,0,.22)); }
#maps-cam{ transform-box:view-box; transform-origin:0 0; transition:transform 1.15s cubic-bezier(.32,.72,0,1); }
#maps-puck{ transition:transform 1.15s cubic-bezier(.32,.72,0,1); }
#maps-puck .puck-rot{ transition:transform 1.15s cubic-bezier(.32,.72,0,1); }
#maps-route-progress{ transition:stroke-dashoffset 1.15s cubic-bezier(.32,.72,0,1); }

/* contrassegni — pin a goccia, tutti BLU, stile Apple */
.mw-pin{ cursor:pointer; }
.mw-pin-drop{ animation:mwDrop .7s cubic-bezier(.3,1.36,.46,1) both; transform-box:fill-box; transform-origin:50% 100%; }
@keyframes mwDrop{ 0%{ opacity:0; transform:translateY(-30px) scale(.5); } 55%{ opacity:1; } 100%{ opacity:1; transform:none; } }
.mw-pin-rot{ transition:transform 1.15s cubic-bezier(.32,.72,0,1); }
.pin-ground{ fill:#000; opacity:.22; }
.pin-body{ fill:#0a84ff; filter:url(#mw-pin-sh); transition:fill .25s ease; }
.pin-core{ fill:#fff; }
.pin-num{ font-size:11px; font-weight:850; fill:#0a3d7a; }
.pin-halo{ fill:#0a84ff; opacity:0; transform-box:fill-box; transform-origin:50% 50%; }
.mw-pin:not(.on):not(.done) .pin-body{ fill:#4a9beb; }
.mw-pin.done .pin-body{ fill:#0a84ff; }
.mw-pin.done .pin-core{ fill:#cfe6ff; }
.mw-pin.on .pin-halo{ opacity:.32; animation:mwHalo 1.8s ease-out infinite; }
@keyframes mwHalo{ 0%{ transform:scale(.7); opacity:.4; } 70%{ opacity:0; } 100%{ transform:scale(1.9); opacity:0; } }

/* targhette — pulite, Apple style */
.mw-label{ transition:opacity .35s ease; }
.mw-label rect{ fill:rgba(22,24,28,.94); stroke:rgba(255,255,255,.16); stroke-width:1; filter:url(#mw-pin-sh); }
.mw-label .lbl-dot{ fill:#0a84ff; }
.mw-label text{ fill:#fff; font-size:14.5px; font-weight:720; letter-spacing:-.01em; }
.maps-app.nav .mw-label{ opacity:0; pointer-events:none; }

/* puck */
.puck-halo{ fill:#0a84ff; opacity:.18; transform-box:fill-box; transform-origin:50% 50%; animation:mwHalo 2s ease-out infinite; }
.puck-dot{ fill:#0a84ff; stroke:#fff; stroke-width:3; }
.puck-arrow{ fill:#0a84ff; stroke:#fff; stroke-width:2.5; stroke-linejoin:round; opacity:0; }
.maps-app.nav .puck-dot{ opacity:0; }
.maps-app.nav .puck-arrow{ opacity:1; }

/* card indicazioni (banner) — neutra in panoramica, verde in navigazione */
.mw-banner{ position:absolute; left:16px; top:16px; width:312px; max-width:calc(100% - 86px); z-index:5;
  display:flex; gap:13px; padding:14px 16px; border-radius:20px; color:#fff;
  background:rgba(34,36,41,.86); backdrop-filter:blur(18px) saturate(1.7); -webkit-backdrop-filter:blur(18px) saturate(1.7);
  box-shadow:0 16px 40px rgba(0,0,0,.34), inset 0 0 0 1px rgba(255,255,255,.09);
  transition:background .45s ease, box-shadow .45s ease; }
.maps-app.nav .mw-banner{ background:linear-gradient(180deg,#31ba53,#159b3c);
  box-shadow:0 18px 44px rgba(8,82,30,.42), inset 0 1px 0 rgba(255,255,255,.32); }
.mw-banner-icon{ width:46px; height:46px; flex:none; display:flex; align-items:center; justify-content:center;
  font-size:32px; font-weight:800; line-height:1; text-shadow:0 2px 4px rgba(0,0,0,.2); }
.mw-banner-text span{ display:block; font-size:11px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; color:rgba(255,255,255,.74); }
.mw-banner-text b{ display:block; margin-top:2px; font-size:21px; font-weight:850; letter-spacing:-.03em; line-height:1.05; }
.mw-banner-text p{ margin:6px 0 0; font-size:12.5px; line-height:1.4; font-weight:520; color:rgba(255,255,255,.94); }
.mw-flash{ animation:mwFlash .5s cubic-bezier(.22,1,.36,1); }
@keyframes mwFlash{ from{ opacity:0; transform:translateY(7px); } to{ opacity:1; transform:none; } }

/* zoom */
.mw-zoom{ position:absolute; right:15px; top:16px; z-index:6; display:flex; flex-direction:column; width:38px;
  border-radius:12px; overflow:hidden; background:rgba(40,42,47,.82); backdrop-filter:blur(14px) saturate(1.6);
  -webkit-backdrop-filter:blur(14px) saturate(1.6); box-shadow:0 10px 28px rgba(0,0,0,.3), inset 0 0 0 1px rgba(255,255,255,.1);
  transition:opacity .3s ease; }
.maps-app.nav .mw-zoom{ opacity:.35; }
.mw-zoom button{ height:38px; border:0; background:transparent; color:#f5f5f7; font-size:21px; font-weight:400;
  display:flex; align-items:center; justify-content:center; transition:background .16s ease, transform .2s var(--spring); }
.mw-zoom button:hover{ background:rgba(255,255,255,.12); } .mw-zoom button:active{ transform:scale(.9); }
.mw-zoom span{ height:1px; background:rgba(255,255,255,.12); margin:0 8px; }

/* hint (sostituisce la vecchia barra in basso) */
.mw-hint{ position:absolute; left:50%; bottom:18px; transform:translateX(-50%); z-index:5;
  padding:8px 16px; border-radius:999px; font-size:12.5px; font-weight:700; color:#f5f5f7; white-space:nowrap;
  background:rgba(34,36,41,.82); backdrop-filter:blur(14px) saturate(1.6); -webkit-backdrop-filter:blur(14px) saturate(1.6);
  box-shadow:0 8px 22px rgba(0,0,0,.3), inset 0 0 0 1px rgba(255,255,255,.1); pointer-events:none;
  animation:mwHintPulse 2.4s ease-in-out infinite; }
@keyframes mwHintPulse{ 0%,100%{ opacity:.7; } 50%{ opacity:1; } }

@media(max-width:860px){
  .maps-window{ width:94vw!important; }
  .maps-app{ grid-template-columns:1fr; }
  .mw-side{ display:none; }
  .mw-banner{ width:auto; left:12px; right:64px; }
}
'''
css = css[:cs] + css_new
(root/'macos.css').write_text(css)

print('Rebuild v4 OK')
print('hub.php', len(ph), '| hub.js', len(js), '| macos.css', len(css))
