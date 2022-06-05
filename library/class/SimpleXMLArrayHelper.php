<?php
final class SimpleXMLArrayHelper {
    /**
     * @uses SimpleXMLElement
     * @see http://php.net/manual/en/class.simplexmlelement.php
     * @author Shvakov Kirill shvakov@gmail.com
	 *         http://code.google.com/p/php-simple-utility/
     * @category utility
     * @version 0.1 (11.03.2011)
     */
    private
        $rootNode   = 'root',
        $attributes = null,
        $simpleXml  = null;
    /**
     * @param string $rootNode
     * @param array $attributes
     * @return void
     */
    public function __construct($rootNode = 'root', array $attributes = array()) {
        $this->rootNode   = $rootNode;
        $this->attributes = $attributes;
    }
    /**
     * @param array $arrayData
     * @return object 
     */
    public function setArray(array $arrayData) {
        $this->simpleXml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><' . $this->rootNode . '></' . $this->rootNode . '>');
        if (is_array($this->attributes) && count($this->attributes)) {
            foreach ($this->attributes as $attribute => $attributeValue) {
                $this->simpleXml->addAttribute($attribute, (string) $attributeValue);
            }
        }
        $this->createXml($this->simpleXml, $arrayData);
        return $this;
    }
    /**
     * @param SimpleXMLElement $node
     * @param array $arrayData
     * @return void
     */
    private function createXml(SimpleXMLElement $node, array $arrayData) {
        foreach($arrayData as $nodeName => $data) {
            $value       = !empty($data['value']) ? $data['value'] : '';
            $currentNode = $node->addChild($nodeName, $value);
            if (!empty($data) && is_array($data)) {
                foreach ($data as $attribute => $attributeValue) {
                    if (!empty($attributeValue) && is_array($attributeValue)) {
                        foreach ($attributeValue as $child) {
                            if (!is_array($child)) {
                                continue;
                            }
                            $this->createXml($currentNode, $child);
                        }        
                        continue;
                    }
                    if (substr($attribute, 0, 1) != '@') {
                        continue;
                    }
                    $currentNode->addAttribute(substr($attribute, 1), $attributeValue);
                }
            }
        }
    }
    /**
     * @param string $xmlString
     * @return object
     */
    public function setXml($xmlString) {
        if (file_exists($xmlString)) {
            $xmlString = file_get_contents($xmlString);
        }
        $this->simpleXml = new SimpleXMLElement($xmlString);
        return $this;
    }
    /**
     * @param undefined
     * @return string
     */
    public function asXml() {
        return $this->simpleXml->asXML();
    }
    /**
     * @param undefined
     * @return array
     */
    public function asArray() {
        return self :: toArray($this->simpleXml);
    }
    /**
     * @param mixed array || SimpleXMLElement
     * @return array
     */
    private function toArray($objects) {
        $array   = array();
        $objects = (array) $objects;
        foreach ($objects as $key => $object) {
            if ($object instanceof SimpleXMLElement || is_array($object)) {
                $object = self :: toArray($object);
            }
            $array = array_merge($array, array($key => $object));
        }
        return $array;
    }
    /**
     * @param undefined
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ' :: __toString()';
    }
}