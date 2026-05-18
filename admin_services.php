<?php
require_once 'admin_auth.php';
require_once 'db.php';
$pdo = getDB();

$msg = ''; $type = '';
$edit = null;

// ── Actions POST ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $nom    = trim($_POST['nom']         ?? '');
    $desc   = trim($_POST['description'] ?? '');
    $cat    = trim($_POST['categorie']   ?? 'Administratif');
    $actif  = isset($_POST['actif']) ? 1 : 0;

    if ($action === 'add') {
        if (!$nom || !$desc) {
            $msg = 'Nom et description sont obligatoires.'; $type = 'error';
        } else {
            $pdo->prepare("INSERT INTO services (nom,description,categorie,actif) VALUES(:n,:d,:c,:a)")
                ->execute([':n'=>$nom, ':d'=>$desc, ':c'=>$cat, ':a'=>$actif]);
            $msg = 'Service ajouté avec succès.'; $type = 'success';
        }

    } elseif ($action === 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$nom || !$desc || !$id) {
            $msg = 'Données invalides.'; $type = 'error';
        } else {
            $pdo->prepare("UPDATE services SET nom=:n, description=:d, categorie=:c, actif=:a WHERE id=:id")
                ->execute([':n'=>$nom, ':d'=>$desc, ':c'=>$cat, ':a'=>$actif, ':id'=>$id]);
            $msg = 'Service modifié avec succès.'; $type = 'success';
        }

    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $pdo->prepare("DELETE FROM services WHERE id=:id")->execute([':id'=>$id]);
            $msg = 'Service supprimé.'; $type = 'success';
        }

    } elseif ($action === 'toggle') {
        $id  = (int)($_POST['id'] ?? 0);
        $val = (int)($_POST['val'] ?? 0);
        if ($id) {
            $pdo->prepare("UPDATE services SET actif=:a WHERE id=:id")->execute([':a'=>$val, ':id'=>$id]);
        }
        header('Location: admin_services.php'); exit;
    }
}

// ── Chargement pour édition ───────────────────────────────
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id=:id");
    $stmt->execute([':id'=>(int)$_GET['edit']]);
    $edit = $stmt->fetch();
}

$services = $pdo->query("SELECT * FROM services ORDER BY categorie, nom")->fetchAll();

$categories = ['Administratif','Urbanisme','Enfance','Culture','Environnement','Social','Sport'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Services — Administration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="admin-body">

<?php include 'admin_sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-topbar">
        <h1><?= $edit ? 'Modifier un service' : 'Gestion des services' ?></h1>
        <div class="admin-topbar-actions">
            <?php if ($edit): ?>
                <a href="admin_services.php" class="topbar-back">Annuler</a>
            <?php endif; ?>
            <a href="services.php" class="topbar-back">Voir la page</a>
        </div>
    </div>

    <div class="admin-content">
        <?php if ($msg): ?>
            <div class="alert alert-<?= $type ?>" style="max-width:760px;"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <!-- Formulaire ajout / édition -->
        <div class="admin-form-card" style="margin-bottom:36px;">
            <h2><?= $edit ? 'Modifier le service' : 'Nouveau service' ?></h2>
            <form method="POST">
                <input type="hidden" name="action" value="<?= $edit ? 'edit' : 'add' ?>">
                <?php if ($edit): ?>
                    <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">Nom du service</label>
                        <input type="text" id="nom" name="nom" placeholder="Ex : État civil"
                               value="<?= htmlspecialchars($edit['nom'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="categorie">Catégorie</label>
                        <select id="categorie" name="categorie">
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c ?>" <?= ($edit['categorie'] ?? 'Administratif') === $c ? 'selected' : '' ?>><?= $c ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" style="min-height:120px;"
                              placeholder="Décrivez ce service municipal..." required><?= htmlspecialchars($edit['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="actif" value="1"
                               <?= (!$edit || $edit['actif']) ? 'checked' : '' ?>
                               style="width:16px;height:16px;accent-color:var(--vert);">
                        <span style="font-size:13px;color:var(--texte-doux);font-weight:600;text-transform:none;letter-spacing:0;">Service actif (affiché sur le site)</span>
                    </label>
                </div>

                <div style="display:flex;gap:12px;">
                    <button type="submit" class="btn btn-primary"><?= $edit ? 'Enregistrer les modifications' : 'Ajouter le service' ?></button>
                    <?php if ($edit): ?>
                        <a href="admin_services.php" class="btn" style="background:var(--bordure);color:var(--texte-doux);">Annuler</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Liste des services -->
        <div class="admin-table-wrap">
            <div class="admin-table-header">
                <h2>Tous les services (<?= count($services) ?>)</h2>
            </div>
            <?php if (empty($services)): ?>
                <div class="empty-state"><strong>Aucun service</strong>Créez votre premier service ci-dessus.</div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Description</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $s): ?>
                    <tr>
                        <td style="font-weight:600;color:var(--texte);"><?= htmlspecialchars($s['nom']) ?></td>
                        <td><span class="cat-tag"><?= htmlspecialchars($s['categorie']) ?></span></td>
                        <td style="max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($s['description']) ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="id"     value="<?= $s['id'] ?>">
                                <?php if ($s['actif']): ?>
                                    <input type="hidden" name="val" value="0">
                                    <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;" title="Désactiver">
                                        <span class="status-on">Actif</span>
                                    </button>
                                <?php else: ?>
                                    <input type="hidden" name="val" value="1">
                                    <button type="submit" style="background:none;border:none;cursor:pointer;padding:0;" title="Activer">
                                        <span class="status-off">Inactif</span>
                                    </button>
                                <?php endif; ?>
                            </form>
                        </td>
                        <td>
                            <div class="td-actions">
                                <a href="admin_services.php?edit=<?= $s['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                                <form method="POST" onsubmit="return confirm('Supprimer ce service ?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id"     value="<?= $s['id'] ?>">
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
