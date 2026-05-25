<?php require_once __DIR__ . '/../../app/bootstrap.php'; crud_delete('projets', (string)($_GET['id'] ?? ''));
