<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';
require_roles([ROLE_ADMIN]);

$expectedTables = [
    'CLIENT', 'TYPEPROJET', 'CHEF_PROJET', 'CHEF_SERVICE', 'SERVICE',
    'PERSONNEL', 'PROJET', 'TACHE', 'REGLEMENT', 'AFFECTATION', 'UTILISATEUR',
];

$checks = [];
$checks[] = ['Connexion MySQL', db() instanceof mysqli, 'Connexion active sur ' . DB_NAME];

foreach ($expectedTables as $table) {
    try {
        $count = db_one("SELECT COUNT(*) total FROM `$table`")['total'] ?? 0;
        $checks[] = ["Table $table", true, "$count enregistrement(s)"];
    } catch (Throwable $exception) {
        $checks[] = ["Table $table", false, $exception->getMessage()];
    }
}

$roles = db_all('SELECT Role, COUNT(*) total FROM UTILISATEUR GROUP BY Role');
$roleNames = array_column($roles, 'Role');
foreach ([ROLE_ADMIN, ROLE_CHEF_PROJET, ROLE_CHEF_SERVICE, ROLE_PERSONNEL] as $role) {
    $checks[] = ['Role ' . role_label($role), in_array($role, $roleNames, true), 'Compte de demonstration requis'];
}

$failed = array_filter($checks, fn(array $check) => !$check[1]);

render_header('Tests de verification', 'tests/index.php');
?>
<section class="data-card mb-4">
    <div class="d-flex justify-content-between align-items-center gap-3">
        <div>
            <p class="eyebrow">Controle qualite</p>
            <h2 class="fw-bold mb-0"><?= count($failed) === 0 ? 'Tous les controles principaux sont valides.' : count($failed) . ' controle(s) a corriger.' ?></h2>
        </div>
        <a class="btn btn-outline-primary" href="index.php"><i class="fa-solid fa-rotate"></i> Relancer</a>
    </div>
</section>

<section class="data-card">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Controle</th><th>Statut</th><th>Detail</th></tr></thead>
            <tbody>
            <?php foreach ($checks as [$label, $ok, $detail]): ?>
                <tr>
                    <td class="fw-semibold"><?= e($label) ?></td>
                    <td><?= $ok ? '<span class="badge text-bg-success">OK</span>' : '<span class="badge text-bg-danger">Erreur</span>' ?></td>
                    <td><?= e($detail) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php render_footer(); ?>
