<?php
require_once 'admin_auth.php';
require_once 'db.php';
$pdo = getDB();

// Marquer comme lu
if (isset($_GET['lu'])) {
    $pdo->prepare("UPDATE contacts SET lu=1 WHERE id=:id")->execute([':id'=>(int)$_GET['lu']]);
    header('Location: admin_contacts.php'); exit;
}
// Supprimer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $pdo->prepare("DELETE FROM contacts WHERE id=:id")->execute([':id'=>(int)($_POST['id'] ?? 0)]);
    header('Location: admin_contacts.php'); exit;
}

// Voir le détail
$detail = null;
if (isset($_GET['voir'])) {
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id=:id");
    $stmt->execute([':id'=>(int)$_GET['voir']]);
    $detail = $stmt->fetch();
    if ($detail && !$detail['lu']) {
        $pdo->prepare("UPDATE contacts SET lu=1 WHERE id=:id")->execute([':id'=>$detail['id']]);
    }
}

$contacts = $pdo->query("SELECT * FROM contacts ORDER BY date_envoi DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Messages — Administration</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .msg-detail { background:var(--blanc); border-radius:var(--r); box-shadow:var(--ombre-s); padding:32px 36px; max-width:680px; margin-bottom:32px; }
        .msg-detail h2 { font-family:'Playfair Display',serif; font-size:22px; color:var(--vert); margin-bottom:20px; padding-bottom:14px; border-bottom:1px solid var(--bordure); }
        .msg-meta { display:grid; grid-template-columns:1fr 1fr; gap:10px 24px; margin-bottom:20px; }
        .msg-meta-item label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:var(--vert); display:block; margin-bottom:3px; }
        .msg-meta-item span  { font-size:14px; color:var(--texte-doux); }
        .msg-body { background:var(--vert-pale); border-radius:var(--r); padding:18px 22px; font-size:14.5px; line-height:1.8; color:var(--texte); white-space:pre-wrap; }
    </style>
</head>
<body class="admin-body">

<?php include 'admin_sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-topbar">
        <h1>Messagerie</h1>
        <div class="admin-topbar-actions">
            <?php if ($detail): ?>
                <a href="admin_contacts.php" class="topbar-back">Retour à la liste</a>
            <?php endif; ?>
            <a href="index.php" class="topbar-back">Voir le site</a>
        </div>
    </div>

    <div class="admin-content">

        <?php if ($detail): ?>
        <!-- Détail d'un message -->
        <div class="msg-detail">
            <h2><?= htmlspecialchars($detail['sujet']) ?></h2>
            <div class="msg-meta">
                <div class="msg-meta-item">
                    <label>Expéditeur</label>
                    <span><?= htmlspecialchars($detail['nom']) ?></span>
                </div>
                <div class="msg-meta-item">
                    <label>Adresse e-mail</label>
                    <span><a href="mailto:<?= htmlspecialchars($detail['email']) ?>" style="color:var(--vert);"><?= htmlspecialchars($detail['email']) ?></a></span>
                </div>
                <div class="msg-meta-item">
                    <label>Sujet</label>
                    <span><?= htmlspecialchars($detail['sujet']) ?></span>
                </div>
                <div class="msg-meta-item">
                    <label>Reçu le</label>
                    <span><?= date('d/m/Y à H:i', strtotime($detail['date_envoi'])) ?></span>
                </div>
            </div>
            <div class="msg-body"><?= htmlspecialchars($detail['message']) ?></div>
            <div style="margin-top:20px;display:flex;gap:10px;">
                <a href="mailto:<?= htmlspecialchars($detail['email']) ?>?subject=Re: <?= rawurlencode($detail['sujet']) ?>" class="btn btn-primary">Répondre par e-mail</a>
                <form method="POST" onsubmit="return confirm('Supprimer ce message définitivement ?')">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $detail['id'] ?>">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
                <a href="admin_contacts.php" class="btn" style="background:var(--bordure);color:var(--texte-doux);">Retour</a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Liste -->
        <div class="admin-table-wrap">
            <div class="admin-table-header">
                <h2>Tous les messages (<?= count($contacts) ?>)</h2>
            </div>
            <?php if (empty($contacts)): ?>
                <div class="empty-state"><strong>Aucun message</strong>Les messages envoyés via le formulaire de contact apparaîtront ici.</div>
            <?php else: ?>
            <table>
                <thead>
                    <tr><th>Nom</th><th>Sujet</th><th>Date</th><th>Statut</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $c): ?>
                    <tr style="<?= !$c['lu'] ? 'background:#fafff7;' : '' ?>">
                        <td style="font-weight:<?= !$c['lu'] ? '700' : '400' ?>;color:var(--texte);"><?= htmlspecialchars($c['nom']) ?></td>
                        <td><?= htmlspecialchars($c['sujet']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($c['date_envoi'])) ?></td>
                        <td><?= !$c['lu'] ? '<span class="status-on">Nouveau</span>' : '<span style="font-size:12px;color:var(--texte-doux);">Lu</span>' ?></td>
                        <td>
                            <div class="td-actions">
                                <a href="admin_contacts.php?voir=<?= $c['id'] ?>" class="btn btn-primary btn-sm">Lire</a>
                                <form method="POST" onsubmit="return confirm('Supprimer ce message ?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id"     value="<?= $c['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
