<?php require_once __DIR__ . '/../app/bootstrap.php'; crud_delete('utilisateurs', (string)($_GET['id'] ?? ''));
