<?php

declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Supermetrolog\SynchronizerLocalToFTPBuilder\Builder;

require __DIR__ . "/../vendor/autoload.php";
$secrets = include "secrets.php";

$logger = new Logger("app");
$logger->pushHandler(new StreamHandler(STDOUT));

$logger->info("start build");

$buidler = (new Builder())->create();

$buidler
    ->setLogger($logger)
    ->setSourceRepo("C:\Users\billy\OneDrive\Рабочий стол\datetimeforsync")
    ->setTargetRepo(
        $secrets['host'],
        '/',
        $secrets['username'],
        $secrets['password'],
    )
    ->setAlreadyRepo($buidler->getTargetRepo(), 'sync-file.data');

$synchronizer = $buidler->build();

$logger->info("start load");
$synchronizer->load();

$logger->info("start sync");
$synchronizer->sync();

// $hash = hash_file('md5', 'C:/Users/billy/OneDrive/Рабочий стол/datetimeforsync/docker/mysql/data/mysql.sock');
// var_dump($hash);

// var_dump(is_readable(__DIR__ . "/test.php"));
// var_dump(file_get_contents('C:/Users/billy/OneDrive/Рабочий стол/datetimeforsync/docker/mysql/data/mysql.sock'));
