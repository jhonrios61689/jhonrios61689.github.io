 'use strict';
const dialog=document.querySelector('#social-dialog'),content=document.querySelector('#social-dialog-content');
let toastTimer;
function notice(text){const el=document.querySelector('#social-toast');el.textContent=text;el.classList.add('visible');clearTimeout(toastTimer);toastTimer=setTimeout(()=>el.classList.remove('visible'),3500)}
function show(html){content.innerHTML=html;dialog.showModal()}
const captions=['Tu pausa más dulce. Un postre, un café y un momento para ti.','Un toque de fresa para alegrar tu pausa.','Nuestra compañera de los momentos dulces.','Café que acompaña tu postre favorito.','Capas de sabor para disfrutar a tu ritmo.','¿Qué se te antoja hoy? Descúbrelo en nuestra tienda.'];
document.addEventListener('click',e=>{const b=e.target.closest('button');if(!b)return;
if(b.matches('.close'))dialog.close();
if(b.hasAttribute('data-follow')){const following=b.getAttribute('aria-pressed')==='true';b.setAttribute('aria-pressed',String(!following));b.textContent=following?'+ Seguir':'Siguiendo ✓';notice('Seguimiento de demostración actualizado localmente.')}
if(b.dataset.demo)notice(b.dataset.demo);
if(b.hasAttribute('data-message'))show('<h2>Hablemos de tu próxima pausa</h2><p style="margin:20px 0">Esta es una simulación del perfil. Para explorar los productos y preparar una solicitud, visita la tienda de Dulce Tentación.</p><a class="blue" style="display:inline-block;padding:10px 20px;border-radius:8px" href="index.html">Ir a la tienda</a>');
if(b.hasAttribute('data-post')){const index=Number(b.dataset.post);show('<div class="modal-art">'+b.querySelector('.art').outerHTML+'</div><div class="modal-copy"><strong>dulcetentacion.ejemplo</strong><p>'+captions[index]+'</p><button data-like aria-pressed="false">♡ Me gusta</button><p class="account-note">Publicación y reacciones de ejemplo.</p></div>')}
if(b.dataset.story){const texts={'Postres':'Pequeños antojos para tu pausa más dulce. Descubre nuestras propuestas de postres en la tienda.','Cafés':'El compañero de tu postre favorito. Una pausa con aroma a café.','Nuestra marca':'Postres protagonistas. Café que acompaña. Conoce a nuestra mascota cupcake.','Cómo pedir':'Visita la tienda, añade tus productos a Mi pedido y prepara tu solicitud por WhatsApp.'};show('<h2>'+b.dataset.story+'</h2><img src="assets/img/'+(b.dataset.story==='Nuestra marca'?'mascota.png':'postres-cafe.png')+'" alt="Propuesta visual de la marca" style="width:100%;margin:20px 0;border-radius:10px"><p>'+texts[b.dataset.story]+'</p>')}
if(b.hasAttribute('data-like')){const liked=b.getAttribute('aria-pressed')==='true';b.setAttribute('aria-pressed',String(!liked));b.textContent=liked?'♡ Me gusta':'♥ Te gusta';const count=b.closest('article')?.querySelector('[data-count]');if(count)count.textContent=Number(count.textContent)+(liked?-1:1)}
if(b.hasAttribute('data-comment')){const form=b.closest('article').querySelector('form');form.hidden=false;form.querySelector('input').focus()}
});
document.querySelectorAll('.comment-form').forEach(form=>form.addEventListener('submit',e=>{e.preventDefault();const input=form.querySelector('input');const text=input.value.trim();if(!text)return;const p=document.createElement('p');p.textContent='Comentario de prueba: '+text;form.closest('article').querySelector('.comments').append(p);input.value='';notice('Comentario guardado solo en esta vista de prueba.')}));
dialog.addEventListener('click',e=>{if(e.target===dialog){const r=dialog.getBoundingClientRect();if(e.clientX<r.left||e.clientX>r.right||e.clientY<r.top||e.clientY>r.bottom)dialog.close()}});

const fbSearch=document.querySelector('#fb-search');
if(fbSearch)fbSearch.addEventListener('keydown',e=>{if(e.key==='Enter'){e.preventDefault();const term=fbSearch.value.trim().toLocaleLowerCase('es');const posts=[...document.querySelectorAll('.fb-post')];let shown=0;posts.forEach(post=>{post.hidden=!!term&&!post.textContent.toLocaleLowerCase('es').includes(term);if(!post.hidden)shown++});notice(term?`${shown} publicaciones encontradas en esta página.`:'Se muestran todas las publicaciones.');document.querySelector('#feed').scrollIntoView({behavior:'smooth'})}});
