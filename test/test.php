<?php

require_once '../vendor/autoload.php';

use
    Phalcon\DI\FactoryDefault,
    Phalcon\Mvc\View,
    Phalcon\Cache\Frontend\Output as OutputFrontend,
    Phalcon\Cache\Backend\File as FileBackend,
    Z\Phalcon\Mvc\View\Engine\XSLT;


$di = new FactoryDefault();
/**
 * Setting up the view component
*/
$di->set('view', function () {

    $view = new View();

    $view->setViewsDir('views/');

    $view->registerEngines(array(
        '.xsl' => 'Z\Phalcon\Mvc\View\Engine\XSLT',
        // OR:
        '.xsl' => function ($view, $di) {

            $engine = new XSLT($view, $di);

            $engine->setOptions(array(
                'phpFunctions' => array(
                	'ucfirst'
                ),
            ));

            return $engine;
        }
    ));

    return $view;
}, true);


//Set the views cache service
$di->set('viewCache', function() {

    //Cache data for one day by default
    $frontCache = new OutputFrontend(array(
        "lifetime" => 86400
    ));

    $cacheDir = 'cache/';
    if (!is_dir($cacheDir))
        mkdir($cacheDir, 0755, true);

    //Memcached connection settings
    $cache = new FileBackend($frontCache, array(
        'cacheDir' => $cacheDir
    ));

    return $cache;
});


$view = $di->get('view');


// Load test xml as array:
$test_params = XML2Array::createArray(file_get_contents('users.xml'));

echo $view->getRender('products', 'list',
    $test_params,
    function($view) {
        //Set any extra options here
        $view->setViewsDir("views/");
        $view->setRenderLevel(Phalcon\Mvc\View::LEVEL_MAIN_LAYOUT);

        // Cache this view for 1 hour
        $view->cache(array(
            "lifetime" => 3600
        ));
    }
);
