<?php

use App\RequestHandler\CreateItemHandler;
use App\RequestHandler\GetAllItemsHandler;
use App\RequestHandler\IndexRequestHandler;
use App\Service\ItemRepository;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\ORMSetup;

return [

    'dependencies' => [
        'entity-manager' => function () {
            $config = require_once __DIR__ . '/config.php';
            
            $paths = [__DIR__.'/../src/Entity'];
            
            $isDevMode = true;

            $ORMConfig = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

            $ORMConfig->setNamingStrategy(new UnderscoreNamingStrategy());

            $connection = DriverManager::getConnection($config['database']);

            return new EntityManager($connection, $ORMConfig);
        }
    ],

    'services' => [
        ItemRepository::class,
    ],

    'request-handlers' => [
        IndexRequestHandler::class,
        CreateItemHandler::class,
        GetAllItemsHandler::class,
    ],

];