# -*- coding: utf-8 -*-
from pathlib import Path
root = Path('/data/maturita-work/maturita-2k26-main')

# ============================================================
# v6 — cursore/progresso agganciati al path + rimozione strade grigie
# ============================================================

# 1) Rimuovi le strade grigie dallo sfondo in hub.php
php_path = root/'hub.php'
ph = php_path.read_text()
old_roads = '''          <g stroke-linecap="round" fill="none">
            <path d="M-200 540 C200 480 360 440 520 360 C720 262 880 230 1400 150" stroke="#3a3e45" stroke-width="9"/>
            <path d="M40 760 C260 600 380 560 540 460 C720 350 900 300 1400 230" stroke="#33373d" stroke-width="6"/>
            <path d="M300 760 C340 600 460 470 580 400 C700 330 760 250 800 120" stroke="#33373d" stroke-width="5"/>
          </g>

'''
if old_roads in ph:
    ph = ph.replace(old_roads, '')
else:
    print('WARN: blocco strade grigie non trovato, continuo')
php_path.write_text(ph)

# 2) Sostituisci il navigatore JS: posizione puck e progress calcolati sul path reale
js_path = root/'hub.js'
js = js_path.read_text()
js_s = js.index('/* Maps Navigator')
js_e = js.index('})();', js_s) + len('})();')

nav = r'''/* Maps Navigator — path-locked cursor/progress */
(function(){
  const app=document.querySelector('[data-maps-navigator]');
  if(!app) return;
  const cam=app.querySelector('#maps-cam');
  const puck=app.querySelector('#maps-puck');
  const puckRot=app.querySelector('#maps-puck .puck-rot');
  const progress=app.querySelector('#maps-route-progress');
  const route=app.querySelector('#maps-route');
  const stage=app.querySelector('[data-maps-stage]');
  const title=app.querySelector('[data-maps-title]');
  const kicker=app.querySelector('[data-maps-kicker]');
  const copy=app.querySelector('[data-maps-copy]');
  const icon=app.querySelector('[data-maps-icon]');
  const bannerText=app.querySelector('[data-maps-bannertext]');
  const hint=app.querySelector('[data-maps-hint]');
  const pins=Array.from(app.querySelectorAll('.mw-pin'));
  if(!cam||!stage||!route||!progress||!puck) return;

  // Percentuali lungo il path reale. Puck e progresso usano questi stessi valori.
  const stopP=[0,.17,.42,.70,1];
  const seq=[
    {p:.36, targetP:.42, active:2, icon:'↑', kicker:'Tra poco',       title:'Diploma',              copy:'Tra poche centinaia di metri raggiungi il Diploma, il primo grande traguardo del percorso.'},
    {p:.42, targetP:.58, active:2, icon:'◉', kicker:'Tappa raggiunta', title:'Diploma',              copy:'Maturità conseguita. Prosegui dritto verso l’Università di Brescia.'},
    {p:.70, targetP:.86, active:3, icon:'↑', kicker:'Prosegui dritto', title:'Università · Brescia', copy:'Ingegneria Informatica: qui costruisci basi solide nel software e nei sistemi.'},
    {p:1,   targetP:1,   active:4, icon:'⚑', kicker:'Sei arrivato',    title:'Estero · America',     copy:'Destinazione raggiunta: una carriera all’estero, con il sogno America.'}
  ];

  let vi=-1;
  let userZoom=1;
  const last=seq.length-1;

  function total(){ return route.getTotalLength(); }
  function pt(p){
    const L=total();
    const clamped=Math.max(0,Math.min(1,p));
    return route.getPointAtLength(L*clamped);
  }
  function headingFromPath(p,targetP){
    const a=pt(p);
    const b=pt(Math.max(0, Math.min(1, targetP!=null ? targetP : p+.012)));
    return -90 - Math.atan2(b.y-a.y, b.x-a.x)*180/Math.PI;
  }
  function flash(el){ if(!el) return; el.classList.remove('mw-flash'); void el.offsetWidth; el.classList.add('mw-flash'); }
  function setProgress(p){ progress.style.strokeDashoffset=(1-Math.max(0,Math.min(1,p))).toFixed(4); }

  function render(){
    const navMode=vi>=0;
    app.classList.toggle('nav', navMode);

    if(navMode){
      const v=seq[vi];
      const pos=pt(v.p);
      const rot=headingFromPath(v.p, v.targetP);
      const S=1.95, cx=600, cy=548;

      // Camera, cursore e tratto completato condividono la stessa p sul path.
      cam.style.transform=`translate(${cx}px, ${cy}px) rotate(${rot}deg) scale(${S}) translate(${-pos.x}px, ${-pos.y}px)`;
      puck.setAttribute('transform',`translate(${pos.x.toFixed(2)} ${pos.y.toFixed(2)})`);
      setProgress(v.p);

      pins.forEach((g,i)=>{
        const r=g.querySelector('.mw-pin-rot');
        let t=`rotate(${-rot})`;
        if(i===v.active) t+=' scale(1.08)';
        if(r) r.setAttribute('transform',t);
        g.classList.toggle('on', i===v.active);
        g.classList.toggle('done', stopP[i] < v.p-0.02 && i!==v.active);
      });
      if(puckRot) puckRot.setAttribute('transform',`rotate(${-rot})`);

      kicker.textContent=v.kicker; title.textContent=v.title; copy.textContent=v.copy; icon.textContent=v.icon;
      hint.textContent=vi<last ? 'Tocca per proseguire' : 'Tocca per rivedere la panoramica';
    } else {
      cam.style.transform=`translate(600px,380px) scale(${userZoom}) translate(-600px,-380px)`;
      pins.forEach(g=>{ const r=g.querySelector('.mw-pin-rot'); if(r) r.setAttribute('transform',''); g.classList.remove('on','done'); });
      if(puckRot) puckRot.setAttribute('transform','');
      const start=pt(0);
      puck.setAttribute('transform',`translate(${start.x.toFixed(2)} ${start.y.toFixed(2)})`);
      setProgress(0);
      kicker.textContent='Percorso'; title.textContent='5 anni di crescita'; copy.textContent='Tocca la mappa per avviare la navigazione.'; icon.textContent='↗';
      hint.textContent='Tocca la mappa per iniziare';
    }
    flash(bannerText); flash(hint);
  }

  function advance(){
    if(vi>=last){ vi=-1; userZoom=1; }
    else vi++;
    render();
  }

  stage.addEventListener('click', e=>{
    const z=e.target.closest('[data-maps-zoom]');
    if(z){
      if(vi<0){ userZoom=Math.max(1,Math.min(1.6,userZoom+(z.dataset.mapsZoom==='in'?.14:-.14))); render(); }
      return;
    }
    advance();
    try{ sndOpen&&sndOpen(); }catch(err){}
  });

  render();
})();'''

js_path.write_text(js[:js_s] + nav + js[js_e:])
print('Rebuild v6 OK')
