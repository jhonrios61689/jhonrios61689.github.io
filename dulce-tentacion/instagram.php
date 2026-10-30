<?php require __DIR__ . '/php/componentes/redes.php'; ?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" href="assets/img/logo.png">
    <link rel="stylesheet" href="css/redes.css">
    <script src="js/redes.js" defer></script>
    <title>Instagram de Dulce Tentación · Simulación</title>
</head>

<body class="instagram">
    <aside class="ig-sidebar"><a class="instagram-word" href="instagram.php">Instagram</a>
        <nav aria-label="Instagram simulado"><a href="#perfil">⌂ <span>Inicio</span></a><a href="#publicaciones">▦ <span>Explorar</span></a><button data-demo="Los reels se definirán en la propuesta de contenidos.">▷ <span>Reels</span></button><button data-message>♡ <span>Mensajes</span></button><button data-demo="Aquí aparecerían las interacciones de la cuenta real.">♧ <span>Notificaciones</span></button><a href="#perfil"><?php echo social_avatar('mini'); ?> <span>Perfil</span></a></nav><a class="sidebar-shop" href="index.html">Visitar la tienda ↗</a>
    </aside>
    <main class="ig-main" id="perfil">
        <header class="ig-profile">
            <div class="avatar-ring"><?php echo social_avatar(); ?></div>
            <div class="ig-bio">
                <div class="ig-name">
                    <h1>dulcetentacion.ejemplo</h1><button class="blue" data-follow>Seguir</button><button data-message>Mensaje</button>
                </div>
                <div class="ig-counts"><span><b>6</b> publicaciones</span><span><b>1.280</b> seguidores</span><span><b>184</b> seguidos</span></div><strong>Dulce Tentación | Postres y café</strong>
                <p>Tu pausa más dulce. 🧁☕<br>Postres protagonistas. Café que acompaña.<br>Descubre tu próximo antojo ↓</p><a class="bio-link" href="index.html">↗ Nuestra tienda</a><small class="account-note">Nombre de usuario propuesto · Cifras de ejemplo</small>
            </div>
        </header>
        <section class="highlights" aria-label="Historias destacadas"><button data-story="Postres"><span class="highlight-art"><img src="assets/img/postres-cafe.png" alt=""></span><b>Postres</b></button><button data-story="Cafés"><span class="highlight-art"><img src="assets/img/postres-cafe.png" alt=""></span><b>Cafés</b></button><button data-story="Nuestra marca"><span class="highlight-art"><?php echo social_avatar(); ?></span><b>Nuestra marca</b></button><button data-story="Cómo pedir"><span class="highlight-art"><img src="assets/img/mascota.png" alt=""></span><b>Cómo pedir</b></button></section>
        <div class="ig-tabs" id="publicaciones"><span>▦ PUBLICACIONES</span><span>UNA PAUSA PARA COMPARTIR</span></div>
        <section class="ig-grid" aria-label="Publicaciones de ejemplo"><button class="ig-tile" data-post="0" aria-label="Ver publicación: Tu pausa más dulce. Un postre, un café y un momento para ti."><?php echo social_art('brand'); ?><span class="tile-overlay">♡ 128　○ 12</span></button><button class="ig-tile" data-post="1" aria-label="Ver publicación: Un toque de fresa para alegrar tu pausa."><?php echo social_art('cupcake'); ?><span class="tile-overlay">♡ 139　○ 13</span></button><button class="ig-tile" data-post="2" aria-label="Ver publicación: Nuestra compañera de los momentos dulces."><?php echo social_art('mascot'); ?><span class="tile-overlay">♡ 150　○ 14</span></button><button class="ig-tile" data-post="3" aria-label="Ver publicación: Café que acompaña tu postre favorito."><?php echo social_art('coffee'); ?><span class="tile-overlay">♡ 161　○ 15</span></button><button class="ig-tile" data-post="4" aria-label="Ver publicación: Capas de sabor para disfrutar a tu ritmo."><?php echo social_art('dessert'); ?><span class="tile-overlay">♡ 172　○ 16</span></button><button class="ig-tile" data-post="5" aria-label="Ver publicación: ¿Qué se te antoja hoy? Descúbrelo en nuestra tienda."><?php echo social_art('brand'); ?><span class="tile-overlay">♡ 183　○ 17</span></button></section>
        <p class="social-footer">Propuesta de presencia digital · Dulce Tentación · Instagram simulado</p>
    </main>
    <dialog id="social-dialog"><button class="close" aria-label="Cerrar">×</button>
        <div id="social-dialog-content"></div>
    </dialog>
    <div id="social-toast" role="status"></div>
</body>

</html>