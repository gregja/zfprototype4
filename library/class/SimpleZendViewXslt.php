<?php
require_once dirname(__FILE__).'/SimpleXMLArrayHelper.php';
/**
 * @uses SimpleXMLArrayHelper
 * @uses XSLTProcessor
 * @uses DOMDocument
 * @author Shvakov Kirill shvakov@gmail.com
 *         http://code.google.com/p/php-simple-utility/
 * @category utility
 * @version 0.1 
 */
class SimpleZendViewXslt extends Zend_View_Abstract {
    private
        $xsltProcessor        = null,
        $templatePath         = null,
        $defaultTemplate      = null,
        $currentTemplate      = null,
        $rootNode             = 'result',
        $attributes           = array(),
        $arrayData            = array(),
        $pendingData          = array(),
        $usePendingData       = True,
        $applicationTimeStart = 0,
        $xml                  = null;
    protected function _run() {}
    /**
     * @param string $templatePath
     * @throws Zend_View_Exception
     * @return void
     */
    public function __construct($templatePath = null) {
        $this->applicationTimeStart = self :: getMicrotime();
        $this->xsltProcessor        = new XSLTProcessor;
        if ($templatePath) {
            if (is_readable($templatePath)) {
                $this->setScriptPath($templatePath);
                $this->templatePath = $templatePath;
                return;
            }
            throw new Zend_View_Exception('XSLT :: Invalid template dir path ' . $templatePath);
        }
    }
    /**
     * @param undefined
     * @return float
     */
    private static function getMicrotime() {
        list($usec, $sec) = explode(' ',microtime());
        return (float)$usec + (float)$sec;
    }
    /**
     * @param undefined
     * @return XSLTProcessor
     */
    public function getEngine() {
        return $this->xsltProcessor;
    }
    /**
     * @param string $defaultTemplate
     * @return void
     */
    public function setDefaultTemplate($defaultTemplate) {
        $this->defaultTemplate = $defaultTemplate;
    }
    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value) {
        $value = is_string($value) ? array('value' => $value) : (array) $value;
        $this->arrayData = array_merge(array((string) $name => $value), $this->arrayData);
    }
    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        return isset($this->arrayData[$name]) ? $this->arrayData[$name] : False;
    }
    /**
     * @param string $name
     * @return void
     */
    public function __unset($name) {
        if (isset($this->arrayData[$name])) {
            unset($this->arrayData[$name]);
        }
    }
    /**
     * @param undefined
     * @return void
     */
    public function clearVars() {
        $this->arrayData   = array();
        $this->pendingData = array();
    }
    /**
     * @param boolean $use
     * @return void
     */
    public function usePendingData($use = True) {
        $this->usePendingData = (boolean) $use;
    }
    /**
     * @param array
     * @return void
     */
    public function setPendingData(array $pendingData = array()) {
        $this->pendingData = $pendingData;
    }
    /**
     * @param string $rootNode
     * @param array $array
     * @param array $attributes
     * @return void
     */
    public function setArray($rootNode, array $array = array(), array $attributes = array()) {
        $this->arrayData  = array_merge($this->arrayData, $array);
        $this->rootNode   = $rootNode;
        $this->attributes = $attributes;
        $this->xml        = null;
    }
    /**
     * @param undefined
     * @return array
     */
    public function getArray() {
        return $this->arrayData;
    }
    /**
     * @param string $xml
     * @return void
     */
    public function setXml($xml) {
        $this->xml = trim($xml);
    }
    /**
     * @param string $xml
     * @return void
     */
    public function setFileXml($xml) {
        $this->xml = file_exists($xml) && is_readable($xml) ? file_get_contents($xml) : '';
    }
    
    /**
     * @param boolean $formatOutput
     * @return string
     */
    public function getXml($formatOutput = False) {
        $arrayData = $this->arrayData;
        if ($this->usePendingData && count($this->pendingData)) {
            $arrayData = array_merge($arrayData, $this->pendingData);
        }
        if (empty($this->xml)) {
            $sXml      = new SimpleXMLArrayHelper($this->rootNode, $this->attributes);
            $this->xml = $sXml->setArray($arrayData)->asXml();
        }
        if (!$formatOutput) {
            return $this->xml;
        }
        $xml = new DOMDocument;
        $xml->loadXML($this->xml);
        $xml->formatOutput = True;
        return $xml->saveXML();
    }
    /**
     * @param undefined
     * @throws Zend_View_Exception
     * @return void
     */
    private function transformToXML() {
        $xmlPrepareTimeStart = self :: getMicrotime();
        $templatePath        = null;
        $templates           = array(
            $this->currentTemplate,
            $this->defaultTemplate
        );
        $this->currentTemplate = null;
        
        foreach ($this->getScriptPaths() as $path) {
            foreach ($templates as $template) {
                if ($template !== null && file_exists($path . '/' . $template)) {
                    $templatePath = $path . '/' . $template;
                    break 2;
                }
            }
        }
        
        if ($templatePath === null) {
            throw new Zend_View_Exception(
                'XSLT :: Invalid view script path: ' . implode(', ', $templates) .
                '. Template include path: ' . implode('; ',$this->getScriptPaths())
            );
        }
        
        $xsl = new DOMDocument;
        $xsl->load($templatePath);
        
        $xml = new DOMDocument;
        $xml->loadXML($this->getXml());
        
        $this->xsltProcessor->setParameter('', 'applicationWorkTime', self :: getMicrotime() - $this->applicationTimeStart);
        $this->xsltProcessor->setParameter('', 'xmlPrepareTime',      self :: getMicrotime() - $xmlPrepareTimeStart);
        $this->xsltProcessor->importStyleSheet($xsl);
        echo $this->xsltProcessor->transformToXML($xml);
        exit;
    }
    /**
     * @param undefined
     * @return void
     */
    public function renderJson() {
        echo Zend_Json_Encoder :: encode($this->getArray());
        exit;
    }
    /**
     * @param string $currentTemplate
     * @return transformToXML
     */
    public function render($currentTemplate) {
        $this->currentTemplate = $currentTemplate;
        return $this->transformToXML();
    }
    /**
     * @param string $template
     * @return render
     */
    public function renderScript($template) {
        $this->render($template);
    }
}


