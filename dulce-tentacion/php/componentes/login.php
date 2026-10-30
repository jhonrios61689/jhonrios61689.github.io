<dialog id="auth-dialog" class="auth-dialog" closedby="any" aria-labelledby="auth-title">
    <button type="button" class="close-button" data-close aria-label="Cerrar acceso de clientes">×</button>
    <div class="auth-layout">
        <aside class="auth-showcase" aria-label="Beneficios del club Dulce Tentación">
            <div class="auth-showcase-top">
                <span class="auth-badge">✦ Club Dulce Tentación</span>
                <h3>Tu pausa más dulce empieza aquí.</h3>
                <p>Regístrate o inicia sesión con tu correo electrónico para guardar tus datos y finalizar tus pedidos en segundos.</p>
            </div>
            <div class="auth-mascot-card">
                <img src="assets/img/mascota.png" alt="Mascota de Dulce Tentación" width="180" height="120" loading="lazy">
                <div>
                    <strong>Antojos hechos a tu medida</strong>
                    <span>Postres artesanales y café recién preparado</span>
                </div>
            </div>
            <ul class="auth-perks">
                <li><span aria-hidden="true">🧁</span> Guarda tus datos para próximas compras</li>
                <li><span aria-hidden="true">💌</span> Acceso rápido y seguro con tu correo</li>
                <li><span aria-hidden="true">⚡</span> Confirmación inmediata para tu pedido</li>
            </ul>
        </aside>
        <div class="auth-panel">
            <div id="auth-checkout-notice" class="auth-checkout-notice" hidden>
                <span class="notice-icon" aria-hidden="true">🛍️</span>
                <div>
                    <strong>¡Casi listo para finalizar tu compra!</strong>
                    <p>Regístrate con tu correo electrónico (o inicia sesión si ya tienes cuenta) para completar tu pedido.</p>
                </div>
            </div>
            <div id="auth-forms-box">
                <div class="auth-header">
                    <p class="eyebrow" id="auth-eyebrow">Acceso de clientes</p>
                    <h2 id="auth-title">Inicia sesión con tu correo</h2>
                </div>
                <div class="auth-tabs" role="tablist" aria-label="Opciones de acceso">
                    <button type="button" class="auth-tab active" data-auth-tab="login" role="tab" aria-selected="true">Iniciar sesión</button>
                    <button type="button" class="auth-tab" data-auth-tab="register" role="tab" aria-selected="false">Crear cuenta</button>
                </div>
                <form id="login-form" class="auth-form">
                    <div class="auth-field">
                        <label for="login-email">Correo electrónico</label>
                        <div class="input-icon-wrap">
                            <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="5" width="18" height="14" rx="3" />
                                <path d="m3 7 9 6 9-6" />
                            </svg>
                            <input id="login-email" name="email" type="email" inputmode="email" autocomplete="username" spellcheck="false" placeholder="tucorreo@ejemplo.com" enterkeyhint="next" required>
                        </div>
                    </div>
                    <div class="auth-field">
                        <div class="label-row">
                            <label for="current-password">Contraseña</label>
                            <button type="button" class="auth-link-btn" data-forgot-password>¿Olvidaste tu contraseña?</button>
                        </div>
                        <div class="input-icon-wrap">
                            <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="5" y="11" width="14" height="10" rx="2" />
                                <path d="M8 11V7a4 4 0 0 1 8 0v4" />
                            </svg>
                            <input id="current-password" name="password" type="password" autocomplete="current-password" minlength="6" placeholder="Ingresa tu contraseña" enterkeyhint="done" required>
                            <button type="button" class="toggle-password" data-toggle-pass="current-password" aria-label="Mostrar contraseña" aria-pressed="false">Ver</button>
                        </div>
                    </div>
                    <p id="login-error" class="auth-error" role="alert" hidden></p>
                    <button type="submit" class="button primary auth-submit" id="login-submit-btn">Iniciar sesión con mi correo <span aria-hidden="true">→</span></button>
                    <p class="auth-switch-copy">¿Aún no tienes cuenta? <button type="button" class="auth-link-btn" data-auth-tab="register">Regístrate gratis aquí</button></p>
                </form>
                <form id="register-form" class="auth-form" hidden>
                    <div class="auth-field">
                        <label for="register-name">Nombre completo</label>
                        <div class="input-icon-wrap">
                            <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 20c1.8-3.8 5-6 8-6s6.2 2.2 8 6" />
                            </svg>
                            <input id="register-name" name="name" type="text" autocomplete="name" maxlength="80" placeholder="Ej. Valentina Gómez" enterkeyhint="next" required>
                        </div>
                    </div>
                    <div class="auth-field">
                        <label for="register-email">Correo electrónico</label>
                        <div class="input-icon-wrap">
                            <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="5" width="18" height="14" rx="3" />
                                <path d="m3 7 9 6 9-6" />
                            </svg>
                            <input id="register-email" name="email" type="email" inputmode="email" autocomplete="username" spellcheck="false" placeholder="tucorreo@ejemplo.com" enterkeyhint="next" required>
                        </div>
                    </div>
                    <div class="auth-field">
                        <label for="register-phone">WhatsApp o teléfono <span>(opcional)</span></label>
                        <div class="input-icon-wrap">
                            <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7A2 2 0 0 1 22 16.9z" />
                            </svg>
                            <input id="register-phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" maxlength="25" placeholder="Ej. 311 361 7622" enterkeyhint="next">
                        </div>
                    </div>
                    <div class="auth-field">
                        <label for="new-password">Crea una contraseña</label>
                        <div class="input-icon-wrap">
                            <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="5" y="11" width="14" height="10" rx="2" />
                                <path d="M8 11V7a4 4 0 0 1 8 0v4" />
                            </svg>
                            <input id="new-password" name="new-password" type="password" autocomplete="new-password" minlength="6" placeholder="Mínimo 6 caracteres" enterkeyhint="done" required>
                            <button type="button" class="toggle-password" data-toggle-pass="new-password" aria-label="Mostrar contraseña" aria-pressed="false">Ver</button>
                        </div>
                    </div>
                    <p id="register-error" class="auth-error" role="alert" hidden></p>
                    <button type="submit" class="button primary auth-submit" id="register-submit-btn">Crear mi cuenta <span aria-hidden="true">→</span></button>
                    <p class="auth-switch-copy">¿Ya tienes cuenta? <button type="button" class="auth-link-btn" data-auth-tab="login">Inicia sesión aquí</button></p>
                </form>
            </div>
            <div id="account-view" class="account-view" hidden>
                <div class="account-card">
                    <div class="account-avatar" id="account-avatar">DT</div>
                    <p class="eyebrow">Sesión iniciada</p>
                    <h2 id="account-name">Cliente</h2>
                    <p class="account-email" id="account-email"></p>
                    <p class="account-phone" id="account-phone" hidden></p>
                </div>
                <div class="account-actions">
                    <button type="button" class="button primary" data-open-cart>Ir a Mi pedido (<span class="cart-count">0</span>)</button>
                    <button type="button" class="button secondary" id="logout-button">Cerrar sesión</button>
                </div>
            </div>
        </div>
    </div>
</dialog>