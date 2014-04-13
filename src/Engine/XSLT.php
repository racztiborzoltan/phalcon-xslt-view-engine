<?php
namespace Z\Phalcon\Mvc\View\Engine;

use Phalcon\Events\EventsAwareInterface;
/**
 * Adapter to use XSLT as templating engine
 *
 * @author Rácz Tibor Zoltán <racztiborzoltan@gmail.com>
 *
 */
class XSLT extends \Phalcon\Mvc\View\Engine implements EventsAwareInterface
{

    /**
     * Array of options
     *
     * Structure:
     * Array(
     *  //
     *  // List of enabled PHP function for XSLT files
     *  // Default: array()
     *  //
     *  'phpFunctions' => array(),
     *  //
     *  // Variable name (tag name) in generated XML, that contains the content of previous View in the View hierarchy
     *  // Default: '_getContent'
     *  //
     *  'prevContentTagName' => string
     *  //
     *  // Name of root node in temporarily generated XML
     *  // Default: 'variables'
     *  //
     * 'rootTagName' => string
     * //
     * // Default parameters for XSLT class
     * // Default: array()
     * // Structure:
     * //   Array(
     * //       'parameter_name' => 'parameter_value',
     * //       ...
     * //   )
     * //
     * 'defaultParameters' => array
     * )
     *
     * @var array
     */
    protected $_options = array(
        'phpFunctions' => array(),
        'prevContentTagName' => '_getContent',
        'rootTagName' => 'variables',
        'defaultParameters' => array()
    );

    protected $_content = null;

    protected $_path = null;

    protected $_parameters = array();

    protected $_mustclean = null;

    protected $_eventsManager;

    protected static $_instances = array();

    protected $_instanceId = null;

    /**
     * For ->render() method
     * @var \DOMDocument
     */
    protected $_xmldoc = null;

    /**
     * Path to the fix XML.
     * Received parameters are ignored
     * @var string
     */
    protected $_xml_path = null;


	/* (non-PHPdoc)
	 * @see \Phalcon\Mvc\View\Engine::__construct()
	 */
	public function __construct($view, $dependencyInjector = null)
	{
	    parent::__construct($view, $dependencyInjector);

	    $this->_instanceId = uniqid();
        self::$_instances[$this->_instanceId] = &$this;
	}

	public function getInstanceId()
	{
	    return $this->_instanceId;
	}

    /**
     *
     * @return \Z\Phalcon\Mvc\View\Engine\XSLT
     */
    public static function &getInstance($instanceId)
    {
        if (isset(self::$_instances[$instanceId]))
            return self::$_instances[$instanceId];
        else
            return null;
        ;
    }

    /**
     * Sets the events manager
     *
     * @param \Phalcon\Events\ManagerInterface $eventsManager
     */
    public function setEventsManager($eventsManager)
    {
        $this->_eventsManager = $eventsManager;
    }


    /**
     * Returns the internal event manager
     *
     * @return \Phalcon\Events\ManagerInterface
    */
    public function getEventsManager()
    {
        return $this->_eventsManager;
    }

    /**
     * Set options of XSLT engine
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        foreach ($this->_options as $key => $value) {
            if (array_key_exists($key, $options))
                $this->_options[$key] = $options[$key];
        }

        if (empty($this->_options['phpFunctions'])
            || (!is_array($this->_options['phpFunctions']) && !is_string($this->_options['phpFunctions']))
            )
            $this->_options['phpFunctions'] = array();
    }

    /**
     * Return options of XSLT engine
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Get path of XSL file
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * Set path of XSL file
     * @param string $path
     * @return \Z\Phalcon\Mvc\View\Engine\XSLT
     */
    public function setPath($path)
    {
        $this->_path = (string)$path;
        return $this;
    }

    /**
     * Get parameters and values
     * Structure of return value:
     * Array(
     *  'parameter_name' => 'value',
     *  ...
     * )
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * Override parameters and values
     * Structure of first parameter:
     * Array(
     *  'parameter_name' => 'value',
     *  ...
     * )
     *
     * @param array $parameters
     * @return \Z\Phalcon\Mvc\View\Engine\XSLT
     */
    public function setParameters(array $parameters)
    {
        $this->_parameters = $parameters;
        return $this;
    }

    /**
     * Merge additional parameters and values
     * @param array $parameters
     * @return \Z\Phalcon\Mvc\View\Engine\XSLT
     */
    public function mergeParameters(array $parameters)
    {
        $this->_parameters = array_merge($this->_parameters, $parameters);
        return $this;
    }

    /**
     * Remove all parameters
     * @return \Z\Phalcon\Mvc\View\Engine\XSLT
     */
    public function removeParameters()
    {
        $this->_parameters = array();
        return $this;
    }

    /**
     *
     * @return bool
     */
    public function getMustClean()
    {
        return $this->_mustclean;
    }

    /**
     *
     * @param bool $clean
     * @return \Z\Phalcon\Mvc\View\Engine\XSLT
     */
    public function setMustClean($mustClean)
    {
        $this->_mustclean = (bool)$mustClean;
        return $this;
    }

    /**
     * Equivalent to ->getMustClean() method
     * @deprecated
     * @return boolean
     */
    public function getClean()
    {
        return $this->getMustClean();
    }

    /**
     * Equivalent to ->setMustClean() method
     *
     * @deprecated
     * @param bool $clean
     * @return \Z\Phalcon\Mvc\View\Engine\XSLT
     */
    public function setClean($clean)
    {
        return $this->setMustClean($clean);
    }

    /**
     * Get path of fix XML for rendering
     * @return string
     */
    public function getXMLPath()
    {
        return $this->_xml_path;
    }

    /**
     * Set path of fix XML for rendering
     * @param string $xmlPath
     * @return \Z\Phalcon\Mvc\View\Engine\XSLT
     */
    public function setXMLPath($xmlPath)
    {
        $this->_xml_path = (string)$xmlPath;
        return $this;
    }

    /**
     * Set fix XML as instance of DOMDocument
     * @param \DOMDocument $xmlDom
     * @return \Z\Phalcon\Mvc\View\Engine\XSLT
     */
    public function setXMLDom(\DOMDocument $xmlDom)
    {
        $this->_xml_path = $xmlDom;
        return $this;
    }

    /**
     * Renders a view using the template engine
     *
     * @param string $path
     * @param array $params
     */
    public function render($path, $params, $mustClean = false)
    {
        $view = $this->getView();

        if (empty($params))
            $params = array();

        // Set values of parameters in class:
        $this->setPath($path);
        $this->mergeParameters(array_merge($this->_options['defaultParameters'], $params));
        $this->setMustClean($mustClean);

        // Add to template variables the content of previous View in View hierarchy:
        $prev_view_content = $view->getContent();
        $this->_parameters[$this->_options['prevContentTagName']] = $prev_view_content;

        if ($eventsManager = $this->getEventsManager())
            $eventsManager->fire('xslt-view-engine:beforeRender', $this);

        $this->xmldoc = new \DOMDocument();

        $xml_path = $this->getXMLPath();
        if (empty($xml_path))
        {
            // Convert parameters to XML:
            $xml = \Array2XML::createXML($this->_options['rootTagName'], $this->getParameters())->saveXML();

            // Load generated XML:
            $this->xmldoc->loadXML($xml);
        }
        else
        {
            if (is_string($this->_xml_path))
            {
                // Load XML:
                $this->xmldoc->load($xml_path);
            }
            elseif (is_object($this->_xml_path) && $this->_xml_path instanceof \DOMDocument)
            {
                $this->xmldoc = $this->_xml_path;
            }

            // Insert new tag with content of previous View
            $root_node = $this->xmldoc->documentElement;
            $prev_content_tag = $this->xmldoc->createElement($this->_options['prevContentTagName'], $prev_view_content);
            $root_node->appendChild($prev_content_tag);
            unset($root_node, $prev_content_tag);
        }

        if ($this->getMustClean() === true)
        {
            ob_clean();
        }

        // call the "real" render method:
        echo $content = $this->_render();

        if ($this->getMustClean() === true)
        {
            $view->setContent(ob_get_contents());
        }

        if ($eventsManager = $this->getEventsManager())
            $eventsManager->fire('xslt-view-engine:afterRender', $this, $content);

        return $content;
    }

    /**
     * Real render the XSLT transformation:
     *
     * @return string generated content
     */
    protected function _render()
    {
        // Load XSL file:
        $xsldoc = new \DOMDocument();
        $xsldoc->load($this->getPath());

        // Start output buffering for error messages:
        ob_start();

        // Generate the content:
        $proc = new \XSLTProcessor();
        $proc->registerPHPFunctions($this->_options['phpFunctions']);
        $proc->importStyleSheet($xsldoc);
        $content = $proc->transformToXML($this->xmldoc);

        // Dump errors:
        $error_outputs = ob_get_clean();
        if ($error_outputs !== '')
            exit($error_outputs);

        return $content;
    }
}