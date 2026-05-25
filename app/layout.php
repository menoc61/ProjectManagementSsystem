<?php
declare(strict_types=1);

function nav_items(): array
{
    return [
        ['label' => 'Accueil', 'url' => 'index.php', 'icon' => 'fa-solid fa-house', 'roles' => [ROLE_ADMIN, ROLE_CHEF_PROJET, ROLE_CHEF_SERVICE, ROLE_PERSONNEL]],
        ['label' => 'Tableau de bord', 'url' => 'dashboard/index.php', 'icon' => 'fa-solid fa-chart-line', 'roles' => [ROLE_ADMIN, ROLE_CHEF_PROJET, ROLE_CHEF_SERVICE, ROLE_PERSONNEL]],
        ['label' => 'Clients', 'url' => 'modules/clients/index.php', 'icon' => 'fa-solid fa-building-user', 'roles' => [ROLE_ADMIN, ROLE_CHEF_PROJET, ROLE_CHEF_SERVICE, ROLE_PERSONNEL]],
        ['label' => 'Projets', 'url' => 'modules/projets/index.php', 'icon' => 'fa-solid fa-diagram-project', 'roles' => [ROLE_ADMIN, ROLE_CHEF_PROJET]],
        ['label' => 'Taches', 'url' => 'modules/taches/index.php', 'icon' => 'fa-solid fa-list-check', 'roles' => [ROLE_ADMIN, ROLE_CHEF_PROJET, ROLE_CHEF_SERVICE, ROLE_PERSONNEL]],
        ['label' => 'Reglements', 'url' => 'modules/reglements/index.php', 'icon' => 'fa-solid fa-money-bill-wave', 'roles' => [ROLE_ADMIN, ROLE_CHEF_PROJET]],
        ['label' => 'Types de projet', 'url' => 'modules/typesprojet/index.php', 'icon' => 'fa-solid fa-tags', 'roles' => [ROLE_ADMIN, ROLE_CHEF_PROJET]],
        ['label' => 'Services', 'url' => 'modules/services/index.php', 'icon' => 'fa-solid fa-sitemap', 'roles' => [ROLE_ADMIN, ROLE_CHEF_SERVICE]],
        ['label' => 'Personnel', 'url' => 'modules/personnel/index.php', 'icon' => 'fa-solid fa-id-badge', 'roles' => [ROLE_ADMIN, ROLE_CHEF_SERVICE]],
        ['label' => 'Affectations', 'url' => 'modules/affectations/index.php', 'icon' => 'fa-solid fa-user-check', 'roles' => [ROLE_ADMIN, ROLE_CHEF_PROJET, ROLE_CHEF_SERVICE]],
        ['label' => 'Chefs de projet', 'url' => 'modules/chefs_projet/index.php', 'icon' => 'fa-solid fa-user-tie', 'roles' => [ROLE_ADMIN]],
        ['label' => 'Chefs de service', 'url' => 'modules/chefs_service/index.php', 'icon' => 'fa-solid fa-user-shield', 'roles' => [ROLE_ADMIN]],
        ['label' => 'Utilisateurs', 'url' => 'modules/utilisateurs/index.php', 'icon' => 'fa-solid fa-users-gear', 'roles' => [ROLE_ADMIN]],
        ['label' => 'Tests', 'url' => 'tests/index.php', 'icon' => 'fa-solid fa-vial-circle-check', 'roles' => [ROLE_ADMIN]],
    ];
}

function render_header(string $title, string $active = ''): void
{
    $messages = consume_flash();
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= e($title) ?> - <?= e(APP_NAME) ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
        <link href="<?= e(app_url('public/assets/css/app.css')) ?>" rel="stylesheet">
    </head>
    <body class="role-<?= e(current_user_role()) ?>">
    <div class="app-shell">
        <aside class="app-sidebar">
            <a class="brand" href="<?= e(app_url('index.php')) ?>">
                <span class="brand-icon"><i class="fa-solid fa-layer-group"></i></span>
                <span>Gestion Projets</span>
            </a>
            <nav class="nav flex-column gap-1">
                <?php foreach (nav_items() as $item): ?>
                    <?php if (has_role($item['roles'])): ?>
                        <a class="nav-link <?= $active === $item['url'] ? 'active' : '' ?>" href="<?= e(app_url($item['url'])) ?>">
                            <i class="<?= e($item['icon']) ?>"></i>
                            <span><?= e($item['label']) ?></span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>
        </aside>
        <main class="app-main">
            <header class="topbar">
                <div>
                    <p class="eyebrow mb-1">Espace de travail</p>
                    <h1><?= e($title) ?></h1>
                </div>
                <div class="user-chip">
                    <i class="fa-solid fa-circle-user"></i>
                    <span><?= e(current_user_name()) ?></span>
                    <?= role_badge(current_user_role()) ?>
                    <a class="btn btn-sm btn-outline-danger" href="<?= e(app_url('logout.php')) ?>">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
            </header>
            <?php foreach ($messages as $message): ?>
                <div class="alert alert-<?= e($message['type']) ?> alert-dismissible fade show" role="alert">
                    <?= e($message['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            <?php endforeach; ?>
    <?php
}

function render_footer(): void
{
    ?>
            <footer class="app-footer">
                <span>Copyright <?= date('Y') ?> - Gestion des projets d'entreprise.</span>
                <a href="https://github.com/menoc61" target="_blank" rel="noopener">
                    <i class="fa-brands fa-github"></i> menoc61
                </a>
            </footer>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= e(app_url('public/assets/js/app.js')) ?>"></script>
    </body>
    </html>
    <?php
}
