<?php
require_once 'admin_auth.php';
require_once 'db.php';
$pdo = getDB();

$nb_articles  = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$nb_visibles  = $pdo->query("SELECT COUNT(*) FROM articles WHERE visible=1")->fetchColumn();
$nb_services  = $pdo->query("SELECT COUNT(*) FROM services  WHERE actif=1")->fetchColumn();
$nb_contacts  = $pdo->query("SELECT COUNT(*) FROM contacts  WHERE lu=0")->fetchColumn();

$derniers_contacts = $pdo->query(
    "SELECT nom, sujet, date_envoi, lu FROM contacts ORDER BY date_envoi DESC LIMIT 5"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Administration — Mairie de Francilly-Selency</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="admin-body">

<?php include 'admin_sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-topbar">
        <h1>Tableau de bord</h1>
        <div class="admin-topbar-actions">
            <a href="index.php" class="topbar-back">Voir le site</a>
            <a href="admin_logout.php" class="btn btn-danger btn-sm">Déconnexion</a>
        </div>
    </div>
    <div class="admin-content">

        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-val"><?= $nb_articles ?></div>
                <div class="stat-label">Articles au total</div>
            </div>
            <div class="stat-card">
                <div class="stat-val"><?= $nb_services ?></div>
                <div class="stat-label">Services actifs</div>
            </div>
            <div class="stat-card" style="border-left-color:var(--or);">
                <div class="stat-val"><?= $nb_contacts ?></div>
                <div class="stat-label">Messages non lus</div>
            </div>
        </div>

        <div class="admin-table-wrap">
            <div class="admin-table-header">
                <h2>Derniers messages reçus</h2>
                <a href="admin_contacts.php" class="btn btn-primary btn-sm">Voir tous les messages</a>
            </div>
            <?php if (empty($derniers_contacts)): ?>
                <div class="empty-state"><strong>Aucun message</strong>Les messages envoyés via le formulaire apparaîtront ici.</div>
            <?php else: ?>
            <table>
                <thead>
                    <tr><th>Nom</th><th>Sujet</th><th>Date</th><th>Statut</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($derniers_contacts as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['nom']) ?></td>
                        <td><?= htmlspecialchars($c['sujet']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($c['date_envoi'])) ?></td>
                        <td>
                            <?php if (!$c['lu']): ?>
                                <span class="status-on">Nouveau</span>
                            <?php else: ?>
                                <span class="status-off">Lu</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <a href="admin_articles.php" class="stat-card" style="text-decoration:none;display:block;cursor:pointer;transition:box-shadow var(--tr);">
                <div style="font-family:'Playfair Display',serif;font-size:18px;color:var(--vert);margin-bottom:6px;">Gérer les articles</div>
                <div style="font-size:13px;color:var(--texte-doux);"><?= $nb_visibles ?> article<?= $nb_visibles > 1 ? 's' : '' ?> publié<?= $nb_visibles > 1 ? 's' : '' ?> actuellement</div>
            </a>
            <a href="admin_services.php" class="stat-card" style="text-decoration:none;display:block;cursor:pointer;transition:box-shadow var(--tr);">
                <div style="font-family:'Playfair Display',serif;font-size:18px;color:var(--vert);margin-bottom:6px;">Gérer les services</div>
                <div style="font-size:13px;color:var(--texte-doux);"><?= $nb_services ?> service<?= $nb_services > 1 ? 's' : '' ?> actif<?= $nb_services > 1 ? 's' : '' ?> actuellement</div>
            </a>
        </div>

    </div>
</div>
</body>
</html>
