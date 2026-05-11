/**
 * main.js — Écurie du Bodoage
 * ─────────────────────────────────────────────────────────────
 * Fonctionnalités :
 *  1. Menu mobile (hamburger toggle)
 *  2. Active state lien nav courant
 *  3. Injection des données config.js dans le DOM
 * ─────────────────────────────────────────────────────────────
 */

/* ── 1. Menu mobile ───────────────────────────────────────── */
(function () {
  const toggle = document.querySelector('.site-nav__toggle');
  const menu   = document.querySelector('.site-nav__menu');
  if (!toggle || !menu) return;

  toggle.addEventListener('click', function () {
    const isOpen = menu.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', isOpen);
    toggle.setAttribute('aria-label', isOpen ? 'Fermer le menu' : 'Ouvrir le menu');
  });

  // Fermer si clic en dehors
  document.addEventListener('click', function (e) {
    if (!toggle.contains(e.target) && !menu.contains(e.target)) {
      menu.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
    }
  });
})();


/* ── 2. Active state lien nav ─────────────────────────────── */
(function () {
  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.site-nav__menu a').forEach(function (link) {
    const href = link.getAttribute('href');
    if (href === currentPage) {
      link.setAttribute('aria-current', 'page');
    }
  });
})();


/* ── 3. Injection config.js → DOM ─────────────────────────── */
/*
 * Si config.js est chargé avant main.js, on injecte automatiquement
 * les données dans les éléments portant un attribut [data-config="clé"].
 *
 * Exemple HTML : <span data-config="tel"></span>
 * Résultat     : <span data-config="tel">XX XX XX XX XX</span>
 *
 * Clés disponibles (cf config.js) :
 *   tel, email, adresse, ville, cp, nom, slogan
 */
(function () {
  if (typeof SITE === 'undefined') return;

  // Données plates injectables
  var flat = {
    tel:      SITE.tel,
    email:    SITE.email,
    adresse:  SITE.adresse,
    ville:    SITE.ville,
    cp:       SITE.cp,
    nom:      SITE.nom,
    slogan:   SITE.slogan,
    facebook: SITE.facebook,
    instagram:SITE.instagram,
    horaires_semaine: SITE.horaires.semaine,
    horaires_samedi:  SITE.horaires.samedi,
    horaires_dimanche:SITE.horaires.dimanche,
    // Pension
    pension_box:           SITE.pension.box,
    pension_box_paddock:   SITE.pension.box_paddock,
    pension_paddock_paradise: SITE.pension.paddock_paradise,
    pension_box_ponctuel:  SITE.pension.box_ponctuel,
    pension_soins:         SITE.pension.soins,
    // Cours
    cours_initiation: SITE.cours.initiation_enfant,
    cours_individuel: SITE.cours.individuel,
    cours_collectif:  SITE.cours.collectif,
    cours_balade:     SITE.cours.balade,
    // Equestre
    equestre_box:      SITE.equestre.box_nuit,
    equestre_paddock:  SITE.equestre.paddock_nuit,
    equestre_formule:  SITE.equestre.formule_complete,
    // Hébergement
    heberg_tiny:     SITE.hebergement.tiny_house,
    heberg_cc:       SITE.hebergement.camping_car,
    heberg_tente:    SITE.hebergement.tente,
    heberg_balade_dej: SITE.hebergement.formule_balade_dej,
    // Chiffres
    stat_boxes:      SITE.chiffres.boxes,
    stat_experience: SITE.chiffres.experience,
    stat_chevaux:    SITE.chiffres.chevaux,
    stat_sentiers:   SITE.chiffres.sentiers,
  };

  document.querySelectorAll('[data-config]').forEach(function (el) {
    var key = el.getAttribute('data-config');
    if (flat[key] !== undefined) {
      el.textContent = flat[key];
    }
  });

  // Liens href dynamiques
  var fbLinks = document.querySelectorAll('[data-href="facebook"]');
  fbLinks.forEach(function(el){ el.setAttribute('href', SITE.facebook); });

  var igLinks = document.querySelectorAll('[data-href="instagram"]');
  igLinks.forEach(function(el){ el.setAttribute('href', SITE.instagram); });

  // Liens externes configurables via data-href-config
  document.querySelectorAll('[data-href-config]').forEach(function (el) {
    var key = el.getAttribute('data-href-config');
    if (SITE[key]) el.setAttribute('href', SITE[key]);
  });
})();
