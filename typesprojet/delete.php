<?php require_once __DIR__ . '/../app/bootstrap.php'; crud_delete('typesprojet', (string)($_GET['id'] ?? ''));
