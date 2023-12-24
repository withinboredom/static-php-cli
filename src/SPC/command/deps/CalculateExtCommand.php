<?php

declare(strict_types=1);

namespace SPC\command\deps;

use SPC\command\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('deps:calc-ext', '(vendor only) calculate required extensions for current project')]
class CalculateExtCommand extends BaseCommand
{
    public function handle(): int
    {
        // get vendor/composer/installed.json
        $file = WORKING_DIR . '/vendor/composer/installed.json';
        if (!file_exists($file)) {
            $this->output->writeln('<error>File vendor/composer/installed.json not exists!</error>');
            return self::FAILURE;
        }

        $file_content = file_get_contents($file);
        $json = json_decode($file_content, true);
        if (!is_array($json)) {
            $this->output->writeln('<error>installed.json is not a valid json content!</error>');
            return self::FAILURE;
        }

        // dev mode, remove dev-packages
        $dev_list = $json['dev'] === true ? $json['dev-package-names'] : [];

        foreach ($json['packages'] as $package) {
            if (in_array($package['name'], $dev_list)) {
                continue;
            }

            foreach ($package['require'] as $key => $item) {
                if (str_starts_with($key, 'ext-')) {
                    $ext_name = substr($key, 4);
                    $this->output->writeln("<comment>{$package['name']} requires extension [{$ext_name}]</comment>");
                }
            }
        }
        return self::SUCCESS;
    }
}
