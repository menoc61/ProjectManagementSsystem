<?php
declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function app_url(string $path = ''): string
{
    return rtrim(APP_BASE_URL, '/') . '/' . ltrim($path, '/');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function consume_flash(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

function request_value(string $key, mixed $default = ''): mixed
{
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}

function format_money(mixed $amount): string
{
    return number_format((float)$amount, 0, ',', ' ') . ' FCFA';
}

function format_date(?string $date): string
{
    if (!$date) {
        return '-';
    }
    return date('d/m/Y', strtotime($date));
}

function project_status_label(int|string|null $status): string
{
    return match ((int)$status) {
        1 => "<span class=\"badge text-bg-primary\">En cours</span>",
        2 => "<span class=\"badge text-bg-success\">Termine</span>",
        3 => "<span class=\"badge text-bg-danger\">Annule</span>",
        default => "<span class=\"badge text-bg-secondary\">Non defini</span>",
    };
}

function task_status_label(int|string|null $status): string
{
    return match ((int)$status) {
        1 => "<span class=\"badge text-bg-secondary\">A faire</span>",
        2 => "<span class=\"badge text-bg-primary\">En cours</span>",
        3 => "<span class=\"badge text-bg-success\">Terminee</span>",
        4 => "<span class=\"badge text-bg-danger\">Annulee</span>",
        default => "<span class=\"badge text-bg-secondary\">Non defini</span>",
    };
}

function role_badge(string $role): string
{
    $class = match ($role) {
        ROLE_ADMIN => 'danger',
        ROLE_CHEF_PROJET => 'primary',
        ROLE_CHEF_SERVICE => 'info',
        ROLE_PERSONNEL => 'secondary',
        default => 'dark',
    };
    return '<span class="badge text-bg-' . $class . '">' . e(role_label($role)) . '</span>';
}
