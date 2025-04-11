<?php

namespace App\functions;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Slim\App;

final class Load {
    public static function files(string $dir, App $app): void
    {
        $container = $app->getContainer();

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
        );
    
        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                require_once $file->getPathname();
            }
        }
    }
}
