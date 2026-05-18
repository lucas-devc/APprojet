<?php
session_start();

// ── Mot de passe admin ─────────────────────────────────────
// Modifiez cette valeur avec votre propre mot de passe haché :
// Pour générer : php -r "echo password_hash('votre_mdp', PASSWORD_DEFAULT);"
define('ADMIN_HASH', password_hash('admin1234', PASSWORD_DEFAULT));
// ────────────────────────────────────────────────────────────

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pwd = $_POST['password'] ?? '';
    if (password_verify($pwd, ADMIN_HASH)) {
        $_SESSION['admin_ok'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Mot de passe incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Connexion — Administration</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; background:var(--vert-pale); }
        .login-card {
            background:var(--blanc); border-radius:var(--r);
            box-shadow:var(--ombre-m); padding:48px 44px;
            width:100%; max-width:400px;
        }
        .login-card .login-brand {
            display:flex; align-items:center; gap:12px; margin-bottom:32px;
        }
        .login-card .login-emblem {
            width:40px; height:40px; background:var(--vert);
            border-radius:4px; display:flex; align-items:center; justify-content:center;
            font-family:'Playfair Display',serif; font-size:15px; font-weight:700; color:var(--blanc);
        }
        .login-card h1 { font-family:'Playfair Display',serif; font-size:22px; color:var(--vert); margin-bottom:4px; }
        .login-card p  { font-size:13px; color:var(--texte-doux); margin-bottom:28px; }
        .login-footer  { text-align:center; margin-top:20px; font-size:12px; color:var(--texte-doux); }
        .login-footer a { color:var(--vert); font-weight:600; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-brand">
        <div class="login-emblem">FS</div>
        <div>
            <div style="font-family:'Playfair Display',serif;font-size:16px;font-weight:600;color:var(--vert);">Francilly-Selency</div>
            <div style="font-size:11px;color:var(--texte-doux);letter-spacing:1px;text-transform:uppercase;">Administration</div>
        </div>
    </div>
    <h1>Connexion</h1>
    <p>Espace réservé à la mairie.</p>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="••••••••" autofocus required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Accéder à l'administration</button>
    </form>
    <div class="login-footer"><a href="index.php">Retour au site</a></div>
</div>
</body>
</html>
