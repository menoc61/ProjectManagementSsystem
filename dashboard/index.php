<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';
require_login();

$totals = [
    'clients' => (int)(db_one('SELECT COUNT(*) total FROM CLIENT')['total'] ?? 0),
    'projets' => (int)(db_one('SELECT COUNT(*) total FROM PROJET')['total'] ?? 0),
    'taches' => (int)(db_one('SELECT COUNT(*) total FROM TACHE')['total'] ?? 0),
    'personnel' => (int)(db_one('SELECT COUNT(*) total FROM PERSONNEL')['total'] ?? 0),
    'cout' => (float)(db_one('SELECT COALESCE(SUM(CoutProjet), 0) total FROM PROJET')['total'] ?? 0),
    'regle' => (float)(db_one('SELECT COALESCE(SUM(MontantReglement), 0) total FROM REGLEMENT')['total'] ?? 0),
];
$totals['reste'] = $totals['cout'] - $totals['regle'];

$projectStates = db_all('SELECT EtatProjet, COUNT(*) total FROM PROJET GROUP BY EtatProjet ORDER BY EtatProjet');
$taskStates = db_all('SELECT EtatTache, COUNT(*) total FROM TACHE GROUP BY EtatTache ORDER BY EtatTache');
$roleStats = db_all('SELECT Role, COUNT(*) total FROM UTILISATEUR GROUP BY Role ORDER BY Role');
$financeByProject = db_all(
    'SELECT p.TitreProjet, p.CoutProjet, COALESCE(SUM(r.MontantReglement), 0) Regle
     FROM PROJET p
     LEFT JOIN REGLEMENT r ON r.IdProjet = p.IdProjet
     GROUP BY p.IdProjet
     ORDER BY p.IdProjet DESC
     LIMIT 8'
);
$lateTasks = db_all(
    'SELECT t.IdTache, t.LibelleTache, t.DateFinTache, p.TitreProjet
     FROM TACHE t
     JOIN PROJET p ON p.IdProjet = t.IdProjet
     WHERE t.EtatTache IN (1,2) AND t.DateFinTache IS NOT NULL AND t.DateFinTache < CURDATE()
     ORDER BY t.DateFinTache ASC
     LIMIT 6'
);

render_header('Tableau de bord', 'dashboard/index.php');
?>
<section class="row g-3 mb-4">
    <div class="col-md-3"><div class="metric-card"><div class="icon"><i class="fa-solid fa-building-user"></i></div><strong><?= $totals['clients'] ?></strong><span>Clients</span></div></div>
    <div class="col-md-3"><div class="metric-card"><div class="icon bg-success"><i class="fa-solid fa-diagram-project"></i></div><strong><?= $totals['projets'] ?></strong><span>Projets</span></div></div>
    <div class="col-md-3"><div class="metric-card"><div class="icon bg-info"><i class="fa-solid fa-list-check"></i></div><strong><?= $totals['taches'] ?></strong><span>Taches</span></div></div>
    <div class="col-md-3"><div class="metric-card"><div class="icon bg-dark"><i class="fa-solid fa-id-badge"></i></div><strong><?= $totals['personnel'] ?></strong><span>Membres du personnel</span></div></div>
</section>

<section class="row g-3 mb-4">
    <div class="col-md-4"><div class="metric-card"><span>Cout total des projets</span><strong><?= format_money($totals['cout']) ?></strong></div></div>
    <div class="col-md-4"><div class="metric-card"><span>Total regle</span><strong class="text-success"><?= format_money($totals['regle']) ?></strong></div></div>
    <div class="col-md-4"><div class="metric-card"><span>Reste a percevoir</span><strong class="<?= $totals['reste'] > 0 ? 'text-danger' : 'text-success' ?>"><?= format_money($totals['reste']) ?></strong></div></div>
</section>

<section class="row g-4">
    <div class="col-xl-4">
        <div class="data-card h-100">
            <h2 class="h5 fw-bold mb-3">Etat des projets</h2>
            <?php foreach ($projectStates as $state): ?>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span><?= project_status_label($state['EtatProjet']) ?></span>
                    <strong><?= (int)$state['total'] ?></strong>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="data-card h-100">
            <h2 class="h5 fw-bold mb-3">Etat des taches</h2>
            <?php foreach ($taskStates as $state): ?>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span><?= task_status_label($state['EtatTache']) ?></span>
                    <strong><?= (int)$state['total'] ?></strong>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="data-card h-100">
            <h2 class="h5 fw-bold mb-3">Utilisateurs par role</h2>
            <?php foreach ($roleStats as $role): ?>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span><?= role_badge($role['Role']) ?></span>
                    <strong><?= (int)$role['total'] ?></strong>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="row g-4 mt-1">
    <div class="col-xl-7">
        <div class="data-card h-100">
            <h2 class="h5 fw-bold mb-3">Suivi financier par projet</h2>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Projet</th><th>Cout</th><th>Regle</th><th>Solde</th></tr></thead>
                    <tbody>
                    <?php foreach ($financeByProject as $project): ?>
                        <tr>
                            <td><?= e($project['TitreProjet']) ?></td>
                            <td><?= format_money($project['CoutProjet']) ?></td>
                            <td><?= format_money($project['Regle']) ?></td>
                            <td><?= format_money((float)$project['CoutProjet'] - (float)$project['Regle']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="data-card h-100">
            <h2 class="h5 fw-bold mb-3">Taches en retard</h2>
            <?php if (!$lateTasks): ?>
                <p class="text-muted mb-0">Aucune tache en retard.</p>
            <?php endif; ?>
            <?php foreach ($lateTasks as $task): ?>
                <a class="list-group-item list-group-item-action px-0 border-bottom" href="<?= e(app_url('modules/taches/view.php?id=' . urlencode((string)$task['IdTache']))) ?>">
                    <strong><?= e($task['LibelleTache']) ?></strong>
                    <span class="d-block text-muted"><?= e($task['TitreProjet']) ?> - echeance <?= format_date($task['DateFinTache']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php render_footer(); ?>
