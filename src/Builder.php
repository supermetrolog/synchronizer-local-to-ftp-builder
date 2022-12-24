<?php

declare(strict_types=1);

namespace Supermetrolog\SynchronizerLocalToFTPBuilder;

class Builder
{
    public function create(): SynchronizerBuilder
    {
        return new SynchronizerBuilder();
    }
}
