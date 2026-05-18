<?php
require_once 'db.php';
$msg = ''; $type = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nom     = trim($_POST['nom']     ?? '');
    $email   = trim($_POST['email']   ?? '');
    $sujet   = trim($_POST['sujet']   ?? '');
    $message = trim($_POST['message'] ?? '');
    if(!$nom||!$email||!$sujet||!$message){
        $msg = 'Veuillez remplir tous les champs du formulaire.'; $type='error';
    } elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $msg = "L'adresse e-mail saisie n'est pas valide."; $type='error';
    } else {
        try {
            $pdo = getDB();
            $pdo->prepare("INSERT INTO contacts(nom,email,sujet,message) VALUES(:n,:e,:s,:m)")
                ->execute([':n'=>$nom,':e'=>$email,':s'=>$sujet,':m'=>$message]);
            $msg = 'Votre message a bien été transmis à la mairie.'; $type='success';
            $nom=$email=$sujet=$message='';
        } catch(PDOException $e){ $msg='Une erreur est survenue. Veuillez réessayer.'; $type='error'; }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Contact — Mairie de Francilly-Selency</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header><?php include 'header.php'; ?></header>
<div class="page-banner">
    <p class="eyebrow">Mairie de Francilly-Selency</p>
    <h1>Nous contacter</h1>
    <p>Une question, un signalement, une demande ? La mairie vous répond dans les meilleurs délais.</p>
</div>
<hr class="divider">

<div class="contact-layout">
    <div class="contact-info">
        <h2 class="section-title">Informations pratiques</h2>
        <div class="info-block"><div class="info-label">Adresse</div><p>6 Grande Rue<br>02760 Francilly-Selency</p></div>
        <div class="info-block"><div class="info-label">Téléphone</div><p>03 23 09 60 03</p></div>
        <div class="info-block"><div class="info-label">Courriel</div><p>mairie.de.francilly@wanadoo.fr</p></div>
        <div class="info-block"><div class="info-label">Horaires d'ouverture</div><p>Lundi &amp; Mardi : 16h30 – 18h30<br>Vendredi : 16h30 – 18h30<br>Autres jours : fermé</p></div>
        <div class="info-block"><div class="info-label">Accès</div><p>À 5 minutes de Saint-Quentin<br>Parking disponible devant la mairie</p></div>
    </div>
    <div class="contact-form-wrap">
        <h2 class="section-title">Envoyer un message</h2>
        <?php if($msg): ?><div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom complet</label>
                    <input type="text" id="nom" name="nom" placeholder="Jean Dupont" value="<?= htmlspecialchars($nom??'') ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email" placeholder="jean@exemple.fr" value="<?= htmlspecialchars($email??'') ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="sujet">Sujet</label>
                <select id="sujet" name="sujet" required>
                    <option value="" disabled <?= empty($sujet??'')?'selected':'' ?>>Choisir un sujet</option>
                    <?php foreach(["Demande d'information","État civil","Urbanisme","Voirie / signalement","Associations","Autre"] as $opt): ?>
                    <option value="<?= $opt ?>" <?= ($sujet??'')===$opt?'selected':'' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" placeholder="Décrivez votre demande..." required><?= htmlspecialchars($message??'') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer le message</button>
        </form>
    </div>
</div>
<hr class="divider">
<?php include 'footer.php'; ?>
</body>
</html>
