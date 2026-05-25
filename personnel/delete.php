<?php require_once __DIR__ . '/../app/bootstrap.php'; crud_delete('personnel', (string)($_GET['id'] ?? ''));
