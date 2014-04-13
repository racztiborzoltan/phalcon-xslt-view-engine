<?php
//
// Original sample here: http://docs.phalconphp.com/en/latest/reference/views.html#hierarchical-rendering
//

require_once '../../vendor/autoload.php';
include '../_preinit.php';


use Z\Phalcon\Mvc\View\Engine\XSLT;


/**
 * Setting up the view component
*/
$di->set('view', function () {

    $view = new Phalcon\Mvc\View\Simple();

    $view->setViewsDir('views/');

    $view->registerEngines(array(
        '.xsl' => 'Z\Phalcon\Mvc\View\Engine\XSLT',
        // OR:
        '.xsl' => function ($view, $di) {

            $engine = new XSLT($view, $di);

            $eventsManager = new Phalcon\Events\Manager();

            // ---------------------------------------------
            // Example events:
            $eventsManager->attach('xslt-view-engine:beforeRender', function($event, XSLT $view_engine){
                // Get the correct class reference:
                $view_engine = XSLT::getInstance($view_engine->getInstanceId());

                $view_engine->setXMLPath('post_other.xml');
//                 // OR:
//                 $temp_dom = new DOMDocument();
//                 $temp_dom->load('post_other.xml');
//                 $view_engine->setXMLDom($temp_dom);
            });

//             $eventsManager->attach('xslt-view-engine:afterRender', function($event, XSLT $view_engine, $rendered_content){
//             });
            // ---------------------------------------------

            $engine->setEventsManager($eventsManager);

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
//Set any extra options here
$view->setViewsDir("views/");

// Cache this view for 1 hour with random key:
$datetime = explode('.', microtime(true));
$datetime = date('Y-m-d-H-i-s-', $datetime[0]) . str_pad($datetime[1], 4, '0');

$view->cache(array(
    "lifetime" => 3600,
    'key' => $datetime,
));


// test template variables:
$test_params = array(
	'postId' => mt_rand()
);

echo $view->render('index', $test_params);
