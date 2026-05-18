<?php
require_once 'admin_auth.php';
require_once 'db.php';
$pdo = getDB();

$msg = ''; $type = '';
$edit = null;

// ── Actions POST ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action  = $_POST['action']  ?? '';
    $titre   = trim($_POST['titre']    ?? '');
    $contenu = trim($_POST['contenu']  ?? '');
    $cat     = trim($_POST['categorie'] ?? 'Général');
    $visible = isset($_POST['visible']) ? 1 : 0;

    if ($action === 'add') {
        if (!$titre || !$contenu) {
            $msg = 'Titre et contenu sont obligatoires.'; $type = 'error';
        } else {
            $pdo->prepare("INSERT INTO articles (titre,contenu,categorie,visible) VALUES(:t,:c,:ca,:v)")
                ->execute([':t'=>$titre, ':c'=>$contenu, ':ca'=>$cat, ':v'=>$visible]);
            $msg = 'Article publié avec succès.'; $type = 'success';
        }

    } elseif ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$titre || !$contenu || !$id) {
            $msg = 'Données invalides.'; $type = 'error';
        } else {
            $pdo->prepare("UPDATE articles SET titre=:t, contenu=:c, categorie=:ca, visible=:v WHERE id=:id")
                ->execute([':t'=>$titre, ':c'=>$contenu, ':ca'=>$cat, ':v'=>$visible, ':id'=>$id]);
            $msg = 'Article modifié avec succès.'; $type = 'success';
        }

    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $pdo->prepare("DELETE FROM articles WHERE id=:id")->execute([':id'=>$id]);
            $msg = 'Article supprimé.'; $type = 'success';
        }

    } elseif ($action === 'toggle') {
        $id  = (int)($_POST['id'] ?? 0);
        $val = (int)($_POST['val'] ?? 0);
        if ($id) {
            $pdo->prepare("UPDATE articles SET visible=:v WHERE id=:id")->execute([':v'=>$val, ':id'=>$id]);
        }
        header('Location: admin_articles.php'); exit;
    }
}

// ── Chargement pour édition ───────────────────────────────
if (isset($_GET['edit'])) {
    $edit = $pdo->prepare("SELECT * FROM articles WHERE id=:id");
    $edit->execute([':id'=>(int)$_GET['edit']]);
    $edit = $edit->fetch();
}

$articles = $pdo->query("SELECT * FROM articles ORDER BY date_pub DESC")->fetchAll();

$categories = ['Général','Vie municipale','Travaux','Événements','Environnement','École','Urbanisme'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Articles — Administration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="admin-body">

<?php include 'admin_sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-topbar">
        <h1><?= $edit ? 'Modifier un article' : 'Gestion des articles' ?></h1>
        <div class="admin-topbar-actions">
            <?php if ($edit): ?>
                <a href="admin_articles.php" class="topbar-back">Annuler</a>
            <?php endif; ?>
            <a href="index.php" class="topbar-back">Voir le site</a>
        </div>
    </div>

    <div class="admin-content">
        <?php if ($msg): ?>
            <div class="alert alert-<?= $type ?>" style="max-width:760px;"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <!-- Formulaire ajout / édition -->
        <div class="admin-form-card" style="margin-bottom:36px;">
            <h2><?= $edit ? 'Modifier l\'article' : 'Nouvel article' ?></h2>
            <form method="POST">
                <input type="hidden" name="action" value="<?= $edit ? 'edit' : 'add' ?>">
                <?php if ($edit): ?>
                    <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="titre">Titre de l'article</label>
                    <input type="text" id="titre" name="titre" placeholder="Ex : Conseil municipal — juin 2025"
                           value="<?= htmlspecialchars($edit['titre'] ?? '') ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="categorie">Catégorie</label>
                        <select id="categorie" name="categorie">
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c ?>" <?= ($edit['categorie'] ?? 'Général') === $c ? 'selected' : '' ?>><?= $c ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group" style="justify-content:flex-end;padding-bottom:6px;">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin-top:auto;">
                            <input type="checkbox" name="visible" value="1"
                                   <?= (!$edit || $edit['visible']) ? 'checked' : '' ?>
                                   style="width:16px;height:16px;accent-color:var(--vert);">
                            <span style="font-size:13px;color:var(--texte-doux);font-weight:600;text-transform:none;letter-spacing:0;">Publier immédiatement</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="contenu">Contenu</label>
                    <textarea id="contenu" name="contenu" style="min-height:180px;" placeholder="Saisissez le contenu de l'article..." required><?= htmlspecialchars($edit['contenu'] ?? '') ?></textarea>
                    <span class="form-hint">Retours à la ligne acceptés. Pas de HTML.</span>
                </div>

                <div style="display:flex;gap:12px;">
                    <button type="submit" class="btn btn-primary"><?= $edit ? 'Enregistrer les modifications' : 'Publier l\'article' ?></button>
                    <?php if ($edit): ?>
                        <a href="admin_articles.php" class="btn" style="background:var(--bordure);color:var(--texte-doux);">Annuler</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Liste des articles -->
        <div class="admin-table-wrap">
            <div class="admin-table-header">
                <h2>Tous les articles (<?= count($articles) ?>)</h2>
            </div>
            <?php if (empty($articles)): ?>
                <div class="empty-state"><strong>Aucun article</strong>Créez votre premier article ci-dessus.</div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $a): ?>
                    <tr>
                        <td style="font-weight:600;color:var(--texte);"><?= htmlspecialchars($a['titre']) ?></td>
                        <td><span class="cat-tag"><?= htmlspecialchars($a['categorie']) ?></span></td>
                        <td><?= date('d/m/Y', strtotime($a['date_pub'])) ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="id"     value="<?= $a['id'] ?>">
                                <?php if ($a['visible']): ?>
                                    <input type="hidden" name="val" value="0">
                                    <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;" title="Masquer">
                                        <span class="status-on">Publié</span>
                                    </button>
                                <?php else: ?>
                                    <input type="hidden" name="val" value="1">
                                    <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;" title="Publier">
                                        <span class="status-off">Masqué</span>
                                    </button>
                                <?php endif; ?>
                            </form>
                        </td>
                        <td>
                            <div class="td-actions">
                                <a href="admin_articles.php?edit=<?= $a['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                                <form method="POST" onsubmit="return confirm('Supprimer cet article ?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id"     value="<?= $a['id'] ?>">
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
