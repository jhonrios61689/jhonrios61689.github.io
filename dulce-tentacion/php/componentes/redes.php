<?php
function social_avatar($class = 'avatar')
{
    return '<img class="' . $class . '" src="assets/img/logo.png" alt="Logo de Dulce Tentación">';
}
function social_art($type)
{
    if ($type === 'brand') return '<div class="art brand-art"><div><small>UN MOMENTO PARA TI</small><h2>Tu pausa<br>más dulce.</h2><p>Postres protagonistas.<br>Café que acompaña.</p></div>' . social_avatar('art-logo') . '</div>';
    if ($type === 'mascot') return '<div class="art mascot-art"><span>Un pequeño antojo.<br>Una gran sonrisa.</span><img src="assets/img/mascota.png" alt="Mascota cupcake con café"></div>';
    return '<div class="art photo-art ' . $type . '"><img src="assets/img/postres-cafe.png" alt="Propuesta visual de postres y café de Dulce Tentación"></div>';
}
