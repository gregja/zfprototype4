<?php

class Zend_View_Helper_EncodingDef {

    function encodingDef() {
        $session = new Zend_Session_Namespace('resources'); 
        return $session->charset;
    }

}