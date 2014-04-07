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

        if (empty($this->_options['phpFunctions']))
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
        $this->_path = $path;
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
        $this->_mustclean = $mustClean;
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
     * Renders a view using the template engine
     *
     * @param string $path
     * @param array $params
     */
    public function render($path, $params, $mustClean = null)
    {
        $view = $this->getView();

        // Add to template variables the content of previous View in View hierarchy:
        $params[$this->_options['prevContentTagName']] = $view->getContent();

        // Set values of parameters in class:
        $this->setPath($path);
        $this->mergeParameters(array_merge($this->_options['defaultParameters'], $params));
        $this->setMustClean($mustClean);

        $this->_eventsManager->fire('xslt-view-engine:beforeRender', $this);

        // Convert parameters to XML:
        $xml = \Array2XML::createXML($this->_options['rootTagName'], $params)->saveXML();

        // Create and load XML:
        $xmldoc = new \DOMDocument();
        $xmldoc->loadXML($xml);

        $xsldoc = new \DOMDocument();
        $xsldoc->load($path);

        $proc = new \XSLTProcessor();
        $proc->registerPHPFunctions($this->_options['phpFunctions']);
        $proc->importStyleSheet($xsldoc);
        $content = $proc->transformToXML($xmldoc);

        $this->_eventsManager->fire('xslt-view-engine:afterRender', $this, $content);

        if ($view instanceof \Phalcon\Mvc\View)
            if ($view->isCaching()) {
                $view->setContent($content);
                echo $content;
                return;
            }

        if ($this->getMustClean()) {
            $view->setContent($content);
        } else {
            echo $content;
        }

        return $content;
    }
}