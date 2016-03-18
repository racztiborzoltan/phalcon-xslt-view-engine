<?php

require_once '../../../vendor/autoload.php';
include '../../_preinit.php';


/**
 * Setting up the view component
 */
$di->set('view', function () {
    return testViewRegister(new \Phalcon\Mvc\View());
}, true);



$view = $di->get('view');


// Load test xml as array:
$test_params = \LSS\XML2Array::createArray(file_get_contents('../test_1/users.xml'));

echo $view->getRender('users', 'list',
    $test_params,
    function($view) {
        //Set any extra options here
        $view->setViewsDir("views/");

        if ($_GET['cache'] == 1) {
            // Cache this view for 1 hour with random key:
            $datetime = explode('.', microtime(true));
            $datetime = date('Y-m-d-H-i-s-', $datetime[0]) . str_pad($datetime[1], 4, '0');

            $view->cache(array(
                "lifetime" => 3600,
                'key' => $datetime
            ));
        }
    }
);
