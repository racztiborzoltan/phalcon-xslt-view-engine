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
    return testViewRegister(new \Phalcon\Mvc\View());
}, true);



$view = $di->get('view');


// test template variables:
$test_params = array(
	'postId' => mt_rand()
);

echo $view->getRender('posts', 'show',
    $test_params,
    function($view){
        //Set any extra options here
        $view->setViewsDir("views/");
        $view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_MAIN_LAYOUT);

        if ($_GET['cache'] == 1) {
            // Cache this view for 1 hour with random key:
            $datetime = explode('.', microtime(true));
            $datetime = date('Y-m-d-H-i-s-', $datetime[0]) . str_pad($datetime[1], 4, '0');

            $view->cache(array(
                "lifetime" => 3600,
                'key' => $datetime,
                'level' => true
            ));
        }
    }
);
