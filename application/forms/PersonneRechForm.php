<?php

/*
 * version avec élimination des décorateurs
 */

class PersonneRechForm extends Zend_Form {

    public function __construct($options = null) {
        parent::__construct($options);

        $this->setMethod('post')
             ->setAction('')
             ->setName('PersonneRechForm');

        $fc = Zend_Controller_Front::getInstance();

        $this->setName('PersonneRecherche');
        $this->removeDecorator('label');
        $this->removeDecorator('htmlTag');
        $this->removeDecorator('description');
        $this->removeDecorator('errors');

        $liste_fields = array();
        $typ_form_rech = Zend_Registry::get('typ_form_rech_personnes');

        if ($typ_form_rech == 2) {
            /*
            * recherche de type "données métier"
            */
            $var_cod_per = new Zend_Form_Element_Text('rech_codper');
            $var_cod_per->setLabel('Code personne: ')
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->addValidator('StringLength', false, array(0, 3))
                    ->addValidator('NotEmpty')
                    ->setAttrib('class', 'form-control');
            $liste_fields [] = $var_cod_per;

            $var_mot_gener = new Zend_Form_Element_Text('rech_libper');
            $var_mot_gener->setAttrib('size', 30)
                    ->setLabel('Nom et prénom : ')
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->addValidator('StringLength', false, array(0, 80))
                    ->setAttrib('class', 'form-control');
            $liste_fields [] = $var_mot_gener;

        } else {
            /*
             * recherche de type "datastructure"
             */

            $var_zon_rech = new Zend_Form_Element_Select('zon_rech');
            $var_zon_rech->removeDecorator('label');
            $var_zon_rech->removeDecorator('htmlTag');
            $var_zon_rech->removeDecorator('description');
            $var_zon_rech->removeDecorator('errors');
            $var_zon_rech->setLabel('Zone de recherche: ')
                    ->setRequired(true)
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->addValidator('StringLength', false, array(0, 80))
                    ->addValidator('NotEmpty');

            /*
             * les clés du tableau ci-dessous doivent correspondre à des
             * noms de colonnes de la table SQL sous-jacente
             */
            $array = array(
                'code' => 'Code',
                'libelle' => 'Nom et prénom'
            );
            $var_zon_rech->setMultiOptions($array);

            $var_typ_rech = new Zend_Form_Element_Select('typ_rech');
            $var_typ_rech->removeDecorator('label');
            $var_typ_rech->removeDecorator('htmlTag');
            $var_typ_rech->removeDecorator('description');
            $var_typ_rech->removeDecorator('errors');
            $var_typ_rech->setLabel('Type de recherche: ')
                    ->setRequired(true)
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->addValidator('StringLength', false, array(0, 10))
                    ->addValidator('NotEmpty');
            $var_typ_rech->setMultiOptions(CrudOutils::SelectorSQL());

            $var_val_rech = new Zend_Form_Element_Text('val_rech');
            $var_val_rech->removeDecorator('label');
            $var_val_rech->removeDecorator('htmlTag');
            $var_val_rech->removeDecorator('description');
            $var_val_rech->removeDecorator('errors');
            $var_val_rech->setLabel('Valeur de recherche: ')
                    ->addFilter('StripTags')
                    ->addFilter('StringTrim')
                    ->addValidator('StringLength', false, array(0, 80));

            $liste_fields = array($var_zon_rech, $var_typ_rech, $var_val_rech);
        }

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->removeDecorator('Label');
        $submit->removeDecorator('HtmlTag');
        $submit->removeDecorator('description');
        $submit->removeDecorator('errors');
        $submit->removeDecorator('DtDdWrapper');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setAttrib('class', "btn btn-primary btn-sm");

        $export_xls = new Zend_Form_Element_Submit('exportxls');
        $export_xls->removeDecorator('Label');
        $export_xls->removeDecorator('HtmlTag');
        $export_xls->removeDecorator('description');
        $export_xls->removeDecorator('errors');
        $export_xls->removeDecorator('DtDdWrapper');
        $export_xls->setAttrib('class', 'btn btn-secondary btn-sm');

        $export_doc = new Zend_Form_Element_Submit('exportdoc');
        $export_doc->removeDecorator('Label');
        $export_doc->removeDecorator('HtmlTag');
        $export_doc->removeDecorator('description');
        $export_doc->removeDecorator('errors');
        $export_doc->removeDecorator('DtDdWrapper');
        $export_doc->setAttrib('class', 'btn btn-secondary btn-sm');

        $export_pdf = new Zend_Form_Element_Submit('exportpdf');
        $export_pdf->removeDecorator('Label');
        $export_pdf->removeDecorator('HtmlTag');
        $export_pdf->removeDecorator('description');
        $export_pdf->removeDecorator('errors');
        $export_pdf->removeDecorator('DtDdWrapper');
        $export_pdf->setAttrib('class', 'btn btn-secondary btn-sm');

        $liste_fields [] = $submit;
        $liste_fields [] = $export_xls;
        $liste_fields [] = $export_doc;
        $liste_fields [] = $export_pdf;

        $this->addElements($liste_fields);

    }

}