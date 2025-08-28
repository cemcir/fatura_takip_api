<?php
function getPDO() {
    $host = "localhost";
    $db   = "fatura_db";
    $user = "root";
    $pass = "";
    $charset   = "utf8";
    $collation = "utf8_turkish_ci";

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);
    $pdo->exec("SET NAMES $charset COLLATE $collation");
    $pdo->exec("SET CHARACTER SET $charset");

    return $pdo;
}
