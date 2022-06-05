<?php

class Zend_View_Helper_ProfileLink {

    public $view;

    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }

    public function profileLink() {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $username = $auth->getIdentity()->username;
            return $username ;
        } else {
            return '' ;
        }
    }

}
