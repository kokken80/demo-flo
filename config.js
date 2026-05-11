/**
 * config.js — Écurie du Bodoage
 * ─────────────────────────────────────────────────────────────
 * FICHIER CENTRAL DE CONFIGURATION
 * Modifiez les valeurs ci-dessous, elles s'injectent automatiquement
 * dans toutes les pages via les attributs data-config="clé".
 * ─────────────────────────────────────────────────────────────
 */

const SITE = {

  /* ── Identité ─────────────────────────────────────── */
  nom:       "Écurie du Bodoage",
  slogan:    "L'équitation au cœur de la nature picarde",
  adresse:   "Lieu-dit Le Bodage",
  ville:     "Vron",
  cp:        "80120",
  dept:      "Somme",
  region:    "Hauts-de-France",
  tel:       "06 20 13 07 94",
  email:     "ecuriedubodoage@gmail.com",
  siret:     "XXX XXX XXX XXXXX",
  domaine:   "https://www.ecurie-du-bodoage.fr",

  /* ── Réseaux sociaux ──────────────────────────────── */
  facebook:  "https://www.facebook.com/ecuriedubodoage80/",
  instagram: "https://www.instagram.com/ecuriedubodoage/",

  /* ── Annonces externes (chevaux à vendre) ─────────── */
  facebook_url:  "https://www.facebook.com/ecuriedubodoage80/",
  chevalannonce: "https://www.chevalannonce.com/ca/ecuriedubodoage/annonces",
  leboncoin: "https://www.leboncoin.fr/profil/...",   // ← URL profil LBC à compléter

  /* ── Horaires ─────────────────────────────────────── */
  horaires: {
    semaine: "8h – 19h",
    samedi:  "8h – 18h",
    dimanche:"Sur rendez-vous",
  },

  /* ── Tarifs Pension (source : page Facebook officielle) ── */
  pension: {
    pre_box:           "250 €/mois",  // 6 mois pré / 6 mois box (places limitées)
    box_1_sortie:      "300 €/mois",
    box_3_sorties:     "320 €/mois",
    box_5_sorties:     "340 €/mois",
  },

  /* ── Tarifs Cours ─────────────────────────────────── */
  cours: {
    carte_10_seances:     "250 €",
    cours_avec_pension:   "27 €/séance",
    cours_exterieur:      "30 €/séance",
  },

  /* ── Tarifs Marcheur ──────────────────────────────── */
  marcheur: {
    forfait_mois:           "60 €/mois",
    sortie_pensionnaire:    "3,50 €",
    sortie_non_pensionnaire:"7 €",
  },

  /* ── Tarifs Location de structure ─────────────────── */
  location: {
    carriere:  "14 €/h",
    manege:    "18 €/h",
  },

  /* ── Tarifs Tourisme équestre ─────────────────────── */
  equestre: {
    box_nuit:          "XX €/nuit",
    paddock_nuit:      "XX €/nuit",
    formule_complete:  "XX €/nuit",
  },

  /* ── Tarifs Hébergement ───────────────────────────── */
  hebergement: {
    tiny_house:        "XX €/nuit",
    camping_car:       "XX €/nuit",
    tente:             "XX €/nuit",
    formule_balade_dej:"XX €/pers.",
  },

  /* ── Chiffres clés (page Accueil) ─────────────────── */
  chiffres: {
    boxes:      "28",       // capacité totale (box + paddock)
    experience: "XX",
    chevaux:    "XX",
    sentiers:   "XX km",
  },

};

if (typeof module !== "undefined") module.exports = SITE;
