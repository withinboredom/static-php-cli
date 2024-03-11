<?php

declare(strict_types=1);

namespace SPC\builder\unix\library;

trait libsodium
{
    protected function build(): void
    {
        $root = BUILD_ROOT_PATH;
        shell()->cd($this->source_dir)
            ->exec($this->getDefaultFlags() . " ./configure --enable-static --disable-shared --prefix={$root}")
            ->exec('make clean')
            ->exec("make -j{$this->builder->concurrency}")
            ->exec('make install');
    }
}
