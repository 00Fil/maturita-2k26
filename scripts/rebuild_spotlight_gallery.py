# -*- coding: utf-8 -*-
from pathlib import Path
from PIL import Image
root = Path('/data/maturita-work/maturita-2k26-main')
gal = root/'assets/spotlight-gallery'
gal.mkdir(parents=True, exist_ok=True)

# ------------------------------------------------------------------
# Build static image-app windows from files in assets/spotlight-gallery
# ------------------------------------------------------------------
files = []
for ext in ('*.png','*.jpg','*.jpeg','*.webp'):
    files.extend(gal.glob(ext))
files = sorted(files, key=lambda p: (p.name != 'center.png', p.name))
if not (gal/'center.png').exists():
    raise SystemExit('center.png mancante in assets/spotlight-gallery')

positions = [
    ('4.5%', '9%'), ('58%', '8%'), ('8%', '46%'), ('64%', '42%'), ('31%', '10%'), ('40%', '52%')
]
side_max = [330, 360, 340, 380, 320, 360]

sections = []
side_i = 0
for p in files:
    rel = 'assets/spotlight-gallery/' + p.name
    with Image.open(p) as im:
        w,h = im.size
    is_center = p.name == 'center.png'
    if is_center:
        disp_w = min(w, 640)
        left = 'calc(50%% - %dpx)' % (disp_w//2)
        # approximate image height after scaling + titlebar
        disp_h = int(h * disp_w / w)
        top = 'calc(50%% - %dpx)' % ((disp_h + 44)//2)
        cls = 'sg-window sg-center-win'
        data = ' data-spot-gallery-window data-spot-center'
        title = 'George Orwell — centro'
    else:
        lim = side_max[side_i % len(side_max)]
        disp_w = min(w, lim)
        left, top = positions[side_i % len(positions)]
        cls = 'sg-window'
        data = ' data-spot-gallery-window'
        title = p.stem.replace('-', ' ').replace('_', ' ').title()
        side_i += 1
    sections.append(f'''<section class="win {cls}" id="sg-{p.stem}" style="left:{left};top:{top};width:{disp_w}px"{data}>
  <div class="titlebar sg-titlebar"><span class="wt">{title}</span></div>
  <div class="wbody sg-body">
    <img src="{rel}" alt="{title}" draggable="false" data-natural-width="{w}" data-natural-height="{h}">
  </div>
</section>''')

gallery_html = "\n\n<!-- Spotlight image gallery windows -->\n" + "\n".join(sections) + "\n<!-- /Spotlight image gallery windows -->\n\n"

php_path = root/'hub.php'
ph = php_path.read_text()
# remove previous gallery block if any
start = ph.find('<!-- Spotlight image gallery windows -->')
if start != -1:
    end = ph.find('<!-- /Spotlight image gallery windows -->', start)
    if end != -1:
        end = ph.find('\n', end) + 1
        ph = ph[:start] + ph[end:]
insert = ph.index('<nav class="dock" id="dock">')
ph = ph[:insert] + gallery_html + ph[insert:]
php_path.write_text(ph)

# ------------------------------------------------------------------
# CSS append/replace gallery block
# ------------------------------------------------------------------
css_path = root/'macos.css'
css = css_path.read_text()
css_start = css.find('/* Spotlight image gallery windows */')
if css_start != -1:
    css_end = css.find('/* /Spotlight image gallery windows */', css_start)
    if css_end != -1:
        css_end = css.find('\n', css_end) + 1
        css = css[:css_start] + css[css_end:]
css_block = r'''
/* Spotlight image gallery windows */
.sg-window{background:rgba(28,29,33,.86)!important;border-radius:18px!important;min-width:0!important;max-width:min(92vw,720px);max-height:calc(100vh - 92px);box-shadow:0 28px 90px rgba(0,0,0,.48),inset 0 1px 0 rgba(255,255,255,.12),inset 0 0 0 1px rgba(255,255,255,.10)!important;backdrop-filter:blur(22px) saturate(1.6);-webkit-backdrop-filter:blur(22px) saturate(1.6);overflow:hidden}
.sg-titlebar{height:38px;background:rgba(39,40,45,.76);box-shadow:0 1px 0 rgba(255,255,255,.08)}
.sg-titlebar .wt{font-size:12px;color:rgba(245,245,247,.72);margin-right:51px}
.sg-body{overflow:hidden;background:#08090b;display:flex;align-items:center;justify-content:center}
.sg-body img{display:block;width:100%;height:auto;max-height:calc(100vh - 136px);object-fit:contain;user-select:none;-webkit-user-drag:none}
.sg-window.open{animation:sgWinIn .54s cubic-bezier(.18,1.16,.32,1) both}
.sg-window.closing{animation:sgWinOut .24s cubic-bezier(.22,1,.36,1) both}
.sg-center-win{z-index:9998!important;max-width:min(72vw,760px);box-shadow:0 34px 120px rgba(0,0,0,.62),0 0 0 1px rgba(255,255,255,.12),inset 0 1px 0 rgba(255,255,255,.16)!important}
.sg-center-win.open{animation:sgCenterIn .62s cubic-bezier(.18,1.16,.32,1) both}
.sg-center-win .sg-titlebar{background:rgba(22,23,27,.84)}
.sg-center-win .sg-body{background:#050607}
@keyframes sgWinIn{from{opacity:0;transform:translateY(26px) scale(.72) rotate(var(--sg-rot,-1deg));filter:blur(8px)}to{opacity:1;transform:translateY(0) scale(1) rotate(0);filter:none}}
@keyframes sgCenterIn{0%{opacity:0;transform:translateY(18px) scale(.78);filter:blur(10px)}58%{opacity:1;transform:translateY(-4px) scale(1.025);filter:none}100%{opacity:1;transform:none;filter:none}}
@keyframes sgWinOut{from{opacity:1;transform:scale(1);filter:none}to{opacity:0;transform:scale(.88) translateY(12px);filter:blur(4px)}}
/* /Spotlight image gallery windows */
'''
css_path.write_text(css + css_block)

# ------------------------------------------------------------------
# JS replace Spotlight IIFE so result opens many image apps
# ------------------------------------------------------------------
js_path = root/'hub.js'
js = js_path.read_text()
start = js.index("(function(){\n  var spot=document.getElementById('spot');")
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

  function closeSpot(silent){
    if(!spot.classList.contains('on'))return;
    spot.classList.remove('on','typing','ready');
    spot.setAttribute('aria-hidden','true');
    clearInterval(timer); clearTimeout(closeTimer);
    if(!silent && typeof sndClose==='function'){try{sndClose();}catch(e){}}
    setTimeout(resetSpot,220);
  }

  function placeCenter(win){
    if(!win) return;
    win.style.setProperty('left','50%','important');
    win.style.setProperty('top','50%','important');
    win.style.setProperty('transform','translate(-50%, -50%)','important');
    win.style.setProperty('z-index','9998','important');
  }

  function openGallery(){
    if(!spot.classList.contains('ready')) return;
    closeSpot(true);
    var all=Array.prototype.slice.call(document.querySelectorAll('[data-spot-gallery-window]'));
    var center=all.find(function(w){ return w.hasAttribute('data-spot-center'); });
    var side=all.filter(function(w){ return w!==center; });

    // Chiudo eventuali finestre gallery già aperte per rilanciare l'animazione.
    all.forEach(function(w){ w.classList.remove('open','closing','maxi'); w.style.removeProperty('transform'); });

    side.forEach(function(w,i){
      w.style.setProperty('--sg-rot', (i%2 ? '1.2deg' : '-1.2deg'));
      setTimeout(function(){ openWin(w.id); }, i*58);
    });
    setTimeout(function(){
      if(center){
        openWin(center.id);
        placeCenter(center);
      }
    }, Math.min(260, side.length*58 + 70));
  }

  if(result){
    result.style.cursor='pointer';
    result.addEventListener('click',function(e){ e.preventDefault(); e.stopPropagation(); openGallery(); });
  }
  window.addEventListener('resize',function(){
    var center=document.querySelector('[data-spot-center].open');
    if(center) placeCenter(center);
  });

  document.addEventListener('click',function(e){
    var t=e.target.closest('[data-spot]');
    if(t){e.preventDefault();openSpot();return;}
    if(spot.classList.contains('on')&&shell&&!shell.contains(e.target))closeSpot();
  });
  document.addEventListener('keydown',function(e){
    if((e.metaKey||e.ctrlKey)&&e.code==='Space'){e.preventDefault();openSpot();return;}
    if(e.key==='Escape')closeSpot();
    if(e.key==='Enter'&&spot.classList.contains('ready')){e.preventDefault();openGallery();}
  });
})();'''
js_path.write_text(js[:start] + new_js + js[end:])

print('Spotlight gallery rebuild OK')
print('files:', ', '.join(p.name for p in files))
print('windows:', len(files))
