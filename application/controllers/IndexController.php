<?php

class IndexController extends Zend_Controller_Action {

    public function indexAction() {

        $fc = Zend_Controller_Front::getInstance();

        $registry = Zend_Registry::getInstance();
        $this->view->naviglobal = $registry->get('naviglobal_data');

        $this->view->title = $registry::get('APP_TITLE');
    }

}
