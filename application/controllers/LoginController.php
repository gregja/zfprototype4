<?php

class LoginController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        Zend_Auth::getInstance ()->clearIdentity();
    }

    public function preDispatch() {
        if (Zend_Auth::getInstance ()->hasIdentity()) {
            if ('logout' != $this->getRequest()->getActionName()) {
                $this->_helper->redirector('index', 'index');
            }
        } else {
            if ('logout' == $this->getRequest()->getActionName()) {
                $this->_helper->redirector('index');
            }
        }
    }

    public function indexAction() {
        $fc = Zend_Controller_Front::getInstance();

        /*
         * transmission du nom du contrôleur
         * à la vue courante, pour faciliter le dialogue
         * entre vues et contrôleurs
         */
        $this->view->mvc_controller_name = $this->_request->controller;

        $form = new UserLoginForm ( );

        /*
         * Important : la méthode setEscape ci-dessous a permis d'éliminer le
         * message de type Warning suivant :
         *   Warning: htmlspecialchars() expects parameter 1 to be string, array
         *   given in C:\wamp\www\ZendFramework\library\Zend\View\Abstract.php
         *   on line 847
         * Une légère modification a néanmoins été nécessaire dans la classe
         * précitée pour obtenir un affichage correct des messages
         * d'erreur (cf. doc "lis_moi.txt")
         */
        $this->view->setEscape('utf8_encode');
        $this->view->formLogin = $form;

        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                if ($form->getValue('username')) {
                    $username = $form->getValue('username');
                } else {
                    $username = '';
                }
                if ($form->getValue('password')) {
                    $password = $form->getValue('password');
                } else {
                    $password = '';
                }
                $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter ());
                $authAdapter->setTableName('users')
                        ->setIdentityColumn('username')
                        ->setCredentialColumn('password')
                        ->setCredentialTreatment('MD5(?)')
                        ->setIdentity($username)
                        ->setCredential($password);
                $authAuthenticate = $authAdapter->authenticate();
                if ($authAuthenticate->isValid()) {
                    $storage = Zend_Auth::getInstance ()->getStorage();
                    $storage->write($authAdapter->getResultRowObject(null, 'password'));
                    $this->_helper->redirector('index', 'index');
                } else {
                    $form->addError("Il n'existe pas d'utilisateur avec ce mot de passe");
                }
            }
        }
        $this->render('index');
    }

    public function logoutAction() {
        Zend_Auth::getInstance ()->clearIdentity();
        $this->_helper->redirector('index', 'index');
    }

}
