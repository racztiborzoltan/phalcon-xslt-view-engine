phalcon-xslt-view-engine
========================

Adapter to use XSLT as templating engine for Phalcon PHP framework

Usages in ```test``` directory.

## Example

	$di->set('view', function () {
		// ...	
	    $view->registerEngines(array(
	        '.xsl' => '\Z\Phalcon\Mvc\View\Engine\XSLT',
	        //
	        // OR:
	        //
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
		// ...	
	    return $view;
	}, true);

## License

Released under MIT license.


## CHANGELOG

### v1.x

- The past ...

### v2.0.0

- code cleaning, easing
	- remove Phalcon 1.x compatibility
	- remove events from XSLT View Engine
	- remove 'defaultParameters' option
	- removed methods: 
		- \...\XSLT->mergeParameters()
		- \...\XSLT->getMustClean()
		- \...\XSLT->setMustClean()
		- \...\XSLT->getClean()
		- \...\XSLT->setClean()
		- \...\XSLT->getPath()
		- \...\XSLT->setPath()
		- \...\XSLT->getParameters()
		- \...\XSLT->setParameters()
		- \...\XSLT->removeParameters()
		- \...\XSLT->getXMLPath()
		- \...\XSLT->setXMLPath()
		- \...\XSLT->setXMLDom()
		- \...\XSLT->getInstanceId()
		- \...\XSLT->getInstance()
	- removed properties
		- \...\XSLT->_mustclean
		- \...\XSLT->_eventsManager
		- \...\XSLT->_path
		- \...\XSLT->_parameters
		- \...\XSLT->_content
		- \...\XSLT->_xml_path
		- \...\XSLT::$_instances
		- \...\XSLT->_instanceId
		- \...\XSLT->_xmldoc
	- simpler rendering
	- simple xml debug option
- new methods:
	- \...\XSLT::createXmlFromArray()
