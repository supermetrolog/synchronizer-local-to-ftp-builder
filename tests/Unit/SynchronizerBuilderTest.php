<?php

declare(strict_types=1);

namespace tests\unit;

use League\Flysystem\Ftp\UnableToConnectToFtpHost;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Supermetrolog\SynchronizerFilesystemFTPTargetRepo\FilesystemFTPRepository;
use Supermetrolog\SynchronizerLocalToFTPBuilder\Builder;
use Supermetrolog\SynchronizerLocalToFTPBuilder\SynchronizerBuilder;

class SynchronizerBuilderTest extends TestCase
{
    private LoggerInterface $logger;
    private FilesystemFTPRepository $targetRepo;

    public function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->targetRepo = $this->createMock(FilesystemFTPRepository::class);
    }

    public function testSetLogger(): void
    {
        $builder = (new Builder())->create();
        $this->assertInstanceOf(SynchronizerBuilder::class, $builder->setLogger($this->logger));
    }

    public function testAlreadyRepo(): void
    {
        $builder = (new Builder())->create();
        $this->assertInstanceOf(
            SynchronizerBuilder::class,
            $builder->setAlreadyRepo(
                $this->targetRepo,
                "sync-file.data"
            )
        );
    }
    public function testSourceRepo(): void
    {
        $builder = (new Builder())->create();
        $this->assertInstanceOf(SynchronizerBuilder::class, $builder->setSourceRepo(__DIR__));
    }
    public function testTargetRepo(): void
    {
        $builder = (new Builder())->create();
        $this->expectException(UnableToConnectToFtpHost::class);
        $builder->setTargetRepo(
            'host',
            'root',
            'username',
            'password'
        );
    }
}
