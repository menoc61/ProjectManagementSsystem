<?php require_once __DIR__ . '/../app/bootstrap.php'; crud_delete('chefs_projet', (string)($_GET['id'] ?? ''));
