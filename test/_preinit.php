<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Cache\Frontend\Output as OutputFrontend;
use Phalcon\Cache\Backend\File as FileBackend;
use Z\Phalcon\Mvc\View\Engine\XSLT;


$_GET['cache'] = isset($_GET['cache']) ? (int)$_GET['cache'] : 0;
$_GET['debug'] = isset($_GET['debug']) ? (int)$_GET['debug'] : 0;


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


/**
 * view register function for tests
 *
 * @param \Phalcon\Mvc\ViewBaseInterface $view new class instance
 * @return \Phalcon\Mvc\ViewBaseInterface
 */
function testViewRegister(\Phalcon\Mvc\ViewBaseInterface $view)
{
    $view->setViewsDir('views/');

    $eventsManager = new \Phalcon\Events\Manager();

    //
    // For XML debug
    //
    if ($_GET['debug'] == 1) {
        $eventsManager->attach('view:beforeRender', function ($event, \Phalcon\Mvc\ViewBaseInterface $view) {
            header('Content-Type: text/plain;charset=utf-8');
            echo XSLT::createXmlFromArray((array)$view->getParamsToView(), 'variables')->saveXML();
            exit();
        });
    }

    $view->setEventsManager($eventsManager);

    $view->registerEngines(array(
        '.xsl' => '\Z\Phalcon\Mvc\View\Engine\XSLT',
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
}



//
// Custom error handler for tests:
//
set_error_handler(function ($code, $message, $file = null, $line = null, $context = null) {

    // output buffer cleaning:
    while (ob_get_level() > 0 && ob_end_clean());

    throw new ErrorException( $message, 0, $code, $file, $line );
});

