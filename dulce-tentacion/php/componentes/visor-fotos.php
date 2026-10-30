<dialog id="fb-photo-viewer" aria-label="Visor de fotos de Dulce Tentación">
    <section class="photo-stage" aria-label="Fotografía ampliada">
        <div class="photo-top-left"><button type="button" data-photo-close aria-label="Cerrar visor">×</button><span class="photo-fb-logo" aria-hidden="true">f</span></div>
        <div class="photo-zoom"><button type="button" data-photo-zoom="1" aria-label="Ampliar foto">＋</button><button type="button" data-photo-zoom="-1" aria-label="Reducir foto">−</button><button type="button" data-photo-fit aria-label="Restablecer tamaño">⛶</button></div>
        <button type="button" class="photo-prev" data-photo-step="-1" aria-label="Foto anterior">‹</button>
        <div class="photo-canvas">
            <div id="fb-photo-image"></div>
        </div>
        <button type="button" class="photo-next" data-photo-step="1" aria-label="Foto siguiente">›</button>
        <span id="fb-photo-counter" aria-live="polite"></span>
    </section>
    <aside class="photo-detail">
        <header><img src="assets/img/logo.png" alt="Dulce Tentación">
            <div><strong>Dulce Tentación</strong><small>Foto de la marca · Público</small></div>
        </header>
        <p id="fb-photo-caption"></p>
        <p class="photo-detail-note">Un postre, un café y un momento para ti. Descubre tu próxima tentación en nuestra tienda.</p><a href="index.html">Visitar la tienda ↗</a>
        <div class="photo-reaction-row"><button type="button" id="fb-photo-like" aria-pressed="false">♡ Me gusta</button><button type="button" id="fb-photo-comment-focus">○ Comentar</button></div>
        <div id="fb-photo-comments" aria-live="polite"></div>
        <form id="fb-photo-comment-form"><label for="fb-photo-comment">Comentar como visitante</label><textarea id="fb-photo-comment" maxlength="300" rows="2" placeholder="Escribe un comentario de prueba…" required></textarea><button type="submit">Publicar localmente</button><small>Las reacciones y comentarios se muestran solo en esta vista local.</small></form>
    </aside>
</dialog>