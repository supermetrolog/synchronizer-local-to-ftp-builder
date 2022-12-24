<?php

declare(strict_types=1);

namespace Supermetrolog\SynchronizerLocalToFTPBuilder;

use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;
use League\Flysystem\Ftp\FtpConnectionProvider;
use Psr\Log\LoggerInterface;
use Supermetrolog\Synchronizer\Repositories;
use Supermetrolog\Synchronizer\Synchronizer;
use Supermetrolog\SynchronizerFilesystemSourceRepo\Filesystem;
use Supermetrolog\SynchronizerFilesystemSourceRepo\path\AbsPath;
use Supermetrolog\SynchronizerFilesystemSourceRepo\FilesystemRepository;
use Supermetrolog\SynchronizerAlreadySyncRepo\AlreadySynchronizedRepository;
use Supermetrolog\SynchronizerFilesystemFTPTargetRepo\FilesystemFTPRepository;
use Supermetrolog\SynchronizerFilesystemFTPTargetRepo\FTPFilesystem;

class SynchronizerBuilder
{
    private LoggerInterface $logger;
    private AlreadySynchronizedRepository $alreadyRepo;
    private FilesystemRepository $sourceRepo;
    private FilesystemFTPRepository $targetRepo;

    public function build(): Synchronizer
    {
        $repositories = new Repositories(
            $this->alreadyRepo,
            $this->sourceRepo,
            $this->targetRepo
        );

        return new Synchronizer($repositories, $this->logger);
    }

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function setAlreadyRepo(FilesystemFTPRepository $repo, string $filename): self
    {
        $this->alreadyRepo = new AlreadySynchronizedRepository($repo, $filename);

        return $this;
    }

    public function setSourceRepo(string $directory): self
    {
        $this->sourceRepo = new FilesystemRepository(
            new AbsPath($directory),
            new Filesystem()
        );

        return $this;
    }

    public function setTargetRepo(
        string $host,
        string $root,
        string $username,
        string $password,
        int $port = 21
    ): self {
        $options = FtpConnectionOptions::fromArray([
            'host' => $host,
            'root' => $root,
            'username' => $username,
            'password' => $password,
            'port' => $port,
        ]);

        $provider = new FtpConnectionProvider();
        $connection = $provider->createConnection($options);

        $adapter = new FtpAdapter($options, $provider);
        $FTPFilesystem = new FTPFilesystem($adapter, $connection);

        $this->targetRepo = new FilesystemFTPRepository($FTPFilesystem);

        return $this;
    }
    public function getTargetRepo(): FilesystemFTPRepository
    {
        return $this->targetRepo;
    }

    public function getSourceRepo(): FilesystemRepository
    {
        return $this->sourceRepo;
    }

    public function getAlreadyRepo(): AlreadySynchronizedRepository
    {
        return $this->alreadyRepo;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
