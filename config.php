<?php
declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap.php';

function isLoggedIn(): bool
{
    return is_logged_in();
}

function isAdmin(): bool
{
    return current_user_role() === ROLE_ADMIN;
}

function redirectIfNotLoggedIn(): void
{
    require_login();
}

function redirectIfNotAdmin(): void
{
    require_roles([ROLE_ADMIN]);
}
