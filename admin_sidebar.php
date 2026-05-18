<?php
$current = basename($_SERVER['PHP_SELF'], '.php');
function sl($file, $label, $icon) {
    global $current;
    $active = ($current === $file) ? ' active' : '';
    echo '<a href="' . $file . '.php" class="sidebar-link' . $active . '">' . $icon . $label . '</a>';
}
?>
<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-emblem">FS</div>
        <div class="sidebar-title">
            Francilly-Selency
            <small>Administration</small>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="sidebar-section">Principal</div>
        <a href="admin.php"          class="sidebar-link <?= $current === 'admin'          ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Tableau de bord
        </a>

        <div class="sidebar-section">Contenu</div>
        <a href="admin_articles.php" class="sidebar-link <?= $current === 'admin_articles' ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            Articles
        </a>
        <a href="admin_services.php" class="sidebar-link <?= $current === 'admin_services' ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/><path d="M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
            Services
        </a>

        <div class="sidebar-section">Messages</div>
        <a href="admin_contacts.php" class="sidebar-link <?= $current === 'admin_contacts' ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            Messagerie
        </a>
    </nav>
    <div class="sidebar-footer">
        <a href="index.php" style="color:rgba(255,255,255,.5);display:block;margin-bottom:6px;">Voir le site public</a>
        <a href="admin_logout.php" style="color:rgba(255,255,255,.5);">Déconnexion</a>
    </div>
</aside>
