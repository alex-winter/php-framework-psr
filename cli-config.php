<?php

require 'vendor/autoload.php';

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;

$config = new PhpFile('migrations.php'); 

$loadConfig = require_once __DIR__ . '/bin/load-config.php';

$entityManager = $loadConfig['dependencies']['entity-manager']();

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));