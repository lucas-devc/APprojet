<?php
require_once 'db.php';
$pdo      = getDB();
$articles = $pdo->query("SELECT * FROM articles WHERE visible=1 ORDER BY date_pub DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Mairie de Francilly-Selency</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header><?php include 'header.php'; ?></header>

<section class="hero">
    <img src="img/farm-field.png" alt="Paysage agricole de Francilly-Selency">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <span class="hero-eyebrow">Commune de l'Aisne &mdash; 02760</span>
        <h1>Francilly-Selency</h1>
        <p>Village du Vermandois fondé en 1883, entre mémoire, patrimoine et vie rurale.</p>
    </div>
</section>
<hr class="divider">

<section class="section-intro">
    <div class="intro-col">
        <h2 class="section-title">Notre commune</h2>
        <p>Contrairement aux autres communes du Vermandois nées en 1789, Francilly-Selency a été créée en 1883 à partir des hameaux de Francilly et Selency, détachés de Fayet. Le territoire fut marqué dès avant sa fondation par la guerre de 1870 — un monument rappelle les mobiles français tombés les 18 et 19 janvier 1871.</p>
        <p>La Grande Guerre y causa des ravages lors de l'Offensive du Printemps du 21 mars 1918. L'église Sainte-Thérèse-de-l'Enfant-Jésus, détruite pendant le conflit, fut rebâtie et constitue aujourd'hui le cœur patrimonial du village.</p>
    </div>
    <div class="intro-col">
        <h2 class="section-title">Chiffres clés</h2>
        <div class="chiffres-grid">
            <div class="chiffre-item"><div class="label">Code postal</div><div class="valeur">02760</div></div>
            <div class="chiffre-item"><div class="label">Code INSEE</div><div class="valeur">02330</div></div>
            <div class="chiffre-item"><div class="label">Superficie</div><div class="valeur">5,43 km²</div></div>
            <div class="chiffre-item"><div class="label">Altitude</div><div class="valeur">86 – 128 m</div></div>
            <div class="chiffre-item"><div class="label">Population</div><div class="valeur">~450–500 hab.</div></div>
            <div class="chiffre-item"><div class="label">Création</div><div class="valeur">1883</div></div>
            <div class="chiffre-item"><div class="label">Département</div><div class="valeur">Aisne (02)</div></div>
            <div class="chiffre-item"><div class="label">Habitants</div><div class="valeur">Francillois</div></div>
        </div>
    </div>
</section>
<hr class="divider">

<section class="section-actu">
    <div class="section-header">
        <h2>Actualités de la mairie</h2>
        <?php if(count($articles)): ?><span class="badge"><?= count($articles) ?> article<?= count($articles)>1?'s':'' ?></span><?php endif; ?>
    </div>
    <?php if(empty($articles)): ?>
        <div class="empty-state"><strong>Aucune actualité</strong>Les articles publiés par la mairie apparaîtront ici.</div>
    <?php else: ?>
        <div class="actu-grid">
            <?php foreach($articles as $a): ?>
            <article class="card-actu">
                <div class="card-actu-head">
                    <span class="card-cat"><?= htmlspecialchars($a['categorie']) ?></span>
                    <h3><?= htmlspecialchars($a['titre']) ?></h3>
                </div>
                <div class="card-actu-body"><p><?= nl2br(htmlspecialchars($a['contenu'])) ?></p></div>
                <div class="card-actu-foot"><?= date('d/m/Y', strtotime($a['date_pub'])) ?></div>
            </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<hr class="divider">
<?php include 'footer.php'; ?>
</body>
</html>
