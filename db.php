<?php
// ============================================================
//  Configuration de la base de données
// ============================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'francilly_db');
define('DB_USER', 'root');       // ← modifier selon votre config
define('DB_PASS', '');           // ← modifier selon votre config
define('DB_CHARSET', 'utf8mb4');

// ============================================================
//  Connexion PDO
// ============================================================
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('<p style="color:red;padding:20px;">Erreur de connexion BDD : ' . htmlspecialchars($e->getMessage()) . '</p>');
        }
    }
    return $pdo;
}

// ============================================================
//  Initialisation des tables (à appeler une seule fois)
//  Accéder à install.php pour créer la BDD
// ============================================================
