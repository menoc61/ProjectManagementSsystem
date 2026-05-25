<?php
declare(strict_types=1);

function entity_option_label(array $row, string $fields): string
{
    $parts = [];
    foreach (explode(',', $fields) as $field) {
        $parts[] = $row[trim($field)] ?? '';
    }
    return trim(implode(' ', array_filter($parts)));
}

function load_options(string $definition): array
{
    [$table, $key, $labelFields] = explode(':', $definition);
    $rows = db_all("SELECT * FROM `$table` ORDER BY `$key`");
    $options = [];
    foreach ($rows as $row) {
        $options[(string)$row[$key]] = entity_option_label($row, $labelFields);
    }
    return $options;
}

function collect_entity_input(array $entity, ?array $existing = null): array
{
    $data = [];

    foreach ($entity['fields'] as $field => $meta) {
        if ($entity['module'] === 'utilisateurs' && $field === 'MotDePasse') {
            $password = trim((string)($_POST[$field] ?? ''));
            if ($existing === null && $password === '') {
                throw new RuntimeException('Le mot de passe est obligatoire pour creer un utilisateur.');
            }
            if ($password !== '') {
                $data[$field] = password_hash($password, PASSWORD_DEFAULT);
            }
            continue;
        }

        $value = $_POST[$field] ?? null;
        if (($meta['required'] ?? false) && trim((string)$value) === '') {
            throw new RuntimeException('Le champ "' . $meta['label'] . '" est obligatoire.');
        }
        $data[$field] = $value === '' ? null : $value;
    }

    if ($entity['module'] === 'reglements' && empty($data['HeureReglement'])) {
        $data['HeureReglement'] = date('H:i:s');
    }

    if ($entity['module'] === 'taches' && empty($data['DateEnregTache'])) {
        $data['DateEnregTache'] = date('Y-m-d');
    }

    return $data;
}

function render_field(string $field, array $meta, mixed $value = null, bool $editingPk = false): void
{
    $required = !empty($meta['required']) ? 'required' : '';
    $disabled = $editingPk ? 'readonly' : '';
    $id = 'field_' . $field;
    $type = $meta['type'] ?? 'text';
    ?>
    <div class="col-md-6 form-field">
        <label for="<?= e($id) ?>" class="form-label"><?= e($meta['label']) ?><?= $required ? ' *' : '' ?></label>
        <?php if ($type === 'textarea'): ?>
            <textarea class="form-control" id="<?= e($id) ?>" name="<?= e($field) ?>" rows="4" <?= $required ?>><?= e((string)$value) ?></textarea>
        <?php elseif ($type === 'select_static'): ?>
            <select class="form-select" id="<?= e($id) ?>" name="<?= e($field) ?>" <?= $required ?>>
                <?php foreach ($meta['options'] as $optionValue => $label): ?>
                    <option value="<?= e((string)$optionValue) ?>" <?= (string)$value === (string)$optionValue ? 'selected' : '' ?>><?= e((string)$label) ?></option>
                <?php endforeach; ?>
            </select>
        <?php elseif ($type === 'select'): ?>
            <select class="form-select" id="<?= e($id) ?>" name="<?= e($field) ?>" <?= $required ?>>
                <option value="">Selectionner</option>
                <?php foreach (load_options($meta['options']) as $optionValue => $label): ?>
                    <option value="<?= e((string)$optionValue) ?>" <?= (string)$value === (string)$optionValue ? 'selected' : '' ?>><?= e((string)$label) ?></option>
                <?php endforeach; ?>
            </select>
        <?php else: ?>
            <input class="form-control" id="<?= e($id) ?>" name="<?= e($field) ?>" type="<?= e($type) ?>" value="<?= e((string)$value) ?>" step="<?= e((string)($meta['step'] ?? '')) ?>" <?= $required ?> <?= $disabled ?>>
        <?php endif; ?>
    </div>
    <?php
}

function entity_display_value(string $module, string $field, mixed $value): string
{
    if ($value === null || $value === '') {
        return '-';
    }

    if (str_contains(strtolower($field), 'date')) {
        return format_date((string)$value);
    }
    if (str_contains(strtolower($field), 'cout') || str_contains(strtolower($field), 'montant') || str_contains(strtolower($field), 'forfait')) {
        return format_money($value);
    }
    if ($field === 'EtatProjet') {
        return project_status_label($value);
    }
    if ($field === 'EtatTache') {
        return task_status_label($value);
    }
    if ($field === 'Role') {
        return role_badge((string)$value);
    }

    return e((string)$value);
}

function crud_index(string $module): void
{
    $entity = get_entity($module);
    require_roles($entity['roles']);

    $search = trim((string)($_GET['search'] ?? ''));
    $where = [];
    $params = [];
    if ($search !== '' && !empty($entity['search'])) {
        $parts = [];
        foreach ($entity['search'] as $field) {
            $parts[] = "`$field` LIKE ?";
            $params[] = '%' . $search . '%';
        }
        $where[] = '(' . implode(' OR ', $parts) . ')';
    }
    $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    $rows = db_all("SELECT * FROM `{$entity['table']}` $whereSql ORDER BY `{$entity['pk']}` DESC", $params);

    render_header($entity['title'], $module . '/index.php');
    ?>
    <section class="toolbar-panel">
        <form class="row g-2 align-items-center" method="get">
            <div class="col-md-6">
                <input class="form-control" name="search" value="<?= e($search) ?>" placeholder="Rechercher dans <?= e(strtolower($entity['title'])) ?>">
            </div>
            <div class="col-md-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Rechercher</button>
                <a class="btn btn-outline-secondary" href="index.php">Reinitialiser</a>
            </div>
            <div class="col text-md-end">
                <a class="btn btn-success" href="export_csv.php<?= $search !== '' ? '?search=' . urlencode($search) : '' ?>"><i class="fa-solid fa-file-csv"></i> CSV</a>
                <a class="btn btn-primary" href="create.php"><i class="fa-solid fa-plus"></i> Nouveau</a>
            </div>
        </form>
    </section>

    <section class="data-card">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                <tr>
                    <th><?= e($entity['pk']) ?></th>
                    <?php foreach ($entity['columns'] as $column): ?>
                        <th><?= e($entity['fields'][$column]['label'] ?? $column) ?></th>
                    <?php endforeach; ?>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td class="fw-semibold"><?= e((string)$row[$entity['pk']]) ?></td>
                        <?php foreach ($entity['columns'] as $column): ?>
                            <td><?= entity_display_value($module, $column, $row[$column] ?? null) ?></td>
                        <?php endforeach; ?>
                        <td class="text-end action-buttons">
                            <a class="btn btn-sm btn-outline-info" href="view.php?id=<?= urlencode((string)$row[$entity['pk']]) ?>" title="Voir"><i class="fa-solid fa-eye"></i></a>
                            <a class="btn btn-sm btn-outline-warning" href="edit.php?id=<?= urlencode((string)$row[$entity['pk']]) ?>" title="Modifier"><i class="fa-solid fa-pen"></i></a>
                            <a class="btn btn-sm btn-outline-danger" href="delete.php?id=<?= urlencode((string)$row[$entity['pk']]) ?>" data-confirm="Supprimer cet element ?" title="Supprimer"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$rows): ?>
                    <tr><td colspan="<?= count($entity['columns']) + 2 ?>" class="text-center text-muted py-5">Aucun enregistrement trouve.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php
    render_footer();
}

function crud_form(string $module, ?string $id = null): void
{
    $entity = get_entity($module);
    require_roles($entity['roles']);
    $editing = $id !== null;
    $row = $editing ? db_one("SELECT * FROM `{$entity['table']}` WHERE `{$entity['pk']}` = ?", [$id]) : null;
    if ($editing && !$row) {
        flash('danger', 'Enregistrement introuvable.');
        redirect('index.php');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $data = collect_entity_input($entity, $row);
            if ($editing) {
                $set = [];
                $params = [];
                foreach ($data as $field => $value) {
                    if ($field === $entity['pk']) {
                        continue;
                    }
                    $set[] = "`$field` = ?";
                    $params[] = $value;
                }
                $params[] = $id;
                db_execute("UPDATE `{$entity['table']}` SET " . implode(', ', $set) . " WHERE `{$entity['pk']}` = ?", $params);
                flash('success', ucfirst($entity['singular']) . ' modifie avec succes.');
            } else {
                $fields = array_keys($data);
                $placeholders = implode(', ', array_fill(0, count($fields), '?'));
                db_execute("INSERT INTO `{$entity['table']}` (`" . implode('`, `', $fields) . "`) VALUES ($placeholders)", array_values($data));
                flash('success', ucfirst($entity['singular']) . ' cree avec succes.');
            }
            redirect('index.php');
        } catch (Throwable $exception) {
            flash('danger', $exception->getMessage());
        }
    }

    render_header(($editing ? 'Modifier ' : 'Ajouter ') . $entity['singular'], $module . '/index.php');
    ?>
    <section class="data-card form-card">
        <form method="post" class="row g-3">
            <?php foreach ($entity['fields'] as $field => $meta): ?>
                <?php
                $isPk = $editing && $field === $entity['pk'];
                $value = $_POST[$field] ?? ($row[$field] ?? '');
                render_field($field, $meta, $value, $isPk);
                ?>
            <?php endforeach; ?>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Enregistrer</button>
                <a class="btn btn-outline-secondary" href="index.php">Annuler</a>
            </div>
        </form>
    </section>
    <?php
    render_footer();
}

function crud_view(string $module, string $id): void
{
    $entity = get_entity($module);
    require_roles($entity['roles']);
    $row = db_one("SELECT * FROM `{$entity['table']}` WHERE `{$entity['pk']}` = ?", [$id]);
    if (!$row) {
        flash('danger', 'Enregistrement introuvable.');
        redirect('index.php');
    }

    render_header('Detail - ' . $entity['singular'], $module . '/index.php');
    ?>
    <section class="data-card">
        <div class="detail-header">
            <div>
                <p class="eyebrow mb-1"><?= e($entity['title']) ?></p>
                <h2><?= e((string)$row[$entity['pk']]) ?></h2>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-warning" href="edit.php?id=<?= urlencode((string)$row[$entity['pk']]) ?>"><i class="fa-solid fa-pen"></i> Modifier</a>
                <a class="btn btn-outline-secondary" href="index.php">Retour</a>
            </div>
        </div>
        <div class="row g-3">
            <?php foreach ($row as $field => $value): ?>
                <?php if ($field === 'MotDePasse') continue; ?>
                <div class="col-md-6">
                    <div class="detail-item">
                        <span><?= e($entity['fields'][$field]['label'] ?? $field) ?></span>
                        <strong><?= entity_display_value($module, $field, $value) ?></strong>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    render_footer();
}

function crud_delete(string $module, string $id): void
{
    $entity = get_entity($module);
    require_roles($entity['roles']);

    if ($module === 'utilisateurs' && (int)$id === (int)($_SESSION['user_id'] ?? 0)) {
        flash('danger', 'Vous ne pouvez pas supprimer votre propre compte.');
        redirect('index.php');
    }

    db_execute("DELETE FROM `{$entity['table']}` WHERE `{$entity['pk']}` = ?", [$id]);
    flash('success', ucfirst($entity['singular']) . ' supprime avec succes.');
    redirect('index.php');
}

function crud_export_csv(string $module): void
{
    $entity = get_entity($module);
    require_roles($entity['roles']);
    $rows = db_all("SELECT * FROM `{$entity['table']}` ORDER BY `{$entity['pk']}` DESC");

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $module . '.csv"');
    $output = fopen('php://output', 'w');
    if ($rows) {
        fputcsv($output, array_keys($rows[0]), ';');
        foreach ($rows as $row) {
            fputcsv($output, $row, ';');
        }
    } else {
        fputcsv($output, [$entity['title']], ';');
    }
    fclose($output);
    exit;
}
