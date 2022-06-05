<?php

require_once ('CrudForm.php');

class DossierForm extends CrudForm {

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
        $this->fields_form['societe_id'] = array('label' => 'Société :',
            'modif' => true, 'oblig' => true,
            'model' => 'PrmCodSoc', 'fieldtype' => 'select',
            'cle_secondaire' => true);
        $this->fields_form['dossier_ref'] = array('label' => 'Réf. dossier :',
            'modif' => true, 'oblig' => true);
        $this->fields_form['personne_id'] = array('label' => 'Personne :',
            'modif' => true, 'oblig' => true, 'fieldtype' => 'modallist');
        $this->fields_form['cde_ape'] = array('label' => 'APE :',
            'modif' => true, 'oblig' => true,
            'model' => 'PrmCodApe', 'fieldtype' => 'select',
            'cle_secondaire' => true);
        $this->fields_form['cde_eta_jur'] = array('label' => 'Etat juridique :',
            'modif' => true, 'oblig' => true,
            'model' => 'PrmEtaJur', 'fieldtype' => 'select');
        $this->fields_form['statut'] = array('label' => 'Statut :',
            'modif' => true, 'oblig' => true,
            'model' => 'PrmStaMaj', 'fieldtype' => 'select');
        $this->fields_form['chgt_doss'] = array('label' => 'Date entrée :',
            'modif' => true, 'oblig' => false, 'fieldtype' => 'date',
            'fieldpattern' => 'yy-mm-dd');
        $this->fields_form['cd_etat_cpt'] = array('label' => 'Solde gestion :',
            'modif' => true, 'oblig' => true,
            'model' => 'PrmTypSld', 'fieldtype' => 'select');
        $this->fields_form['encours_mnt'] = array('label' => 'Encours :',
            'modif' => true, 'oblig' => false);
        $this->fields_form['solde_brut'] = array('label' => 'Solde brut :',
            'modif' => true, 'oblig' => false);
        $this->fields_form['top_cloture'] = array('label' => 'Clôture du dossier',
            'modif' => true, 'oblig' => false, 'size' => 1,
            'model' => '*sel_b_o_n', 'fieldtype' => 'select');

        parent::genererForm($values);
        $this->setMethod('post')
                ->setAction('')
                ->setName('DossierForm');
    }

}
