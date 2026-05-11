<?php
// Démarre la session pour permettre le rate limiting + token timestamp
if (session_status() === PHP_SESSION_NONE) session_start();

// Messages d'erreur lisibles
$err_messages = [
    'missing' => 'Veuillez remplir tous les champs obligatoires.',
    'email'   => 'L\'adresse email saisie n\'est pas valide.',
    'tel'     => 'Le numéro de téléphone n\'est pas valide (7 à 15 chiffres requis).',
    'name'    => 'Le prénom ou le nom contient des caractères non autorisés.',
    'length'  => 'Un des champs est trop long ou trop court.',
    'sujet'   => 'Le sujet sélectionné n\'est pas valide.',
    'rate'    => 'Vous avez déjà envoyé un message récemment. Merci de patienter une minute.',
    'smtp'    => 'Une erreur technique est survenue. Merci de réessayer ou de nous contacter par téléphone.',
];
$err_code = $_GET['err'] ?? null;
$err_text = $err_messages[$err_code] ?? 'Une erreur est survenue. Veuillez réessayer.';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact — Écurie du Bodoage, Vron (Somme 80)</title>
  <meta name="description" content="Contactez l'Écurie du Bodoage à Vron (Somme 80) : téléphone, email, formulaire de contact. Réservation pension, cours, étape équestre et hébergement insolite." />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="https://www.ecurie-du-bodoage.fr/contact.php" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="Contact — Écurie du Bodoage, Vron" />
  <meta property="og:description" content="Coordonnées et formulaire de contact de l'Écurie du Bodoage à Vron." />
  <meta property="og:image" content="https://www.ecurie-du-bodoage.fr/assets/img/og-contact.jpg" />
  <script type="application/ld+json">{"@context":"https://schema.org","@type":"ContactPage","name":"Contact — Écurie du Bodoage","url":"https://www.ecurie-du-bodoage.fr/contact.php","mainEntity":{"@id":"https://www.ecurie-du-bodoage.fr/#ecuriedubodage"}}</script>
  <link rel="preconnect" href="https://fonts.googleapis.com" /><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
  <header role="banner">
    <nav class="site-nav" aria-label="Navigation principale">
      <a href="index.html" class="site-nav__logo" aria-label="Écurie du Bodoage — Accueil">
        <img src="assets/img/logo.png" alt="Logo Écurie du Bodoage" class="site-nav__logo-img" />
        <span class="site-nav__logo-text">Écurie du Bodoage</span>
      </a>
      <ul class="site-nav__menu" role="list">
        <li><a href="index.html">Accueil</a></li>
        <li><a href="pension-elevage.html">Pension</a></li>
        <li><a href="cours-initiations.html">Cours &amp; Initiations</a></li>
        <li><a href="tourisme-equestre.html">Tourisme équestre</a></li>
        <li><a href="hebergement.html">Hébergement</a></li>
        <li><a href="chevaux-a-vendre.html">Nos chevaux</a></li>
      </ul>
      <a href="contact.php" class="site-nav__cta" aria-current="page">Nous contacter</a>
      <button class="site-nav__toggle" aria-expanded="false" aria-label="Ouvrir le menu"><span></span><span></span><span></span></button>
    </nav>
  </header>
  <nav class="breadcrumb" aria-label="Fil d'Ariane">
    <a href="index.html">Accueil</a><span class="breadcrumb__sep" aria-hidden="true">›</span>
    <span aria-current="page">Contact</span>
  </nav>
  <main id="main-content">
    <section class="hero hero--page" aria-labelledby="contact-title"
             style="background: linear-gradient(135deg,#1f2335,#2d3142); padding: 56px 5%; min-height: auto;">
      <p class="hero__eyebrow">Nous joindre</p>
      <h1 id="contact-title">Contactez-nous</h1>
      <p class="hero__sub">Une question, une réservation ou une visite de cheval ? Nous répondons dans la journée.</p>
    </section>

    <section class="section section--white" aria-labelledby="contact-info-title">
      <div class="container">
        <div class="grid--2" style="gap:60px">
          <div>
            <span class="label">Nos coordonnées</span>
            <h2 id="contact-info-title">Venez nous rendre visite</h2>
            <div class="contact-info" style="margin-top:1.5rem">
              <div class="contact-info__item">
                <div class="contact-info__icon" aria-hidden="true">📍</div>
                <div><span class="contact-info__label">Adresse</span><div class="contact-info__value"><span data-config="adresse"></span><br><span data-config="cp"></span> <span data-config="ville"></span> — Somme</div></div>
              </div>
              <div class="contact-info__item">
                <div class="contact-info__icon" aria-hidden="true">📞</div>
                <div><span class="contact-info__label">Téléphone</span><div class="contact-info__value"><a href="tel:+33620130794" data-config="tel">06 20 13 07 94</a></div></div>
              </div>
              <div class="contact-info__item">
                <div class="contact-info__icon" aria-hidden="true">✉️</div>
                <div><span class="contact-info__label">Email</span><div class="contact-info__value"><a href="mailto:ecuriedubodoage@gmail.com" data-config="email">ecuriedubodoage@gmail.com</a></div></div>
              </div>
              <div class="contact-info__item">
                <div class="contact-info__icon" aria-hidden="true">🕐</div>
                <div><span class="contact-info__label">Horaires</span><div class="contact-info__value">Lun–Ven : <span data-config="horaires_semaine"></span><br>Samedi : <span data-config="horaires_samedi"></span><br>Dimanche : <span data-config="horaires_dimanche"></span></div></div>
              </div>
              <div class="contact-info__item">
                <div class="contact-info__icon" aria-hidden="true">📘</div>
                <div><span class="contact-info__label">Réseaux sociaux</span><div class="contact-info__value"><a href="#" data-href="facebook" target="_blank" rel="noopener noreferrer" aria-label="Suivez-nous sur Facebook" class="social-icon"><img src="assets/img/facebook.svg" alt="" /> Facebook</a> · <a href="#" data-href="instagram" target="_blank" rel="noopener noreferrer" aria-label="Suivez-nous sur Instagram" class="social-icon"><img src="assets/img/instagram.svg" alt="" /> Instagram</a></div></div>
              </div>
            </div>
          <div class="map-placeholder" style="height:auto;padding:0;background:none;text-transform:none;color:inherit;letter-spacing:0;font-size:1rem">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2563.0!2d1.747!3d50.310!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zVnJvbg!5e0!3m2!1sfr!2sfr!4v1700000000000"
              width="100%" height="280" style="border:0;border-radius:var(--radius);display:block"
              allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
              title="Carte Google Maps — Écurie du Bodoage, Vron"></iframe>
          </div>
        </div>
        <div class="contact-form">
          <h2 class="contact-form__title">Envoyer un message</h2>

          <!-- Message succès / erreur (affiché après soumission via PHP) -->
          <?php if (isset($_GET['ok'])): ?>
            <div style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:14px;border-radius:4px;margin-bottom:1rem">
              ✅ Votre message a bien été envoyé. Nous vous répondrons rapidement.
            </div>
          <?php elseif (isset($_GET['err'])): ?>
            <div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:14px;border-radius:4px;margin-bottom:1rem">
              ❌ <?php echo htmlspecialchars($err_text, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <form action="envoi-mail.php" method="post" novalidate>
            <div class="form-row">
              <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom"
                       required minlength="2" maxlength="50"
                       pattern="[A-Za-zÀ-ÿ\s\-']{2,50}"
                       title="2 à 50 lettres, espaces, tirets ou apostrophes"
                       autocomplete="given-name">
              </div>
              <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom"
                       required minlength="2" maxlength="50"
                       pattern="[A-Za-zÀ-ÿ\s\-']{2,50}"
                       title="2 à 50 lettres, espaces, tirets ou apostrophes"
                       autocomplete="family-name">
              </div>
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email"
                     required maxlength="100"
                     pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}"
                     title="Adresse email valide"
                     autocomplete="email">
            </div>

            <div class="form-group">
              <label for="tel">Téléphone (optionnel)</label>
              <input type="tel" id="tel" name="tel"
                     maxlength="20"
                     pattern="^[\+]?[\d\s.\-()]{7,20}$"
                     title="Numéro de téléphone (français ou international, ex : 06 12 34 56 78 ou +44 20 7946 0958)"
                     placeholder="06 XX XX XX XX ou +XX ..."
                     autocomplete="tel">
            </div>

            <div class="form-group">
              <label for="sujet">Sujet</label>
              <select id="sujet" name="sujet" required>
                <option value="">— Choisir —</option>
                <option>Pension / hébergement cheval</option>
                <option>Cours / initiation équitation</option>
                <option>Tourisme équestre — étape randonneur</option>
                <option>Hébergement (tiny house, camping)</option>
                <option>Cheval à vendre — demande de visite</option>
                <option>Autre demande</option>
              </select>
            </div>

            <div class="form-group">
              <label for="message">Message <span style="color:var(--color-text2);font-weight:400;text-transform:none;letter-spacing:0">(<span id="msg-count">0</span> / 2000 caractères)</span></label>
              <textarea id="message" name="message"
                        required minlength="10" maxlength="2000"
                        placeholder="Votre message (10 caractères minimum)"></textarea>
            </div>

            <!-- Anti-spam : honeypot (invisible aux humains, piège les bots) -->
            <div style="position:absolute;left:-9999px" aria-hidden="true">
              <label for="website">Ne pas remplir</label>
              <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
            </div>

            <!-- Anti-spam : timestamp (un humain met >3 sec à remplir) -->
            <input type="hidden" name="ts" value="<?php echo time(); ?>">

            <!-- Mention RGPD -->
            <p style="font-size:.78rem;color:var(--color-text2);line-height:1.5;margin-bottom:1rem;padding:10px 14px;background:rgba(216,36,109,.05);border-left:3px solid var(--color-brand);border-radius:3px">
              🔒 Les informations recueillies via ce formulaire sont uniquement transmises par mail à l'Écurie du Bodoage et utilisées pour répondre efficacement à votre demande. Elles ne sont jamais transmises à des tiers.
            </p>

            <button type="submit" class="btn btn--brown">Envoyer le message</button>
          </form>

          <script>
            // Compteur de caractères en temps réel
            (function(){
              var t = document.getElementById('message');
              var c = document.getElementById('msg-count');
              if (t && c) t.addEventListener('input', function(){ c.textContent = t.value.length; });
            })();
          </script>
        </div>
        </div>
      </div>
    </section>
  </main>
  <footer class="site-footer" role="contentinfo">
    <div class="site-footer__grid">
      <div><a href="index.html" class="site-footer__logo"><img src="assets/img/logo.png" alt="Logo Écurie du Bodoage" class="site-footer__logo-img" /></a><p class="site-footer__desc">Pension, cours, tourisme équestre et hébergement insolite à Vron (80).</p><div class="site-footer__social"><a href="#" data-href="facebook" target="_blank" rel="noopener noreferrer" aria-label="Suivez-nous sur Facebook" class="social-icon"><img src="assets/img/facebook.svg" alt="" /> Facebook</a><a href="#" data-href="instagram" target="_blank" rel="noopener noreferrer" aria-label="Suivez-nous sur Instagram" class="social-icon"><img src="assets/img/instagram.svg" alt="" /> Instagram</a></div></div>
      <div class="site-footer__col"><h4>Activités</h4><ul><li><a href="pension-elevage.html">Pension</a></li><li><a href="cours-initiations.html">Cours &amp; Initiations</a></li><li><a href="tourisme-equestre.html">Tourisme équestre</a></li><li><a href="hebergement.html">Hébergement</a></li><li><a href="chevaux-a-vendre.html">Nos chevaux</a></li></ul></div>
      <div class="site-footer__col"><h4>Informations</h4><ul><li><a href="index.html">Accueil</a></li><li><a href="contact.php">Contact</a></li><li><a href="mentions-legales.html">Mentions légales</a></li></ul></div>
    </div>
    <div class="site-footer__bottom"><span>© 2026 Écurie du Bodoage · Vron, Somme · Tous droits réservés</span></div>
  </footer>
  <script src="config.js"></script><script src="assets/js/main.js"></script>
</body>
</html>
