<?php require_once __DIR__ . '/../../app/bootstrap.php'; crud_delete('chefs_service', (string)($_GET['id'] ?? ''));
