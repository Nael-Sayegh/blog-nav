<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $config): void {
    $config->paths([
        __DIR__,
        __DIR__.'/admin',
        __DIR__.'/api',
        __DIR__.'/gadgets',
        __DIR__.'/include',
        __DIR__.'/tasks',
        __DIR__.'/a',
        __DIR__.'/c',
        __DIR__.'/r',
        __DIR__.'/u',
        __DIR__.'/403',
        __DIR__.'/scripts',
    ]);

    $config->skip([
        __DIR__.'/admin/adminer/*',
        __DIR__.'/vendor/*',
        __DIR__.'/cache/*',
        __DIR__.'/include/lib/mtcaptcha/*',
        __DIR__.'/include/lib/facebook/vendor/*',
        __DIR__.'/include/lib/facebook/composer.json',
        __DIR__.'/include/lib/facebook/composer.lock',
    ]);

    $config->sets([LevelSetList::UP_TO_PHP_84]);

    $config->rule(Rector\Php80\Rector\Class_\StringableForToStringRector::class);
};
