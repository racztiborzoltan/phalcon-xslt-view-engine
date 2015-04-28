<?php
namespace Z\Phalcon\Mvc\View\Engine;

use \Phalcon\Events\EventsAwareInterface;
use \LSS\XML2Array;
use \LSS\Array2XML;


/**
 * Abstract XSLT View engine class for Phalcon compatibility
 *
 * @author Rácz Tibor Zoltán <racztiborzoltan@gmail.com>
 *
 */
if (version_compare(\Phalcon\Version::get(), '1.0.0', '>=') && version_compare(\Phalcon\Version::get(), '2.0.0', '<')) {

    //
    // Phalcon v1.x compatible abstract class:
    //
    abstract class XSLTAbstract extends \Phalcon\Mvc\View\Engine implements EventsAwareInterface
    {

        /**
         * Sets the events manager
         *
         * @param \Phalcon\Events\ManagerInterface $eventsManager
         */
        public function setEventsManager($eventsManager)
        {
            $this->_eventsManager = $eventsManager;
        }
    }

} else {

    //
    // Phalcon v2.0.0 compatible abstract class:
    //
    abstract class XSLTAbstract extends \Phalcon\Mvc\View\Engine implements EventsAwareInterface
    {

        /**
         * Sets the events manager
         *
         * @param \Phalcon\Events\ManagerInterface $eventsManager
         */
        public function setEventsManager(\Phalcon\Events\ManagerInterface $eventsManager)
        {
            $this->_eventsManager = $eventsManager;
        }
    }

}
