<?php
/**
 * install.php – Créer la base de données et les tables
 * Ouvrir cette page une seule fois depuis le navigateur puis la supprimer.
 */

$host    = 'localhost';
$user    = 'root';   // ← adapter
$pass    = '';       // ← adapter
$dbName  = 'francilly_db';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $sql = file_get_contents(__DIR__ . '/install.sql');
    // Exécute chaque instruction séparément
    foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
        if ($stmt !== '') $pdo->exec($stmt);
    }

    echo '<h2 style="color:green;font-family:sans-serif;padding:30px;">✅ Installation réussie ! Base de données <strong>' . $dbName . '</strong> créée avec les 3 tables et les données de démo.<br><br><a href="index.php">→ Aller au site</a></h2>';
} catch (PDOException $e) {
    echo '<h2 style="color:red;font-family:sans-serif;padding:30px;">❌ Erreur : ' . htmlspecialchars($e->getMessage()) . '</h2>';
}
