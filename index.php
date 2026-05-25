<?php
declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap.php';
require_login();

$stats = [
    'clients' => db_one('SELECT COUNT(*) total FROM CLIENT')['total'] ?? 0,
    'projets' => db_one('SELECT COUNT(*) total FROM PROJET')['total'] ?? 0,
    'taches' => db_one('SELECT COUNT(*) total FROM TACHE')['total'] ?? 0,
    'reglements' => db_one('SELECT COALESCE(SUM(MontantReglement), 0) total FROM REGLEMENT')['total'] ?? 0,
];
$recentProjects = db_all('SELECT p.IdProjet, p.TitreProjet, p.EtatProjet, c.NomClient FROM PROJET p JOIN CLIENT c ON c.IdClient = p.IdClient ORDER BY p.IdProjet DESC LIMIT 5');
$upcomingTasks = db_all('SELECT t.IdTache, t.LibelleTache, t.DateDebutTache, t.EtatTache, p.TitreProjet FROM TACHE t JOIN PROJET p ON p.IdProjet = t.IdProjet ORDER BY t.DateDebutTache IS NULL, t.DateDebutTache ASC LIMIT 5');

render_header('Accueil', 'index.php');
?>
<section class="data-card mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-8">
            <p class="eyebrow">Bienvenue</p>
            <h2 class="fw-bold">Bonjour <?= e(current_user_name()) ?>, votre espace est pret.</h2>
            <p class="text-muted mb-0">Toutes les fonctionnalites sont organisees par role afin de suivre les clients, les projets, les taches, les affectations et les reglements sans confusion.</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a class="btn btn-primary btn-lg" href="<?= e(app_url('dashboard/index.php')) ?>">
                <i class="fa-solid fa-chart-line"></i> Ouvrir le tableau de bord
            </a>
        </div>
    </div>
</section>

<section class="row g-3 mb-4">
    <div class="col-md-3"><div class="metric-card"><div class="icon"><i class="fa-solid fa-building-user"></i></div><strong><?= e((string)$stats['clients']) ?></strong><span>Clients</span></div></div>
    <div class="col-md-3"><div class="metric-card"><div class="icon bg-success"><i class="fa-solid fa-diagram-project"></i></div><strong><?= e((string)$stats['projets']) ?></strong><span>Projets</span></div></div>
    <div class="col-md-3"><div class="metric-card"><div class="icon bg-info"><i class="fa-solid fa-list-check"></i></div><strong><?= e((string)$stats['taches']) ?></strong><span>Taches</span></div></div>
    <div class="col-md-3"><div class="metric-card"><div class="icon bg-warning"><i class="fa-solid fa-money-bill-wave"></i></div><strong><?= format_money($stats['reglements']) ?></strong><span>Reglements</span></div></div>
</section>

<section class="row g-4">
    <div class="col-lg-6">
        <div class="data-card h-100">
            <h3 class="h5 fw-bold mb-3">Projets recents</h3>
            <div class="list-group list-group-flush">
                <?php foreach ($recentProjects as $project): ?>
                    <a class="list-group-item list-group-item-action px-0" href="<?= e(app_url('modules/projets/view.php?id=' . urlencode((string)$project['IdProjet']))) ?>">
                        <div class="d-flex justify-content-between gap-3">
                            <strong><?= e($project['TitreProjet']) ?></strong>
                            <?= project_status_label($project['EtatProjet']) ?>
                        </div>
                        <span class="text-muted"><?= e($project['NomClient']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="data-card h-100">
            <h3 class="h5 fw-bold mb-3">Taches a suivre</h3>
            <div class="list-group list-group-flush">
                <?php foreach ($upcomingTasks as $task): ?>
                    <a class="list-group-item list-group-item-action px-0" href="<?= e(app_url('modules/taches/view.php?id=' . urlencode((string)$task['IdTache']))) ?>">
                        <div class="d-flex justify-content-between gap-3">
                            <strong><?= e($task['LibelleTache']) ?></strong>
                            <?= task_status_label($task['EtatTache']) ?>
                        </div>
                        <span class="text-muted"><?= e($task['TitreProjet']) ?> - <?= format_date($task['DateDebutTache']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php render_footer(); ?>
