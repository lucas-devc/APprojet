<?php
require_once 'db.php';
$pdo      = getDB();
$services = $pdo->query("SELECT * FROM services WHERE actif=1 ORDER BY categorie,nom")->fetchAll();
$par_cat  = [];
foreach($services as $s) $par_cat[$s['categorie']][] = $s;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Services — Mairie de Francilly-Selency</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header><?php include 'header.php'; ?></header>

<div class="page-banner">
    <p class="eyebrow">Mairie de Francilly-Selency</p>
    <h1>Services municipaux</h1>
    <p>L'ensemble des services proposés par la commune à ses habitants.</p>
</div>
<hr class="divider">

<section class="services-section">
    <?php if(empty($services)): ?>
        <div class="empty-state"><strong>Aucun service</strong>Les services seront disponibles prochainement.</div>
    <?php else: ?>
        <?php foreach($par_cat as $cat => $liste): ?>
            <div class="section-header" style="margin-bottom:26px;margin-top:12px;">
                <h2><?= htmlspecialchars($cat) ?></h2>
                <span class="badge"><?= count($liste) ?></span>
            </div>
            <div class="services-grid">
                <?php foreach($liste as $s): ?>
                <div class="card-service">
                    <div class="service-cat"><?= htmlspecialchars($s['categorie']) ?></div>
                    <h3><?= htmlspecialchars($s['nom']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($s['description'])) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<hr class="divider">
<?php include 'footer.php'; ?>
</body>
</html>
