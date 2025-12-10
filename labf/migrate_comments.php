<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php';

use App\Service\Config;

$config = new \App\Service\Config();

$dsn = Config::get('db_dsn');
$user = Config::get('db_user');
$pass = Config::get('db_pass');

$sql = "
CREATE TABLE comment (
    id INTEGER NOT NULL
        CONSTRAINT comment_pk
            PRIMARY KEY AUTOINCREMENT,
    post_id INTEGER NOT NULL,
    author TEXT NOT NULL,
    content TEXT NOT NULL,
    created_at TEXT NOT NULL,
    FOREIGN KEY (post_id) 
        REFERENCES post(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);
";

try {
    $pdo = new \PDO($dsn, $user, $pass);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    $pdo->exec($sql);

    echo "Tabela 'comment' utworzona!\n";

} catch (\PDOException $e) {
    echo "Blad bazy danych: " . $e->getMessage() . "\n";
} catch (\Exception $e) {
    echo "Blad ladowania konfiguracji: " . $e->getMessage() . "\n";
}
