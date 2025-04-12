<?php

use App\CreateItemHandler;
use App\RequestHandler\IndexRequestHandler;

return [

    'request-handlers' => [
        IndexRequestHandler::class,
        CreateItemHandler::class,
    ],

];