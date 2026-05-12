<?php

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    ...((class_exists('MongoDB\Laravel\MongoDBServiceProvider')) ? [MongoDB\Laravel\MongoDBServiceProvider::class] : []),
];
