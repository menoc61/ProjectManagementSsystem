<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/entities.php';
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/crud.php';

secure_session_start();
$conn = db();
