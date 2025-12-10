<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php';

use App\Service\Config;

$config = new \App\Service\Config();

$dsn = Config::get('db_dsn');
$user = Config::get('db_user');
$pass = Config::get('db_pass');

try {
    $pdo = new \PDO($dsn, $user, $pass);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $pdo->exec('PRAGMA foreign_keys = ON;');

    echo "Usuwanie wszystkich rekordów z tabel: post i comment...\n";
    $pdo->exec('DELETE FROM comment;');
    $pdo->exec('DELETE FROM post;');
    echo "Dane usunięte.\n";

    $pdo->exec("UPDATE sqlite_sequence SET seq = 0 WHERE name = 'post'");
    echo "Licznik ID dla 'post' zresetowany.\n";

    // Reset licznika dla tabeli 'comment'
    $pdo->exec("UPDATE sqlite_sequence SET seq = 0 WHERE name = 'comment'");
    echo "Licznik ID dla 'comment' zresetowany.\n";

    echo "\nBaza danych gotowa.\n";

} catch (\PDOException $e) {
    echo "Blad bazy danych: " . $e->getMessage() . "\n";
} catch (\Exception $e) {
    echo "Blad: " . $e->getMessage() . "\n";
}
