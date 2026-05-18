<?php
// Inclure en haut de chaque page admin
session_start();
if (empty($_SESSION['admin_ok'])) {
    header('Location: admin_login.php');
    exit;
}
