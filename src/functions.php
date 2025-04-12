<?php

namespace App\functions;

use App\Container;

/**
 * @var App\Container $container
 */

function get(string $class) {
    return Container::getInstance()->get($class);
}