# -*- coding: utf-8 -*-
from pathlib import Path
root = Path('/data/maturita-work/maturita-2k26-main')

# Ensure icon is in project
src = Path('/data/maturita-work/spotlight-new-icon.webp')
dst = root/'assets/icons/spotlight.webp'
dst.parent.mkdir(parents=True, exist_ok=True)
if src.exists():
    dst.write_bytes(src.read_bytes())

# ------------------------------------------------------------------
# hub.php: replace wrong Safari icon refs and Spotlight markup
# ------------------------------------------------------------------
php_path = root/'hub.php'
ph = php_path.read_text()
ph = ph.replace("<?= appicon('safari.webp', '/src/apps/scalable/safari.svg') ?>", "<?= appicon('spotlight.webp', '/src/apps/scalable/system-search.svg') ?>")

spot_start = ph.index('<div id="spot" class="spot" aria-hidden="true">')
spot_end = ph.index('<nav class="dock" id="dock">', spot_start)
new_spot = '''<div id="spot" class="spot" aria-hidden="true">
  <div class="spot-shell" role="dialog" aria-label="Spotlight">
    <div class="spot-box">
      <span class="spot-icon-wrap"><?= appicon('spotlight.webp', '/src/apps/scalable/system-search.svg') ?></span>
      <span class="spot-q"><span class="spot-type"></span><span class="spot-ghost">Cerca con Spotlight</span><span class="spot-cur"></span></span>
    </div>
    <div class="spot-result" aria-hidden="true">
      <span class="spot-result-icon"><?= appicon('spotlight.webp', '/src/apps/scalable/system-search.svg') ?></span>
      <span><b>Conclusione</b><small>L'ultima parola prima delle domande</small></span>
      <em>Invio</em>
    </div>
  </div>
  <p class="spot-sub">Cerca con Spotlight</p>
</div>

'''
ph = ph[:spot_start] + new_spot + ph[spot_end:]
php_path.write_text(ph)

# ------------------------------------------------------------------
# hub.js: replace Spotlight behavior with cleaner open/type/result animation
# ------------------------------------------------------------------
js_path = root/'hub.js'
js = js_path.read_text()
start = js.index('(function(){\n  var spot=document.getElementById(\'spot\');')
end = js.index('})();', start) + len('})();')
new_js = r'''(function(){
  var spot=document.getElementById('spot');
  if(!spot)return;
  var shell=spot.querySelector('.spot-shell');
  var box=spot.querySelector('.spot-box');
  var type=spot.querySelector('.spot-type');
  var ghost=spot.querySelector('.spot-ghost');
  var result=spot.querySelector('.spot-result');
  var TEXT='Le parole non sono mai neutre';
  var timer=null, closeTimer=null;

  function resetSpot(){
    clearInterval(timer); clearTimeout(closeTimer);
    spot.classList.remove('typing','ready');
    type.textContent='';
    if(ghost) ghost.style.opacity='1';
    if(result) result.setAttribute('aria-hidden','true');
  }

  function openSpot(){
    if(spot.classList.contains('on'))return;
    resetSpot();
    spot.classList.add('on','typing');
    spot.setAttribute('aria-hidden','false');
    if(typeof sndOpen==='function'){try{sndOpen();}catch(e){}}

    var i=0;
    closeTimer=setTimeout(function(){
      if(ghost) ghost.style.opacity='0';
      timer=setInterval(function(){
        type.textContent=TEXT.slice(0,++i);
        if(i>=TEXT.length){
          clearInterval(timer);
          spot.classList.remove('typing');
          spot.classList.add('ready');
          if(result) result.setAttribute('aria-hidden','false');
        }
      },42);
    },170);
  }

  function closeSpot(){
    if(!spot.classList.contains('on'))return;
    spot.classList.remove('on','typing','ready');
    spot.setAttribute('aria-hidden','true');
    clearInterval(timer); clearTimeout(closeTimer);
    if(typeof sndClose==='function'){try{sndClose();}catch(e){}}
    setTimeout(resetSpot,220);
  }

  document.addEventListener('click',function(e){
    var t=e.target.closest('[data-spot]');
    if(t){e.preventDefault();openSpot();return;}
    if(spot.classList.contains('on')&&shell&&!shell.contains(e.target))closeSpot();
  });
  document.addEventListener('keydown',function(e){
    if((e.metaKey||e.ctrlKey)&&e.code==='Space'){e.preventDefault();openSpot();return;}
    if(e.key==='Escape')closeSpot();
    if(e.key==='Enter'&&spot.classList.contains('ready'))closeSpot();
  });
})();'''
js = js[:start] + new_js + js[end:]
js_path.write_text(js)

# ------------------------------------------------------------------
# macos.css: replace current Spotlight block only
# ------------------------------------------------------------------
css_path = root/'macos.css'
css = css_path.read_text()
cs = css.index('.spot{position:fixed;')
# End at next major block after .spot.on .spot-sub; current block is compact before notes CSS.
# Prefer finding the boot/next app selector after spotlight area.
next_candidates = [p for p in [css.find('.notes-real-window', cs), css.find('/*', cs+1)] if p != -1]
ce = min(next_candidates) if next_candidates else len(css)
new_css = r'''/* Spotlight — macOS 14 style */
.spot{position:fixed;inset:0;z-index:4700;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;padding-top:19.5vh;background:rgba(0,0,0,.10);backdrop-filter:blur(3px) saturate(1.08);-webkit-backdrop-filter:blur(3px) saturate(1.08);opacity:0;pointer-events:none;transition:opacity .18s ease,backdrop-filter .22s ease}
.spot.on{opacity:1;pointer-events:auto;backdrop-filter:blur(9px) saturate(1.25);-webkit-backdrop-filter:blur(9px) saturate(1.25)}
.spot-shell{width:min(560px,90vw);transform:translateY(-18px) scale(.965);opacity:0;filter:blur(8px);transition:transform .42s cubic-bezier(.18,1.16,.32,1),opacity .22s ease,filter .32s ease}
.spot.on .spot-shell{transform:none;opacity:1;filter:none}
.spot-box{height:58px;display:flex;align-items:center;gap:13px;padding:0 18px;border-radius:18px;background:linear-gradient(180deg,rgba(31,93,133,.78),rgba(19,63,99,.74));border:1px solid rgba(210,238,255,.28);box-shadow:0 24px 76px rgba(0,0,0,.38),0 2px 9px rgba(0,0,0,.24),inset 0 1px 0 rgba(255,255,255,.22),inset 0 -1px 0 rgba(0,0,0,.18);backdrop-filter:blur(34px) saturate(1.9);-webkit-backdrop-filter:blur(34px) saturate(1.9);overflow:hidden;position:relative}
.spot-box::before{content:"";position:absolute;inset:0;border-radius:inherit;background:radial-gradient(circle at 18% 0%,rgba(255,255,255,.22),transparent 42%),linear-gradient(90deg,rgba(255,255,255,.04),transparent 36%,rgba(255,255,255,.05));pointer-events:none}
.spot-icon-wrap{width:30px;height:30px;flex:none;display:flex;align-items:center;justify-content:center;position:relative;z-index:1;filter:drop-shadow(0 2px 5px rgba(0,0,0,.25));transform:scale(.9);opacity:.92;transition:transform .36s cubic-bezier(.3,1.36,.46,1),opacity .2s ease}
.spot.on .spot-icon-wrap{transform:scale(1);opacity:1}.spot-icon-wrap img{width:30px;height:30px;object-fit:contain;display:block;border-radius:8px}
.spot-q{position:relative;z-index:1;display:flex;align-items:center;min-width:0;flex:1;height:100%;font-size:23px;font-weight:560;letter-spacing:-.025em;color:#f7fbff;text-shadow:0 1px 10px rgba(0,0,0,.28)}
.spot-type{white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.spot-ghost{position:absolute;left:0;color:rgba(247,251,255,.92);transition:opacity .18s ease;font-weight:560}.spot-cur{display:inline-block;width:2px;height:28px;margin-left:2px;background:rgba(255,255,255,.88);border-radius:1px;box-shadow:0 0 9px rgba(255,255,255,.45);animation:spotcur 1.05s steps(1) infinite;opacity:0}.spot.typing .spot-cur,.spot.ready .spot-cur{opacity:1}@keyframes spotcur{50%{opacity:0}}
.spot-result{display:flex;align-items:center;gap:12px;margin-top:8px;padding:10px 12px;border-radius:14px;background:rgba(24,28,33,.58);border:1px solid rgba(255,255,255,.12);box-shadow:0 14px 38px rgba(0,0,0,.28),inset 0 1px 0 rgba(255,255,255,.10);backdrop-filter:blur(24px) saturate(1.7);-webkit-backdrop-filter:blur(24px) saturate(1.7);opacity:0;transform:translateY(-8px) scale(.985);filter:blur(3px);transition:opacity .24s ease,transform .34s cubic-bezier(.22,1,.36,1),filter .24s ease}
.spot.ready .spot-result{opacity:1;transform:none;filter:none}.spot-result-icon{width:38px;height:38px;border-radius:10px;overflow:hidden;flex:none;box-shadow:0 4px 14px rgba(0,0,0,.25)}.spot-result-icon img{width:100%;height:100%;object-fit:cover;display:block}.spot-result span{display:flex;flex-direction:column;min-width:0;flex:1}.spot-result b{font-size:14px;font-weight:750;color:#fff;letter-spacing:-.01em}.spot-result small{font-size:12px;color:rgba(255,255,255,.62);margin-top:2px}.spot-result em{font-style:normal;font-size:11px;font-weight:750;color:rgba(255,255,255,.58);padding:3px 7px;border-radius:7px;background:rgba(255,255,255,.08)}
.spot-sub{margin-top:14px;font-size:13px;font-weight:650;color:rgba(255,255,255,.9);text-shadow:0 1px 16px rgba(0,0,0,.55);opacity:0;transform:translateY(6px);transition:opacity .3s ease .10s,transform .3s ease .10s}.spot.on .spot-sub{opacity:1;transform:none}.spot.ready .spot-sub{opacity:.72}
'''
css_path.write_text(css[:cs] + new_css + css[ce:])

print('Spotlight rebuild OK')
print('hub.php', len(php_path.read_text()), '| hub.js', len(js_path.read_text()), '| macos.css', len(css_path.read_text()))
