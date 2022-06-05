<?php

require_once('CrudModelForm.php');

/**
 * Description of class CrudForm
 *
 * @author Grégory Jarrige
 *
 * Attention : cette version de la classe CrudForm ne génère que des champs
 * "input" de type "texte" et "select". Les autres types de champs devront
 * être intégrés ultérieurement.
 * Cette classe sait également générer des champs "date" avec le code
 * Javascript "datepicker.js". Il s'agit en fait d'un champ de type "texte"
 * qui déclenche l'affichage d'un calendrier quand on clique dessus.
 */
abstract class CrudForm extends Zend_Form {
    /*
     * type d'action défini par le script appelant (affichage par défaut)
     */

    protected $action = 'display';
    /*
     * métadonnées de la table SQL sous-jacente (reçue en paramètre)
     */
    protected $metadata = array();
    /*
     * liste des champs du formulaire (à définir dans la classe fille)
     */
    protected $fields_form = array();
    /*
     * liste des champs du formulaire (à définir dans la classe fille)
     */
    protected $all_fields_disabled = array();
    /*
     * URL de base pour le chargement des images du site (utilisé
     * notamment pour les champs de type "datepicker"
     */
    protected $baseUrl = '';

    public function __construct($options = null) {

        $fc = Zend_Controller_Front::getInstance();
        $this->baseUrl = $fc->getBaseUrl();

        $this->metadata = array();
        /*
         * par défaut, les champs du formulaire sont tous non modifiables
         */
        $this->all_fields_disabled = true;

        /*
         * si certaines options sont transmises, alors interception et traitement
         * au niveau de la classe fille, ne sont transmises au constructeur que
         * les options non créées spécifiquement par le développeur
         */
        if (is_array($options)) {
            if (array_key_exists('action', $options) && !is_null($options['action'])) {
                $this->action = strtolower($options['action']);

                /*
                 * déverrouillage des champs du formulaire en édition et création
                 * seulement
                 */
                if ($this->action == 'edit' || $this->action == 'add') {
                    $this->all_fields_disabled = false;
                }
                /*
                 * suppression du poste de tableau pour ne pas perturber le constructeur
                 */
                unset($options['action']);
            }

            if (array_key_exists('metadata', $options) && !is_null($options['metadata'])) {
                /*
                 * récupération des métadonnées de la table sous-jacente
                 */
                $this->metadata = $options['metadata'];
                /*
                 * suppression du poste de tableau pour ne pas perturber le constructeur
                 */
                unset($options['metadata']);
            }
        }

        parent::__construct($options);
        $this->setName('Dossier');
        $this->removeDecorator('label');
        $this->removeDecorator('htmlTag');
        $this->removeDecorator('description');
        $this->removeDecorator('errors');
        $this->setDisplayGroupDecorators(array('FormElements', 'Fieldset'));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table')),
            'Form'
        ));
        $this->removeDecorator('DtDdWrapper');
    }

    protected function genererForm($values) {
        /*
         * liste des champs du formulaire formatés par Zend_Form
         */
        $array_fields = array();

        /*
         * formatage des champs du formulaire
         */
        $liste_fields = array();
        foreach ($this->fields_form as $field_code => $field_param) {
            $liste_fields [] = $field_code;

            if (array_key_exists($field_code, $this->metadata)) {
                $field_typedb = $this->metadata[$field_code]['DATA_TYPE'];
                $field_sizealp = $this->metadata[$field_code]['LENGTH'];
                $field_sizenum = $this->metadata[$field_code]['PRECISION'];
                $field_nbdec = $this->metadata[$field_code]['SCALE'];
                $field_default = $this->metadata[$field_code]['DEFAULT'];
                if (intval($this->metadata[$field_code]['NULLABLE']) == 1) {
                    $field_nullable = true;
                } else {
                    $field_nullable = false;
                }
            } else {
                $field_typedb = null;
                $field_sizealp = null;
                $field_sizenum = null;
                $field_nbdec = null;
                $field_default = null;
                $field_nullable = null;
            }

            /*
             * certains champs peuvent être non modifiables, ou tous les champs
             * peutvent être non modifiables
             */
            if ($this->all_fields_disabled === false) {
                if ($field_param['modif'] === true) {
                    $field_disabled = false;
                } else {
                    $field_disabled = true;
                }
            } else {
                $field_disabled = true;
            }

            /*
             * attributs HTML du champ de formulaire considéré
             */
            $attrib = array();

            /*
             * chargement de la valeur
             */
            if (array_key_exists($field_code, $values)) {
                $attrib['value'] = $values[$field_code];
            } else {
                $values[$field_code] = '';
                $attrib['value'] = '';
            }

            if (array_key_exists('fieldtype', $field_param)) {
                $fieldtype = $field_param['fieldtype'];
            } else {
                $fieldtype = 'text';
            }

            switch ($fieldtype) {
                case 'select' : {
                        $array_fields[$field_code] = new Zend_Form_Element_Select($field_code);
                        $array_fields[$field_code]->setAttrib('class', 'custom-select custom-select-sm');
                        $datas_sql = array();
                        if ($field_disabled && array_key_exists('cle_secondaire', $field_param)) {
                            /*
                             * si champ désactivé et clé secondaire définie, alors on
                             * ne charge qu'une seule ligne SQL pour récupérer le
                             * libellé associé au code défini (si renseigné)
                             */
                            if ($values[$field_code] != '' && !is_null($values[$field_code])) {
                                $model_class = new CrudModelForm($field_param['model'],
                                                array('key' => $values[$field_code]));
                                $datas_sql = $model_class->retrieve();
                            }
                        } else {
                            $model_class = new CrudModelForm($field_param['model']);
                            $datas_sql = $model_class->retrieve();
                        }

                        if (is_array($datas_sql) && count($datas_sql) > 0) {
                            $array_fields[$field_code]->setMultiOptions($datas_sql);
                        }
                        if (array_key_exists('size', $field_param)) {
                            $attrib['size'] = $field_param['size'];
                        } else {
                            $attrib['size'] = 1;
                        }

                        break;
                    }
                case 'date' : {
                        if ($field_disabled) {
                            $array_fields[$field_code] = new Zend_Form_Element_Text($field_code);
                            $array_fields[$field_code]->setAttrib('class', 'form-control');
                            if (array_key_exists('size', $field_param)) {
                                $attrib['size'] = $field_param['size'];
                            }
                            break;
                        } else {
                            $array_fields[$field_code] = new Zend_Form_Element_Text($field_code);
                            $array_fields[$field_code]->setAttrib('class', 'form-control datepicker');
                            if (array_key_exists('size', $field_param)) {
                                $attrib['size'] = $field_param['size'];
                            }
                            break;
                        }
                    }
                case 'textarea' : {
                        $array_fields[$field_code] = new Zend_Form_Element_Textarea($field_code);
                        $array_fields[$field_code]->setAttrib('class', 'form-control');
                        if (array_key_exists('attribs', $field_param)) {
                            $array_fields[$field_code]->setAttribs($field_param['attribs']);
                        }
                        break;
                    }
                case 'modallist' : {
                        $array_fields[$field_code] = new Zend_Form_Element_Text($field_code);
                        $array_fields[$field_code]->setAttrib('class', 'form-control col-sm-2');
                        $array_fields[$field_code]->setAttrib('data-list', 'personnes-list');
                        $array_fields[$field_code]->setAttrib('data-type', 'datalist');
                        break;
                    }
                default : {
                        $array_fields[$field_code] = new Zend_Form_Element_Text($field_code, '', );
                        $array_fields[$field_code]->setAttrib('class', 'form-control');
                        if (array_key_exists('size', $field_param)) {
                            $attrib['size'] = $field_param['size'];
                        }
                        break;
                    }
            }

            if ($field_disabled) {
                $array_fields[$field_code]->disable = $field_disabled;
            }

            $array_fields[$field_code]->setLabel($field_param['label']);

            /*
             * définition des attributs complémentaires appliqués au champ
             */
            if (!array_key_exists('size', $attrib)) {
                if ($field_sizealp != '' && $field_sizealp != null) {
                    $field_size_int = intval($field_sizealp);
                    if ($field_size_int > 0) {
                        if ($field_size_int > 80) {
                            $attrib['size'] = 80;
                        } else {
                            $attrib['size'] = $field_size_int;
                        }
                    }
                } else {
                    if ($field_sizenum != '' && $field_sizenum != null) {
                        $field_size_int = intval($field_sizenum);
                        if ($field_size_int > 0) {
                            if ($field_size_int > 80) {
                                $attrib['size'] = 80;
                            } else {
                                $attrib['size'] = $field_size_int + 2;
                            }
                        }
                    }
                }
            }

            if (!$field_disabled) {
                /*
                 * filtres et contrôles standards
                 */
                $array_fields[$field_code]
                        ->addFilter('StripTags')
                        ->addFilter('StringTrim');
                if ($field_sizealp !== null && $field_sizealp !== '') {
                    $array_fields[$field_code]->addValidator('StringLength',
                            false, array(0, intval($field_sizealp)));
                }
                /*
                 * Saisie obligatoire si champ non "nullable" et sans
                 * valeur par défaut définie au niveau de SQL
                 */
                if ($field_param['oblig']) {
                    $array_fields[$field_code]->setRequired(true)
                            ->addValidator('NotEmpty');
                }
            }
            if (count($attrib) > 0) {
                $array_fields[$field_code]->setOptions($attrib);
            }
        }

        $prov_id = new Zend_Form_Element_Hidden('id');
        if (array_key_exists('id', $values)) {
            $id_option = array('value' => $values['id']);
            $prov_id->setOptions($id_option);
        }

        $return = new Zend_Form_Element_Submit('return');
        $return->removeDecorator('DtDdWrapper');
        $liste_fields [] = 'return';

        if ($this->all_fields_disabled === false || $this->action == 'delete') {
            $liste_fields [] = 'submit';

            $submit = new Zend_Form_Element_Submit('submit');
            $submit->removeDecorator('DtDdWrapper');
            $submit->setAttrib('id', 'submitbutton');
            $submit->setAttrib('class', 'btn btn-primary btn-sm');
            $this->addElements(array_merge($array_fields, array($prov_id, $return, $submit)));
        } else {
            $this->addElements(array_merge($array_fields, array($prov_id, $return)));
        }
    }

}

