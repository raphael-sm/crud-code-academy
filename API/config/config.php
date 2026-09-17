<?php


try {
    $db_conn = mysqli_init();
    $db_conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);
    $db_conn->real_connect('database', 'root', '12062009--ra', 'projeto_crud');
} catch (\mysqli_sql_exception $e) {
    error_log((string) $e);
    http_response_code(503);
    echo json_encode(['error' => 'Database unavailable']);
    exit;
}

$allowedOrigins = [
    'http://0.0.0.0:8080',
    'http://localhost:8080',
    'http://127.0.0.1:8080',
    'http://0.0.0.0:5500',
    'http://localhost:5500',
    'http://127.0.0.1:5500',
];