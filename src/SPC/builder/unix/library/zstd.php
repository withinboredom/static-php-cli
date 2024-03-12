<?php

declare(strict_types=1);

namespace SPC\builder\unix\library;

use SPC\exception\FileSystemException;
use SPC\exception\RuntimeException;
use SPC\store\FileSystem;

trait zstd
{
    /**
     * @throws RuntimeException
     * @throws FileSystemException
     */
    protected function build(): void
    {
        FileSystem::resetDir($this->source_dir . '/build/cmake/build');
        shell()->cd($this->source_dir . '/build/cmake/build')
            ->exec(
                $this->getDefaultFlags() . ' cmake ' .
                "{$this->builder->makeCmakeArgs()} " .
                '-DZSTD_BUILD_STATIC=ON ' .
                '-DZSTD_BUILD_SHARED=OFF ' .
                '..'
            )
            ->exec($this->getDefaultFlags() . " cmake --build . -j {$this->builder->concurrency}")
            ->exec('make install DESTDIR=' . BUILD_ROOT_PATH);
        $this->patchPkgconfPrefix(['libzstd.pc']);
    }
}
