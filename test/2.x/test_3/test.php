<?php
//
// Original sample here: http://docs.phalconphp.com/en/latest/reference/views.html#hierarchical-rendering
//

require_once '../../../vendor/autoload.php';
include '../../_preinit.php';



/**
 * Setting up the view component
*/
$di->set('view', function () {
    return testViewRegister(new \Phalcon\Mvc\View\Simple());
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
	'postId' => mt_rand(),
);

echo $view->render('index', $test_params);
