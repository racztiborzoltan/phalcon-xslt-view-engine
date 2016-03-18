<?php
namespace Z\Phalcon\Mvc\View\Engine;

use \LSS\Array2XML;
use Phalcon\Mvc\View\Engine;
use Phalcon\Mvc\View\EngineInterface;

/**
 * Adapter to use XSLT as templating engine
 *
 * @author Rácz Tibor Zoltán <racztiborzoltan@gmail.com>
 *
 */
class XSLT extends Engine implements EngineInterface
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
     * )
     *
     * @var array
     */
    protected $_options = array(
        'phpFunctions' => array(),
        'prevContentTagName' => '_getContent',
        'rootTagName' => 'variables',
    );

    protected $_parameters = array();

    /**
     * Set options of XSLT engine
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->_options = array_merge($this->_options, $options);

        if (!is_array($this->_options['phpFunctions'])) {
            throw new \Phalcon\Mvc\View\Exception('"phpFunctions" config value must be an array!');
        }

        if (!is_string($this->_options['prevContentTagName'])) {
            throw new \Phalcon\Mvc\View\Exception('"prevContentTagName" config value must be an string!');
        }

        if (!is_string($this->_options['rootTagName'])) {
            throw new \Phalcon\Mvc\View\Exception('"rootTagName" config value must be an string!');
        }
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

    public static function createXmlFromArray(array $array, $rootTagName)
    {
        return Array2XML::createXML($rootTagName, $array);
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

        // Add to template variables the content of previous View in View hierarchy:
        $prev_view_content = $view->getContent();
        $params[$this->_options['prevContentTagName']] = $prev_view_content;

        // Convert parameters to XML:
        $xml = static::createXmlFromArray($params, $this->_options['rootTagName'])->saveXML();

        // Load generated XML:
        $xmldoc = new \DOMDocument();
        $xmldoc->loadXML($xml);

        if ($mustClean === true) {
            ob_clean();
        }

        // call the "real" render method:
        echo $this->_render($xmldoc, $path);

        if ($mustClean === true) {
            $view->setContent(ob_get_contents());
        }
    }

    /**
     * Real XSLT transformation:
     *
     * @return string generated content
     */
    protected function _render(\DOMDocument $xmldoc, $path)
    {
        // Load XSL file:
        $xsldoc = new \DOMDocument();
        $xsldoc->load($path);

        // Generate the content:
        $proc = new \XSLTProcessor();
        $proc->registerPHPFunctions($this->_options['phpFunctions']);
        $proc->importStyleSheet($xsldoc);
        $content = $proc->transformToXML($xmldoc);

        return $content;
    }
}
