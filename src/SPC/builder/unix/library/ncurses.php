<?php

declare(strict_types=1);

namespace SPC\builder\unix\library;

trait ncurses
{
    protected function build(): void
    {
        shell()->cd($this->source_dir)
            ->exec(
                $this->getDefaultFlags() . ' ./configure ' .
                '--enable-static ' .
                '--disable-shared ' .
                '--enable-overwrite ' .
                '--with-curses-h ' .
                '--enable-pc-files ' .
                '--enable-echo ' .
                // '--enable-widec ' .
                '--with-normal ' .
                '--with-ticlib ' .
                '--without-tests ' .
                '--without-dlsym ' .
                '--without-debug ' .
                '-enable-symlinks ' .
                '--bindir=' . BUILD_ROOT_PATH . '/bin ' .
                '--includedir=' . BUILD_ROOT_PATH . '/include ' .
                '--libdir=' . BUILD_ROOT_PATH . '/lib ' .
                '--prefix=' . BUILD_ROOT_PATH
            )
            ->exec('make clean')
            ->exec($this->getDefaultFlags() . " make -j{$this->builder->concurrency}")
            ->exec('make install');
    }
}
