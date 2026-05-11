# 🚀 Migration WAMP local → LWS production

Quand tu seras prêt à mettre le site en ligne sur LWS, suivre cette checklist.

## 1. Acheter le domaine + l'hébergement (sur LWS.fr)

- Domaine : `ecurie-du-bodoage.fr` (~10€/an)
- Hébergement : offre **LWS Starter** ou supérieure (~25€/an, PHP inclus)

## 2. Préparer les fichiers à uploader

Avant de transférer, vérifier dans le dossier local :
- ✅ `vendor/` (PHPMailer installé)
- ✅ `composer.json` + `composer.lock`
- ✅ Tous les `.html`, `.php`, `.xml`, `.txt`, `.js`, `.css`
- ✅ `assets/img/` avec logo + futures photos

**À NE PAS uploader :**
- `CLAUDE.md`, `README.md`, `MIGRATION-LWS.md`, `CONFIGURATION-EMAIL.md`
- Fichiers `.git/`, `.DS_Store`

## 3. Upload via FTP (FileZilla)

1. Récupérer les identifiants FTP dans l'espace client LWS
2. Connecter FileZilla
3. Uploader le contenu du dossier dans `/www/` ou `/public_html/`
4. ⚠️ **Vérifier les permissions** : 644 pour les fichiers, 755 pour les dossiers

## 4. Reconfigurer Brevo pour la prod

### 4.1 Mettre à jour `envoi-mail.php`

Remplacer les valeurs de test par les vraies :

```php
$DEST_EMAIL    = 'contact@ecurie-du-bodoage.fr';   // ← email du frère
$DEST_NOM      = 'Écurie du Bodoage';

$EXPEDITEUR_EMAIL = 'no-reply@ecurie-du-bodoage.fr';  // ← @ecurie-du-bodoage.fr
$EXPEDITEUR_NOM   = 'Site Écurie du Bodoage';
```

### 4.2 Valider le nouvel expéditeur dans Brevo

1. Brevo → **Senders, Domains & Dedicated IPs**
2. **Add a sender** : `no-reply@ecurie-du-bodoage.fr`
3. Brevo envoie un mail de validation → cliquer le lien
   - Si l'adresse `no-reply@…` n'existe pas, créer une redirection dans LWS
     (LWS Panel → Email → Créer une adresse email)

### 4.3 Configurer DKIM (anti-spam)

1. Brevo → **Senders, Domains & Dedicated IPs** → onglet **Domains**
2. **Add a domain** : `ecurie-du-bodoage.fr`
3. Brevo affiche des enregistrements DNS à ajouter (TXT ou CNAME)
4. Dans LWS → **Zone DNS** du domaine → Ajouter ces enregistrements
5. Attendre 24h max → vérifier le ✅ Verified dans Brevo

⚠️ Sans DKIM, les mails partent en `@brevosend.com` au lieu de ton domaine
→ ils risquent d'arriver dans les spams.

## 5. Mettre à jour `config.js`

Une fois en ligne, vérifier :
```js
domaine: "https://www.ecurie-du-bodoage.fr",
```

## 6. Tester en prod

1. Ouvrir `https://www.ecurie-du-bodoage.fr/contact.php`
2. Envoyer un message test
3. Vérifier la réception sur le mail du frère
4. Vérifier que l'expéditeur est bien `no-reply@ecurie-du-bodoage.fr`
   (et pas `@brevosend.com`)

## 7. SEO post-mise-en-ligne

- ✅ Activer le **certificat SSL** dans LWS (Let's Encrypt, gratuit)
- ✅ Soumettre `sitemap.xml` dans **Google Search Console**
- ✅ Créer la fiche **Google Business Profile** (priorité absolue)
- ✅ Tester avec **Google Lighthouse** (F12 → Lighthouse) sur chaque page
- ✅ Vérifier l'indexation : taper `site:ecurie-du-bodoage.fr` dans Google après 2-3 jours

## 8. Sauvegarde

- Activer les sauvegardes automatiques dans LWS (souvent inclus)
- Garder une copie locale du site complet
