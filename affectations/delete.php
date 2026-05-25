<?php require_once __DIR__ . '/../app/bootstrap.php'; crud_delete('affectations', (string)($_GET['id'] ?? ''));
