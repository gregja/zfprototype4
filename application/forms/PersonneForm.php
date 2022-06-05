<?php

require_once ('CrudForm.php');

class PersonneForm extends CrudForm {

    /**
     * La méthode genererForm() se substitue aux méthodes init() et
     * populate().
     * La méthode "fille" sert à définir la liste des champs et leurs
     * caractéristiques qui sont :
     * - le nom du champ,
     * - le type de champ (champ texte, liste select...),
     * - le label associé au champ,
     * - le champ est-il modifiable ? si oui est-il obligatoire ?
     * La méthode "parente" génère les champs du formulaire en tenant compte
     * des caractéristiques des champs définis dans la classe fille.
     * La méthode "parente" reçoit également les valeurs provenant de la
     * base de données (via le paramètre $values) et s'en sert pour alimenter le
     * champ "value" de chaque champ du formulaire. Elle se substitue en cela
     * à la méthode populate().
     *
     * @param <type> $values : tableau contenant les valeurs provenant
     *                         de la base de données pour chaque champ
     *                         du formulaire (transmis tel quel à la
     *                         méthode "parente")
     */
    public function genererForm($values) {
        /*
         * liste des champs du formulaire
         */
        $this->fields_form = array();
        $this->fields_form['code'] = array('label' => 'Code :',
            'modif' => true, 'oblig' => true);
        $this->fields_form['libelle'] = array('label' => 'Nom et prénom :',
            'modif' => true, 'oblig' => false);

        parent::genererForm($values);
        $this->setMethod('post')
                ->setAction('')
                ->setName('PersonneForm');
    }

}
