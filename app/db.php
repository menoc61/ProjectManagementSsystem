<?php
declare(strict_types=1);

function db(): mysqli
{
    static $connection = null;

    if ($connection instanceof mysqli) {
        return $connection;
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $connection->set_charset(DB_CHARSET);
        return $connection;
    } catch (mysqli_sql_exception $exception) {
        http_response_code(500);
        exit("Connexion impossible a la base de donnees `" . DB_NAME . "`: " . htmlspecialchars($exception->getMessage()));
    }
}

function db_query(string $sql, array $params = []): mysqli_result|bool
{
    $statement = db()->prepare($sql);

    if ($params !== []) {
        $types = '';
        foreach ($params as $param) {
            $types .= is_int($param) ? 'i' : (is_float($param) ? 'd' : 's');
        }
        $statement->bind_param($types, ...$params);
    }

    $statement->execute();
    return $statement->get_result();
}

function db_one(string $sql, array $params = []): ?array
{
    $result = db_query($sql, $params);
    $row = $result instanceof mysqli_result ? $result->fetch_assoc() : null;
    return $row ?: null;
}

function db_all(string $sql, array $params = []): array
{
    $result = db_query($sql, $params);
    return $result instanceof mysqli_result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function db_execute(string $sql, array $params = []): bool
{
    return db_query($sql, $params) !== false;
}

function db_insert_id(): int
{
    return db()->insert_id;
}
