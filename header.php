<?php
$page_active = basename($_SERVER['PHP_SELF'], '.php');
?>
<link rel="stylesheet" href="styles.css">

<div class="header-inner">
    <a href="index.php" class="logo">
        <div class="logo-emblem">FS</div>
        <div class="logo-text">
            <span class="logo-name">Francilly-Selency</span>
            <span class="logo-sub">Mairie &middot; Commune de l'Aisne</span>
        </div>
    </a>
    <nav>
        <ul class="nav-list">
            <li><a href="index.php"    class="nav-link <?= $page_active === 'index'    ? 'active' : '' ?>">Mairie</a></li>
            <li><a href="services.php" class="nav-link <?= $page_active === 'services' ? 'active' : '' ?>">Services</a></li>
            <li><a href="contact.php"  class="nav-link <?= $page_active === 'contact'  ? 'active' : '' ?>">Contact</a></li>
        </ul>
    </nav>
</div>
