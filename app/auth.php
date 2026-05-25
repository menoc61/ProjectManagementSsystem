<?php
declare(strict_types=1);

function secure_session_start(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    if (PHP_SAPI === 'cli') {
        $_SESSION ??= [];
        return;
    }

    $sessionPath = dirname(__DIR__) . '/storage/sessions';
    if (is_dir($sessionPath) && is_writable($sessionPath)) {
        session_save_path($sessionPath);
    }

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id'], $_SESSION['role']);
}

function current_user_role(): string
{
    return (string)($_SESSION['role'] ?? '');
}

function current_user_name(): string
{
    return (string)($_SESSION['username'] ?? 'Utilisateur');
}

function role_label(string $role): string
{
    return match ($role) {
        ROLE_ADMIN => 'Administrateur',
        ROLE_CHEF_PROJET => 'Chef de projet',
        ROLE_CHEF_SERVICE => 'Chef de service',
        ROLE_PERSONNEL => 'Personnel',
        default => 'Invite',
    };
}

function has_role(array $roles): bool
{
    return in_array(current_user_role(), $roles, true);
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect(app_url('login.php'));
    }
}

function require_roles(array $roles): void
{
    require_login();

    if (!has_role($roles)) {
        flash('danger', "Vous n'avez pas l'autorisation d'acceder a cette fonctionnalite.");
        redirect(app_url('index.php'));
    }
}

function login_user(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$user['IdUtilisateur'];
    $_SESSION['username'] = $user['NomUtilisateur'];
    $_SESSION['role'] = $user['Role'];
    db_execute('UPDATE UTILISATEUR SET DerniereConnexion = NOW() WHERE IdUtilisateur = ?', [(int)$user['IdUtilisateur']]);
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', (bool)$params['secure'], (bool)$params['httponly']);
    }
    session_destroy();
}
