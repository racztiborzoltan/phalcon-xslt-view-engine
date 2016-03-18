<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Cache\Frontend\Output as OutputFrontend;
use Phalcon\Cache\Backend\File as FileBackend;


$di = new FactoryDefault();


//Set the views cache service
$di->set('viewCache', function() {

    //Cache data for one day by default
    $frontCache = new OutputFrontend(array(
        "lifetime" => 86400
    ));

    $cacheDir = 'cache/';
    if (!is_dir($cacheDir))
        mkdir($cacheDir, 0755, true);

    // File cache settings
    $cache = new FileBackend($frontCache, array(
        'cacheDir' => $cacheDir
    ));

    return $cache;
});


//
// Custom error handler for tests:
//
set_error_handler(function ($code, $message, $file = null, $line = null, $context = null) {

    // output buffer cleaning:
    while (ob_get_level() > 0 && ob_end_clean());

    throw new ErrorException( $message, 0, $code, $file, $line );
});