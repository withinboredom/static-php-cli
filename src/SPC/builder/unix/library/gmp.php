<?php

declare(strict_types=1);

namespace SPC\builder\unix\library;

use SPC\exception\FileSystemException;
use SPC\exception\RuntimeException;

trait gmp
{
    /**
     * @throws FileSystemException
     * @throws RuntimeException
     */
    protected function build(): void
    {
        shell()->cd($this->source_dir)
            ->exec(
                $this->getDefaultFlags() . ' ./configure ' .
                '--enable-static --disable-shared ' .
                '--prefix='
            )
            ->exec('make clean')
            ->exec($this->getDefaultFlags() . " make -j{$this->builder->concurrency}")
            ->exec('make install DESTDIR=' . BUILD_ROOT_PATH);
        $this->patchPkgconfPrefix(['gmp.pc']);
    }
}
