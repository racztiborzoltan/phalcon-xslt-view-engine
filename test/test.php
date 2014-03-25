<?php

require_once '../vendor/autoload.php';

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Z\Phalcon\Mvc\View\Engine\XSLT;


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


$view = $di->get('view');

// Load test xml as array:
$test_params = XML2Array::createArray(file_get_contents('users.xml'));

echo $view->getRender('products', 'list',
    $test_params,
    function($view) {
        //Set any extra options here
        $view->setViewsDir("views/");
        $view->setRenderLevel(Phalcon\Mvc\View::LEVEL_MAIN_LAYOUT);
    }
);
