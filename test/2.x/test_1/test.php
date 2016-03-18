<?php

require_once '../../../vendor/autoload.php';
include '../../_preinit.php';


use Z\Phalcon\Mvc\View\Engine\XSLT;
use LSS\XML2Array;
use LSS\Array2XML;


/**
 * Setting up the view component
 */
$di->set('view', function () {

    $view = new \Phalcon\Mvc\View();

    $view->setViewsDir('views/');

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
}, true);



$view = $di->get('view');


// Load test xml as array:
$test_params = XML2Array::createArray(file_get_contents('../test_1/users.xml'));

echo $view->getRender('products', 'list',
    $test_params,
    function($view) {
        //Set any extra options here
        $view->setViewsDir("views/");

        // Cache this view for 1 hour with random key:
        $datetime = explode('.', microtime(true));
        $datetime = date('Y-m-d-H-i-s-', $datetime[0]) . str_pad($datetime[1], 4, '0');

        $view->cache(array(
            "lifetime" => 3600,
            'key' => $datetime
        ));
    }
);
