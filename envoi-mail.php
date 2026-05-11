<?php
/**
 * envoi-mail.php — Écurie du Bodoage
 * ─────────────────────────────────────────────────────────────
 * Reçoit les soumissions du formulaire contact.php
 * Envoie l'email via SMTP Brevo
 *
 * SÉCURITÉ INTÉGRÉE :
 *  ✓ Validation stricte de tous les champs (longueur, format)
 *  ✓ Sanitization (strip_tags + htmlspecialchars)
 *  ✓ Honeypot anti-bot
 *  ✓ Timestamp anti-bot (rejette si soumis en moins de 3 sec)
 *  ✓ Rate limiting (1 envoi par 60 sec par session)
 *  ✓ Whitelist des sujets (anti-injection)
 *  ✓ Détection de spam (trop de liens dans le message)
 *  ✓ Méthode HTTP restreinte à POST
 *
 * MIGRATION LWS (production) : voir MIGRATION-LWS.md
 * ─────────────────────────────────────────────────────────────
 */

session_start();

// ─── CONFIGURATION (à remplir) ─────────────────────────────────
$DEST_EMAIL    = 'ecuriedubodoage@gmail.com';
$DEST_NOM      = 'Écurie du Bodoage';

$BREVO_LOGIN   = 'aa4b9b001@smtp-brevo.com';
$BREVO_KEY     = 'xsmtpsib-dc8c8667e918ba709aca3c3c44db1a0334ebc586217241483bcd2da8144f1f50-kLOnOtXZKHDbrncc';
$BREVO_HOST    = 'smtp-relay.brevo.com';
$BREVO_PORT    = 587;

$EXPEDITEUR_EMAIL = 'ecuriedubodoage@gmail.com';
$EXPEDITEUR_NOM   = 'Site Écurie du Bodoage';
// ───────────────────────────────────────────────────────────────

// Limites de longueur
const MAX_PRENOM   = 50;
const MIN_PRENOM   = 2;
const MAX_NOM      = 50;
const MIN_NOM      = 2;
const MAX_EMAIL    = 100;
const MAX_TEL      = 20;
const MAX_SUJET    = 100;
const MAX_MESSAGE  = 2000;
const MIN_MESSAGE  = 10;

// Whitelist des sujets autorisés
$SUJETS_VALIDES = [
    'Pension / hébergement cheval',
    'Cours / initiation équitation',
    'Tourisme équestre — étape randonneur',
    'Hébergement (tiny house, camping)',
    'Cheval à vendre — demande de visite',
    'Autre demande',
];

// ─── 1. Méthode HTTP : seul POST est accepté ───────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

// ─── 2. Honeypot : champ caché rempli → bot ────────────────────
if (!empty($_POST['website'])) {
    header('Location: contact.php?ok=1'); // simule succès
    exit;
}

// ─── 3. Timestamp : soumis trop vite → bot ─────────────────────
$ts = isset($_POST['ts']) ? (int)$_POST['ts'] : 0;
if ($ts > 0 && (time() - $ts) < 3) {
    header('Location: contact.php?ok=1'); // simule succès
    exit;
}

// ─── 4. Rate limiting : 1 envoi par 60 sec par session ─────────
if (isset($_SESSION['last_send']) && (time() - $_SESSION['last_send']) < 60) {
    header('Location: contact.php?err=rate');
    exit;
}

// ─── 5. Récupération + nettoyage ───────────────────────────────
$prenom  = trim(strip_tags($_POST['prenom']  ?? ''));
$nom     = trim(strip_tags($_POST['nom']     ?? ''));
$email   = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
$tel     = trim(strip_tags($_POST['tel']     ?? ''));
$sujet   = trim(strip_tags($_POST['sujet']   ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

// ─── 6. Champs obligatoires ────────────────────────────────────
if (empty($prenom) || empty($nom) || empty($email) || empty($sujet) || empty($message)) {
    header('Location: contact.php?err=missing');
    exit;
}

// ─── 7. Longueurs ──────────────────────────────────────────────
if (mb_strlen($prenom)  < MIN_PRENOM  || mb_strlen($prenom)  > MAX_PRENOM)  { header('Location: contact.php?err=length'); exit; }
if (mb_strlen($nom)     < MIN_NOM     || mb_strlen($nom)     > MAX_NOM)     { header('Location: contact.php?err=length'); exit; }
if (mb_strlen($email)   > MAX_EMAIL)                                          { header('Location: contact.php?err=length'); exit; }
if (mb_strlen($tel)     > MAX_TEL)                                            { header('Location: contact.php?err=length'); exit; }
if (mb_strlen($sujet)   > MAX_SUJET)                                          { header('Location: contact.php?err=length'); exit; }
if (mb_strlen($message) < MIN_MESSAGE || mb_strlen($message) > MAX_MESSAGE)   { header('Location: contact.php?err=length'); exit; }

// ─── 8. Format prénom / nom ────────────────────────────────────
if (!preg_match("/^[A-Za-zÀ-ÿ\s\-']{2,50}$/u", $prenom)) { header('Location: contact.php?err=name'); exit; }
if (!preg_match("/^[A-Za-zÀ-ÿ\s\-']{2,50}$/u", $nom))    { header('Location: contact.php?err=name'); exit; }

// ─── 9. Email ──────────────────────────────────────────────────
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: contact.php?err=email');
    exit;
}

// ─── 10. Téléphone (optionnel, format international) ───────────
if (!empty($tel)) {
    // Accepte : chiffres, espaces, tirets, points, parenthèses, + en début
    // Et exige au moins 7 chiffres réels (sinon trop court pour être valide)
    $tel_chiffres = preg_replace('/[^0-9]/', '', $tel);
    if (!preg_match('/^[\+]?[\d\s.\-()]{7,20}$/', $tel)
        || strlen($tel_chiffres) < 7
        || strlen($tel_chiffres) > 15) {
        header('Location: contact.php?err=tel');
        exit;
    }
}

// ─── 11. Sujet dans la whitelist ───────────────────────────────
if (!in_array($sujet, $SUJETS_VALIDES, true)) {
    header('Location: contact.php?err=sujet');
    exit;
}

// ─── 12. Anti-spam : trop de liens dans le message ─────────────
$nb_liens = preg_match_all('/(https?:\/\/|www\.)/i', $message);
if ($nb_liens > 2) {
    header('Location: contact.php?ok=1'); // simule succès
    exit;
}

// ─── 13. Composition du mail ───────────────────────────────────
$objet = '[Site] ' . $sujet . ' — ' . $prenom . ' ' . $nom;

$corps = "
<h2>Nouveau message depuis le site</h2>
<table cellpadding='8' style='border-collapse:collapse;font-family:Arial,sans-serif'>
  <tr><td><strong>Nom</strong></td><td>" . htmlspecialchars($prenom . ' ' . $nom, ENT_QUOTES, 'UTF-8') . "</td></tr>
  <tr><td><strong>Email</strong></td><td><a href='mailto:" . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "</a></td></tr>
  <tr><td><strong>Téléphone</strong></td><td>" . htmlspecialchars($tel ?: 'non renseigné', ENT_QUOTES, 'UTF-8') . "</td></tr>
  <tr><td><strong>Sujet</strong></td><td>" . htmlspecialchars($sujet, ENT_QUOTES, 'UTF-8') . "</td></tr>
  <tr><td valign='top'><strong>Message</strong></td><td>" . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . "</td></tr>
  <tr><td><strong>IP</strong></td><td><small>" . htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'inconnue', ENT_QUOTES, 'UTF-8') . "</small></td></tr>
  <tr><td><strong>Date</strong></td><td><small>" . date('d/m/Y H:i:s') . "</small></td></tr>
</table>
<p style='color:#888;font-size:12px;margin-top:20px'>Envoyé depuis ecurie-du-bodoage.fr</p>
";

// ─── 14. ⚠️ ENVOI MAIL DÉSACTIVÉ TEMPORAIREMENT ────────────────
// Le formulaire fonctionne (validation + UX) mais le mail n'est PAS envoyé.
// Pour réactiver : décommenter le bloc PHPMailer ci-dessous.
// ───────────────────────────────────────────────────────────────

// On simule un succès sans envoyer
$_SESSION['last_send'] = time();
header('Location: contact.php?ok=1');
exit;

/* ⛔ BLOC ENVOI DÉSACTIVÉ — RÉACTIVER POUR LA PROD
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = $BREVO_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = $BREVO_LOGIN;
    $mail->Password   = $BREVO_KEY;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $BREVO_PORT;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom($EXPEDITEUR_EMAIL, $EXPEDITEUR_NOM);
    $mail->addAddress($DEST_EMAIL, $DEST_NOM);
    $mail->addReplyTo($email, $prenom . ' ' . $nom);

    $mail->isHTML(true);
    $mail->Subject = $objet;
    $mail->Body    = $corps;
    $mail->AltBody = strip_tags(str_replace(['<br>','</tr>'], "\n", $corps));

    $mail->send();
    $_SESSION['last_send'] = time();
    header('Location: contact.php?ok=1');

} catch (Exception $e) {
    error_log('[Form ecurie-du-bodoage] Erreur SMTP : ' . $mail->ErrorInfo);
    header('Location: contact.php?err=smtp');
}
exit;
*/
