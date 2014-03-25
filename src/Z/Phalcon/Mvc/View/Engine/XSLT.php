<?php
namespace Z\Phalcon\Mvc\View\Engine;

/**
 * Adapter to use XSLT as templating engine
 *
 * @author Rácz Tibor Zoltán <racztiborzoltan@gmail.com>
 *
 */
class XSLT extends \Phalcon\Mvc\View\Engine
{

    protected $_options = array(
        'phpFunctions' => array()
    );

    /**
     * Adapter constructor
     *
     * @param \Phalcon\Mvc\View $view
     * @param \Phalcon\DI $di
     */
    public function __construct($view, $di)
    {
        parent::__construct($view, $di);
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
     * Renders a view using the template engine
     *
     * @param string $path
     * @param array $params
     */
    public function render($path, $params, $mustClean = null)
    {
        // Convert parameters to XML:
        $xml = \Array2XML::createXML('variables', $params)->saveXML();

        // Create and load XML:
        $xmldoc = new \DOMDocument();
        $xmldoc->loadXML($xml);

        $xsldoc = new \DOMDocument();
        $xsldoc->load($path);

        $proc = new \XSLTProcessor();
        $proc->registerPHPFunctions($this->_options['phpFunctions']);
        $proc->importStyleSheet($xsldoc);
        $content = $proc->transformToXML($xmldoc);

        if ($mustClean) {
            $this->_view->setContent($content);
        } else {
            echo $content;
        }
    }
}