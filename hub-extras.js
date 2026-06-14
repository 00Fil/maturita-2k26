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
