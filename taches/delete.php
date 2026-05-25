<?php require_once __DIR__ . '/../app/bootstrap.php'; crud_delete('taches', (string)($_GET['id'] ?? ''));
