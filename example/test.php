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
    ->setSourceRepo("/home/directoryforsync")
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
$logger->info("end load");

$logger->info("start sync");
$synchronizer->sync();
$logger->info("end sync");
