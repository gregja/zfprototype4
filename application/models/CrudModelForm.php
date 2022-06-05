<?php

class CrudModelForm {

    public $datas_extract = array();
    public $predefinis = array(
        '*sel_o_n' => array('O' => 'Oui', 'N' => 'Non'),
        '*sel_b_o_n' => array(' ' =>'Non défini', 'O' => 'Oui', 'N' => 'Non'),
        '*sel_countries'=> array(' ' =>'-- please choose --', 'fr' => 'France', 'de' => 'Allemagne', 'ie' => 'Irlande', 'uk' => 'Grande Bretagne')
    );

    public function __construct($modele, $options='') {

        if (array_key_exists($modele, $this->predefinis)) {
            /*
             * insertion d'un critère optionnel, si transmis en paramètre
             */
            if (is_array($options) && array_key_exists('*all', $options)) {
                $code = $options['*all'];
                $this->datas_extract[$code] = $code;
                $this->datas_extract = array_merge($this->datas_extract,
                                $this->predefinis[$modele]);
            } else {
                $this->datas_extract = $this->predefinis[$modele];
            }
        } else {
            /*
             * insertion d'un critère optionnel, si transmis en paramètre
             */
            if (is_array($options) && array_key_exists('*all', $options)) {
                $code = $options['*all'];
                $this->datas_extract[$code] = $code;
            }

            $instance = new $modele();
            if (is_array($options) && array_key_exists('key', $options)) {
                $dataobj = $instance->fetchRow('code = \'' . trim($options['key']).'\'');
                if ($dataobj) {
                    $datas = $dataobj->toArray() ;
                    $this->datas_extract[$datas['code']] = $datas['libelle'];
                } else {
                    $this->datas_extract['code'] = '';
                }
            } else {
                $datas = $instance->fetchAll()->toArray();
                foreach ($datas as $num => $values) {
                    /*
                     * si pas de libelle défini, alors on affiche le code à la place
                     */
                    if ($values['libelle'] != '') {
                        $this->datas_extract[$values['code']] = $values['libelle'];
                    } else {
                        $this->datas_extract[$values['code']] = $values['code'];
                    }
                }
            }
        }
    }

    public function retrieve() {
        return $this->datas_extract;
    }

}
