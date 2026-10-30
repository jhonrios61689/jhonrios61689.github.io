<dialog id="cart-dialog" class="cart-dialog" aria-labelledby="cart-title">
  <div class="dialog-heading">
    <div>
      <p class="eyebrow">Tu pausa está tomando forma</p>
      <h2 id="cart-title">Mi pedido</h2>
    </div>
    <button class="close-button" data-close aria-label="Cerrar pedido">×</button>
  </div>

  <div id="cart-content"></div>

  <form id="whatsapp-form" class="order-form" hidden>
    <h3>Datos para tu solicitud</h3>

    <label for="order-name">Tu nombre <span>(opcional)</span></label>
    <input id="order-name" name="name" autocomplete="name" maxlength="80">

    <label for="order-method">¿Cómo prefieres recibirlo?</label>
    <select id="order-method" name="method">
      <option value="confirmar">Por acordar con el negocio</option>
      <option value="recogida">Prefiero recogerlo</option>
      <option value="domicilio">Prefiero domicilio</option>
    </select>

    <label for="order-location">Municipio, barrio y dirección o referencia <span id="location-optional">(opcional)</span></label>
    <textarea id="order-location" name="location" rows="3" maxlength="300" autocomplete="street-address" placeholder="Escribe dónde deseas recibir el pedido" aria-describedby="location-help"></textarea>
    <p id="location-help" class="form-note">Si prefieres domicilio, indica la ubicación. El negocio confirmará cobertura y costo de entrega.</p>

    <label for="order-notes">Indicaciones adicionales <span>(opcional)</span></label>
    <textarea id="order-notes" name="notes" rows="2" maxlength="500" placeholder="Por ejemplo, una referencia para encontrar el lugar"></textarea>

    <button type="submit" class="button whatsapp-button">Solicitar pedido por WhatsApp</button>
    <p class="form-note">Se abrirá WhatsApp con tu selección y los datos que escribas. Revisa y envía el mensaje para solicitar confirmación de precios, disponibilidad y entrega.</p>
  </form>
</dialog>
