<?php

require_once __DIR__ . '/../config/config.php';

function loadUser(mysqli $db_conn, int $id): array
{
    $stmt = $db_conn->prepare("SELECT id, name, age, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $stmt->close();

    return $user ?: null;
}


function loadAll()
{
    global $db_conn;

    $result = $db_conn->query("SELECT * FROM users");
    $array = $result->fetch_all(MYSQLI_ASSOC);

    return $array;
}

function insertUser(array $user): array
{
    global $db_conn;

    $stmt = $db_conn->prepare("INSERT INTO users(name, age, email) VALUES (?, ?, ?)");

    $stmt->bind_param("sis", $user['name'], $user['age'], $user['email']);
    $stmt->execute();

    $user['id'] = $db_conn->insert_id;

    $stmt->close();

    return $user;
}

function updateUser(int $id, array $fields): ?array
{
    global $db_conn;

    if (empty($fields)) {
        return loadUser($db_conn, $id);
    }

    $setParts = [];
    $types = "";
    $values = [];

    $allowedFields = ['name', 'age', 'email'];

    foreach ($allowedFields as $field) {
        if (array_key_exists($field, $fields)) {
            $setParts[] = "$field = ?";
            $types .= ($field === 'age') ? "i" : "s";
            $values[] = $fields[$field];
        }
    }

    if (empty($setParts)) {
        return loadUser($db_conn, $id);
    }

    $values[] = $id;
    $types .= "i";

    $query = "UPDATE users SET " . implode(", ", $setParts) . " WHERE ID = ?";
    $stmt = $db_conn->prepare($query);
    $stmt->bind_param($types, ...$values);

    $stmt->execute();

    $stmt->close();

    return loadUser($db_conn, $id);
}

function deleteUser(int $id): ?array
{
    global $db_conn;

    $user = loadUser($db_conn, $id);

    if (!$user) {
        return null;
    }

    $stmt = $db_conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    return $user;
}