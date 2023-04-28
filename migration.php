<?php

require 'vendor/autoload.php';
if (file_exists('config/db.php')) {
    require 'config/db.php';
} else {
    require 'config/db.php.dist';
}

require 'config/config.php';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . '; charset=utf8',
        DB_USER,
        DB_PASSWORD
    );

    $pdo->exec('DROP DATABASE IF EXISTS ' . DB_NAME);
    $pdo->exec('CREATE DATABASE ' . DB_NAME);
    $pdo->exec('USE ' . DB_NAME);

    if (is_file(DB_DUMP_PATH) && is_readable(DB_DUMP_PATH)) {
        $sql = file_get_contents(DB_DUMP_PATH);
        $statement = $pdo->prepare($sql);
        $statement->execute();
    } else {
        echo DB_DUMP_PATH . ' file does not exist';
    }

    //copie du jeux de fichier dans le dossier upload pour les tests.
    $from = __DIR__ . "/data_migration/";
    $to =  __DIR__ . "/public/uploads/";
    $files = array_filter(glob("$from*"), "is_file");
    foreach ($files as $f) {
        copy($f, $to . basename($f));
    }
} catch (PDOException $exception) {
    echo $exception->getMessage();
}
