<?php

class UserLoginForm extends Zend_Form { 

    public function __construct($options = null) {
        parent::__construct($options);

        $this->setName('login_user');
        $this->removeDecorator('errors');

        $decorators = array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            array('Errors', array('placement' => 'apend')),
            'Form'
                );

          $username = new Zend_Form_Element_Text('username');
          $username->setLabel('Code utilisateur : ')
          ->setAttrib('class', 'form-control')
          ->setRequired(true)
          ->addFilter('StripTags')
          ->addFilter('StringTrim')
          ->addValidator('StringLength', false, array(4, 20))
          ->addValidator('NotEmpty')
          ->removeDecorator('errors');

    /*
     * autre façon d'écrire la même chose (pour info)
          $this->addElement('text', 'username', array(
            'label' => 'Code utilisateur : ',
            'required' => true,
            'filters' => array('StripTags', 'StringTrim'),
            'validators' => array('NotEmpty', array('StringLength', false, array(0, 32))),
            'decorators' => $decorators,
            'description' => "Nécessite la saisie d'un code utilisateur correct."
        ));
    */

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Mot de passe : ')
                ->setAttrib('class', 'form-control')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('StringLength', false, array(4, 20))
                ->addValidator('NotEmpty')
                ->removeDecorator('errors');
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
                ->setAttrib('class', 'btn btn-primary')
                ->setLabel('Se connecter');

        $this->addElements(array($username, $password, $submit));

        $this->setDecorators($decorators);
    }

}
