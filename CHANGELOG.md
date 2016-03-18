
CHANGELOG
================================================================================


v2.0.0
--------------------------------------------------------------------------------

- code cleaning, easing
	- remove Phalcon 1.x compatibility
	- remove events from XSLT View Engine
	- remove 'defaultParameters' option
	- removed methods: 
		- \Z\Phalcon\Mvc\View\Engine->mergeParameters()
		- \Z\Phalcon\Mvc\View\Engine->getMustClean()
		- \Z\Phalcon\Mvc\View\Engine->setMustClean()
		- \Z\Phalcon\Mvc\View\Engine->getClean()
		- \Z\Phalcon\Mvc\View\Engine->setClean()
		- \Z\Phalcon\Mvc\View\Engine->getPath()
		- \Z\Phalcon\Mvc\View\Engine->setPath()
		- \Z\Phalcon\Mvc\View\Engine->getParameters()
		- \Z\Phalcon\Mvc\View\Engine->setParameters()
		- \Z\Phalcon\Mvc\View\Engine->removeParameters()
		- \Z\Phalcon\Mvc\View\Engine->getXMLPath()
		- \Z\Phalcon\Mvc\View\Engine->setXMLPath()
		- \Z\Phalcon\Mvc\View\Engine->setXMLDom()
		- \Z\Phalcon\Mvc\View\Engine->getInstanceId()
		- \Z\Phalcon\Mvc\View\Engine->getInstance()
	- removed properties
		- \Z\Phalcon\Mvc\View\Engine->_mustclean
		- \Z\Phalcon\Mvc\View\Engine->_eventsManager
		- \Z\Phalcon\Mvc\View\Engine->_path
		- \Z\Phalcon\Mvc\View\Engine->_parameters
		- \Z\Phalcon\Mvc\View\Engine->_content
		- \Z\Phalcon\Mvc\View\Engine->_xml_path
		- \Z\Phalcon\Mvc\View\Engine::$_instances
		- \Z\Phalcon\Mvc\View\Engine->_instanceId
		- \Z\Phalcon\Mvc\View\Engine->_xmldoc
	- simpler rendering