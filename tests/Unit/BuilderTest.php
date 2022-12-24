<?php

declare(strict_types=1);

namespace tests\unit;

use PHPUnit\Framework\TestCase;
use Supermetrolog\SynchronizerLocalToFTPBuilder\Builder;
use Supermetrolog\SynchronizerLocalToFTPBuilder\SynchronizerBuilder;

class BuilderTest extends TestCase
{
    public function testCreate(): void
    {
        $builder = new Builder();
        $this->assertInstanceOf(SynchronizerBuilder::class, $builder->create());
    }
}
