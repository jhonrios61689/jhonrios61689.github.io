 'use strict';
(()=>{
const viewer=document.querySelector('#fb-photo-viewer');if(!viewer)return;
const imageBox=document.querySelector('#fb-photo-image'),caption=document.querySelector('#fb-photo-caption'),counter=document.querySelector('#fb-photo-counter'),like=document.querySelector('#fb-photo-like'),comments=document.querySelector('#fb-photo-comments'),input=document.querySelector('#fb-photo-comment');
const blocks=[...document.querySelectorAll('.fb-cover,.fb-photos>div,.fb-post>.art')];
const photos=[...blocks,...[...document.querySelectorAll('.facebook img')].filter(img=>!blocks.some(block=>block.contains(img))&&!viewer.contains(img))];
let index=0,zoom=1;const state=new Map();
photos.forEach((el,i)=>{el.dataset.photoIndex=i;el.tabIndex=0;el.setAttribute('role','button');el.setAttribute('aria-label',el.classList.contains('fb-cover')?'Ampliar foto de portada':'Ampliar foto de Dulce Tentación');el.setAttribute('aria-haspopup','dialog');});
function current(){if(!state.has(index))state.set(index,{liked:false,comments:[]});return state.get(index)}
function reactions(){const value=current();like.setAttribute('aria-pressed',String(value.liked));like.textContent=value.liked?'♥ Te gusta':'♡ Me gusta';comments.replaceChildren();value.comments.forEach(text=>{const p=document.createElement('p');p.textContent=text;comments.append(p)})}
function scale(){imageBox.style.transform=`scale(${zoom})`;document.querySelector('[data-photo-zoom="-1"]').disabled=zoom<=1;document.querySelector('[data-photo-zoom="1"]').disabled=zoom>=3}
function render(){const source=photos[index];const copy=source.cloneNode(true);copy.removeAttribute('id');copy.removeAttribute('role');copy.removeAttribute('tabindex');copy.removeAttribute('data-photo-index');copy.removeAttribute('aria-haspopup');copy.querySelectorAll('[id]').forEach(el=>el.removeAttribute('id'));imageBox.replaceChildren(copy);const post=source.closest('.fb-post');caption.textContent=post?.querySelector(':scope > p')?.textContent|| (source.classList.contains('fb-cover')?'Tu pausa más dulce. Portada de Dulce Tentación.':source.matches('img')?'Logo de Dulce Tentación. Postres protagonistas. Café que acompaña.':'Un pequeño antojo para tu pausa más dulce.');counter.textContent=`${index+1} / ${photos.length}`;zoom=1;scale();input.value='';reactions()}
function open(i){index=i;render();viewer.showModal()}
function step(amount){index=(index+amount+photos.length)%photos.length;render()}
document.addEventListener('click',e=>{const source=e.target.closest('[data-photo-index]');if(source&&!viewer.contains(source)){e.preventDefault();e.stopImmediatePropagation();open(Number(source.dataset.photoIndex))}},true);
document.addEventListener('keydown',e=>{const source=e.target.closest('[data-photo-index]');if(source&&!viewer.open&&(e.key==='Enter'||e.key===' ')){e.preventDefault();open(Number(source.dataset.photoIndex))}});
viewer.addEventListener('click',e=>{const b=e.target.closest('button');if(!b)return;if(b.hasAttribute('data-photo-close'))viewer.close();if(b.hasAttribute('data-photo-step'))step(Number(b.dataset.photoStep));if(b.hasAttribute('data-photo-zoom')){zoom=Math.max(1,Math.min(3,zoom+Number(b.dataset.photoZoom)*.25));scale()}if(b.hasAttribute('data-photo-fit')){zoom=1;scale()}if(b===like){current().liked=!current().liked;reactions()}if(b.id==='fb-photo-comment-focus')input.focus()});
viewer.addEventListener('keydown',e=>{if(e.target.matches('textarea,input'))return;if(e.key==='ArrowRight'){e.preventDefault();step(1)}if(e.key==='ArrowLeft'){e.preventDefault();step(-1)}});
document.querySelector('#fb-photo-comment-form').addEventListener('submit',e=>{e.preventDefault();const text=input.value.trim();if(!text)return;current().comments.push(text);input.value='';reactions()});
})();
