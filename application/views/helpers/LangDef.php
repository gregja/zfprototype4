<?php

class Zend_View_Helper_LangDef {

    function langDef() {
        $session = new Zend_Session_Namespace('resources');
        return $session->language;
    }

}