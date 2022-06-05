<?php
require_once 'CrudOutils.php';

class DossiersController extends Zend_Controller_Action {

    public $libelle_entite = "un dossier" ;

    public function preDispatch() {
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->_redirect('login/logout');
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

        /*
         * récupération et transmission du menu de navigation global
         * à la vue sous-jacente
         */
        $registry = Zend_Registry::getInstance();
        $this->view->naviglobal = $registry->get('naviglobal_data');

        /*
         * définition du titre de la vue sous-jacente
         */        
        $this->view->title = $registry::get('APP_TITLE');

        /*
         * récupération du type de formulaire de recherche souhaité
         * 1 = recherche orientée "datastructure"
         * 2 = recherche orientée "données métier"
         */
        $typ_form_rech = $registry::get('typ_form_rech_dossiers');

        /*
         * définition de la session de stockage des filtres utilisateurs
         */
        $crud_filtres = new Zend_Session_Namespace('crud_filtres_dossiers');
        if (!isset($crud_filtres->criteres)) {
            $crud_filtres->criteres = array();
        } else {
            /*
             * en cas de changement de paramétrage, il est préférable de
             * réinitialiser la session "crud_filtres" pour ne pas récupérer
             * des données incompatibles avec le type de formulaire de recherche
             */
            if (isset($crud_filtres->criteres['val']) && $typ_form_rech != 2) {
                $crud_filtres->criteres = array();
            }
            if (isset($crud_filtres->criteres['zon_rech']) && $typ_form_rech != 1) {
                $crud_filtres->criteres = array();
            }
        }

        $top_export_xls  = false;
        $top_export_pdf  = false;
        $top_export_doc  = false;

        /*
         * booléen utilisé pour identifier si une requête GET valide
         * de type "tri" sur colonne, ou une requête POST valide de type
         * demande de pagination, a été émise.
         */
        $valid_getpost_asked = false;

        /*
         * Création et analyse du formulaire de recherche
         */
        $form = new DossierRechForm();
        $form->submit->setLabel('Recherche');
        $form->submit->setAttrib('class', 'btn btn-primary btn-sm');
        $form->exportxls->setLabel('Export XLS');
        $form->exportxls->setAttrib('class', 'btn btn-secondary btn-sm');
        $form->exportpdf->setLabel('Export PDF');
        $form->exportpdf->setAttrib('class', 'btn btn-secondary btn-sm');
        $form->exportdoc->setLabel('Export DOC');
        $form->exportdoc->setAttrib('class', 'btn btn-secondary btn-sm');
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            /*
             * détection d'une demande de pagination
             */
            if (array_key_exists('page', $_POST)) {
                $page_num = Sanitize::blinderGet('page');
                if ($page_num != '') {
                    $valid_getpost_asked = true;
                }
            }

            /*
             * traitement du formulaire de recherche
             */
            $fiche_data = $this->_request->getPost();
            if ($form->isValid($fiche_data)) {
                if ($form->getValue('exportpdf')) {
                    $top_export_pdf = true;
                } else {
                    if ($form->getValue('exportxls')) {
                        $top_export_xls = true;
                    } else {
                        if ($form->getValue('exportdoc')) {
                            $top_export_doc = true;
                        }
                    }
                }

                if ($typ_form_rech == 2) {
                    /*
                     * recherche de type 2 : filtres orientés "métier"
                     */
                    $rech_variable_key = array();
                    $rech_variable_val = array();
                    $zone = 'rech_codsoc';
                    $variable = CrudOutils::trim($form->getValue($zone));
                    if ($variable != '' && $variable != '--') {
                        $rech_variable_key [$zone] = 'societe_id = ?';
                        $rech_variable_val [$zone] = $variable;
                    }
                    $zone = 'rech_contrat';
                    $variable = CrudOutils::trim($form->getValue($zone));
                    if ($variable != '') {
                        $rech_variable_key [$zone] = 'dossier_ref like ?';
                        $rech_variable_val [$zone] = '%' . $variable . '%';
                    }
                    $zone = 'rech_datdeb';
                    $variable = CrudOutils::trim($form->getValue($zone));
                    if ($variable != '') {
                        $rech_variable_key [$zone] = 'chgt_doss >= ?';
                        $rech_variable_val [$zone] = $variable;
                    }
                    $zone = 'rech_stamaj';
                    $variable = CrudOutils::trim($form->getValue($zone));
                    if ($variable != '' && $variable != '--') {
                        $rech_variable_key [$zone] = 'statut = ?';
                        $rech_variable_val [$zone] = $variable;
                    }
                    /*
                     * stockage des filtres en session pour réutilisation
                     * ultérieure
                     */
                    $crud_filtres->criteres = array(
                        'key' => $rech_variable_key,
                        'val' => $rech_variable_val
                    );
                } else {
                    /*
                     * recherche de type 1 : filtres orientés "datastructure"
                     * stockage des filtres en session pour réutilisation
                     * ultérieure
                     */
                    $crud_filtres->criteres = array(
                        'zon_rech' => $form->getValue('zon_rech'),
                        'typ_rech' => $form->getValue('typ_rech'),
                        'val_rech' => $form->getValue('val_rech')
                    );
                }
            } else {
                $form->populate($fiche_data);
            }
        }
        /*
         * traitement des tris sur colonnes (transmis par $_GET)
         */
        if ($this->_request->isGet()) {

            if (array_key_exists('column_name', $_GET)) {
                $column_name = Sanitize::blinderGet('column_name');
                if ($column_name != '') {
                    $valid_getpost_asked = true;
                    /*
                     * si même colonne sélectionnée que précédemment, alors on
                     * inverse le sens du tri ("asc" <-> "desc")
                     */
                    if (isset($crud_filtres->grid_column_name)) {
                        if ($crud_filtres->grid_column_name === $column_name) {
                            if ($crud_filtres->grid_column_order == 'desc') {
                                $crud_filtres->grid_column_order = 'asc';
                            } else {
                                $crud_filtres->grid_column_order = 'desc';
                            }
                        } else {
                            $crud_filtres->grid_column_order = 'asc';
                        }
                    } else {
                        $crud_filtres->grid_column_order = 'asc';
                    }
                    $crud_filtres->grid_column_name = $column_name;
                }
            }
        }
        /*
         * si un tri ou une pagination est demandé après un filtre,
         * alors on conserve le filtre pour que l'utilisateur
         * puisse trier ses données tout en conservant la
         * sélection précédente : on recherche donc l'existence
         * d'un filtre orienté "métier" ou "datastructure" pour
         * forcer les critères de recherche au niveau SQL
         */
        if ($valid_getpost_asked) {
            $fiche_data = array();
            if ($typ_form_rech == 2) {
                if (isset($crud_filtres->criteres['val'])) {
                    foreach ($crud_filtres->criteres['val'] as $key => $val) {
                        // suppresion des éventuels % (insérés automatiquement
                        // par les recherches de type "like")
                        $fiche_data[$key] = str_replace('%', '', $val);
                    }
                }
            } else {
                if (count($crud_filtres->criteres) > 0) {
                    foreach ($crud_filtres->criteres as $key => $val) {
                        // suppresion des éventuels % (insérés automatiquement
                        // par les recherches de type "like")
                        $fiche_data[$key] = str_replace('%', '', $val);
                    }
                }
            }

            /*
             * rechargement des filtres du formulaire avec les
             * données récupérées de la session, pour ne pas les
             * perdre lors d'une nouvelle demande de tri
             */
            if (count($fiche_data) > 0) {
                $form->populate($fiche_data);
            }
        }

        /*
         * si colonne de tri non définie alors on prend la colonne "id"
         * par défaut (en tri ascendant)
         */
        if (!isset($crud_filtres->grid_column_name)) {
            $crud_filtres->grid_column_name = 'id';
            $crud_filtres->grid_column_order = 'asc';
        }

        $entity = new Dossier();
        $select = $entity->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
        /*
         * ajout d'une colonne "fictive" pour le montant total
         */
        $select->columns(array(
            '(encours_mnt + solde_brut) as mnt_total',
            '(select b.libelle from prm_cod_soc b where b.id = societe_id) as lib_societe',
            '(select c.libelle from prm_personne c where c.id = personne_id) as lib_personne'));
        /*
         * on fait le premier "where" à part de la requête principale, comme ça
         * les "where" suivants, s'il y en a (vu qu'ils sont optionnels),
         * seront ajoutés avec des "and"
         * en plus le test ci-dessous permet de ne pas afficher les lignes
         * ayant fait l'objet d'une suppression logique
         */
        $clause_where = "statut <> 'D' ";
        $select->where($clause_where);

        /*
         * traitement des critères de filtrage facultatifs
         */
        if ($typ_form_rech != 2) {
            /*
             * recherche orientée "datastructure"
             */
            if (isset($crud_filtres->criteres['val_rech'])) {
                $zon_rech = $crud_filtres->criteres['zon_rech'];
                $typ_rech = $crud_filtres->criteres['typ_rech'];
                $val_rech = trim($crud_filtres->criteres['val_rech']);
                if ($val_rech != '') {
                    /*
                    * recherche orientée "datastructure"
                    */
                    /*
                    * récupération du type de recherche au format SQL, et
                    * adaptation de like et begin au format SQL
                    */
                    $typ_rech = CrudOutils::SelectorSQL($typ_rech);
                    switch ($typ_rech) {
                        case 'like': {
                                // recherche de type "contient"
                                $val_rech = '%' . CrudOutils::trim($val_rech) . '%';
                                break;
                            }
                        case 'begin': {
                                // recherche de type "commence_par"
                                $typ_rech = 'like';
                                $val_rech = CrudOutils::trim($val_rech) . '%';
                                break;
                            }
                    }
                    $select->where("{$zon_rech} {$typ_rech} ?", $val_rech);
                }
            }
        } else {
            /*
             * recherche orientée "données métier"
             */
            if (isset($crud_filtres->criteres['key'])) {
                foreach ($crud_filtres->criteres['key'] as $key => $value) {
                    $select->where($value, $crud_filtres->criteres['val'][$key]);
                }
            }
        }

        /*
         * détermination de la colonne de tri et de son sens
         */
        if (isset($crud_filtres->grid_column_name)) {
            $order_name = $crud_filtres->grid_column_name;
            $order_sens = $crud_filtres->grid_column_order;
            $select->order("{$order_name} {$order_sens}");
        }

        /*
         * détection d'une éventuelle demande d'export (XLS ou PDF)
         * ou déclenchement du système de pagination standard
         */
        if ($top_export_xls || $top_export_pdf || $top_export_doc) {
            /*
             * récupération de la requête SQL pour la passer à l'une des
             * actions d'export
             */
            $sql = $select->__toString();
            if ($top_export_xls) {
                $this->exportAction('xls', $sql);
            } else {
                if ($top_export_pdf) {
                    $this->exportPDFAction($sql);
                } else {
                    $this->exportAction('doc', $sql);
                }
            }
        } else {
            $maxbypage = Zend_Registry::get('maxbypage');
            $select->limit(1, $maxbypage);
            $paginator = Zend_Paginator::factory($select);
            $paginator->setCurrentPageNumber($this->_getParam('page', 1));
            $paginator->setItemCountPerPage($this->_getParam('par', $maxbypage));
            $this->view->paginator = $paginator;
        }
    }

    /**
     * action d'ajout d'une entité
     */
    public function addAction() {
        $fc = Zend_Controller_Front::getInstance();

        /*
         * récupération et transmission du menu de navigation global
         * à la vue sous-jacente
         */
        $registry = Zend_Registry::getInstance();
        $this->view->naviglobal = $registry->get('naviglobal_data');

        $this->view->title = 'Créer un '. $this->libelle_entite;

        $entity = new Dossier();
        $metadata = $entity->getMetadataCache();
        $options = array(
            'action' => 'add',
            'metadata' => $metadata
        );
        $form = new DossierForm($options);

        $fiche_data = array();
        if ($this->_request->isPost()) {
            $fiche_data = $this->_request->getPost();
            /*
             * formulaire valide ou pas, si l'utilisateur clique sur "return"
             * on sort sans effectuer de mise à jour
             */
            if (array_key_exists('return', $fiche_data)) {
                $this->_redirect('/' . $this->_request->controller);
            }
            $form->genererForm($fiche_data);
            if ($form->isValid($fiche_data)) {
                if ($form->getValue('submit')) {
                    $row = $entity->createRow();
                    $this->rangerDatas($form, $row, $metadata);
                    $row->save();
                }
                $this->_redirect('/' . $this->_request->controller);
            } else {
                $form->genererForm($fiche_data);
            }
        } else {
            $form->genererForm($fiche_data);
        }
        // $form->genererForm($fiche_data);
        $form->submit->setLabel('Ajouter');
        $btnAnnuler = $form->return->setLabel('Annuler');
        $btnAnnuler->setAttrib('class', 'btn btn-secondary');
        $this->view->form = $form;
    }

    /**
     * action d'édition (modification) d'une entité
     */
    function editAction() {
        $fc = Zend_Controller_Front::getInstance();

        /*
         * récupération et transmission du menu de navigation global
         * à la vue sous-jacente
         */
        $registry = Zend_Registry::getInstance();
        $this->view->naviglobal = $registry->get('naviglobal_data');

        $this->view->title = 'Modifier ' . $this->libelle_entite;

        $entity = new Dossier();
        $metadata = $entity->getMetadataCache();
        $options = array(
            'action' => 'edit',
            'metadata' => $metadata
        );

        $form = new DossierForm($options);
        $fiche_data = array();
        if ($this->_request->isPost()) {
            $fiche_data = $this->_request->getPost();
            /*
             * formulaire valide ou pas, si l'utilisateur clique sur "return"
             * on sort sans effectuer de mise à jour
             */
            if (array_key_exists('return', $fiche_data)) {
                $this->_redirect('/' . $this->_request->controller);
            }
            $form->genererForm($fiche_data);
            if ($form->isValid($fiche_data)) {
                if ($form->getValue('submit')) {
                    $id = (int) $form->getValue('id');
                    if ($id > 0) {
                        $row = $entity->fetchRow('id = ' . $id);
                        $this->rangerDatas($form, $row, $metadata);
                        $row->save();
                    }
                }

                $this->_redirect('/' . $this->_request->controller);
            }
        } else {
            $id = (int) $this->_request->getParam('id', 0);
            if ($id <= 0) {
                $this->_redirect('/' . $this->_request->controller);
            }
            $fiche_data = $this->placerDatas($entity, $id, $options['action']);
            $form->genererForm($fiche_data);
        }

        $personnes = new PrmPersonne();
        $perselect = $personnes->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
		$perselect->order("libelle");

        $btnValider = $form->submit->setLabel('Valider');
        $btnValider->setAttrib('class', 'btn btn-primary btn-sm');
        $btnAnnuler = $form->return->setLabel('Annuler');
        $btnAnnuler->setAttrib('class', 'btn btn-secondary btn-sm');
        $this->view->form = $form;
        $this->view->persql = $perselect->__toString();

        unset($entity);
    }

    /**
     * action de suppression d'une entité
     */
    function deleteAction() {
        $fc = Zend_Controller_Front::getInstance();

        /*
         * récupération et transmission du menu de navigation global
         * à la vue sous-jacente
         */
        $registry = Zend_Registry::getInstance();
        $this->view->naviglobal = $registry->get('naviglobal_data');

        $this->view->title = 'Supprimer ' . $this->libelle_entite;

        $entity = new Dossier();
        $metadata = $entity->getMetadataCache();
        $options = array(
            'action' => 'delete',
            'metadata' => $metadata
        );
        $form = new DossierForm($options);
        $fiche_data = array();
        if ($this->_request->isPost()) {
            $fiche_data = $this->_request->getPost();
            /*
             * formulaire valide ou pas, si l'utilisateur clique sur "return"
             * on sort sans effectuer de mise à jour
             */
            if (array_key_exists('return', $fiche_data)) {
                $this->_redirect('/' . $this->_request->controller);
            }
            $form->genererForm($fiche_data);
            if ($form->isValid($fiche_data)) {
                if ($form->getValue('submit')) {
                    $id = (int) $form->getValue('id');
                    if ($id > 0) {
                        /*
                         * suppession physique remplacée par une suppression logique
                         */
                        //   $entity->delete('id = '. $id);
                        $row = $entity->fetchRow('id=' . $id);
                        $row->statut = 'D';
                        $row->save();
                        unset($entity);
                    }
                }
                $this->_redirect('/' . $this->_request->controller);
            }
        } else {
            $id = (int) $this->_request->getParam('id', 0);
            if ($id <= 0) {
                $this->_redirect('/' . $this->_request->controller);
            }
            $fiche_data = $this->placerDatas($entity, $id, $options['action']);
            $form->genererForm($fiche_data);
        }

        $btnValider = $form->submit->setLabel('Valider');
        $btnValider->setAttrib('class', 'btn btn-primary btn-sm');
        $btnAnnuler = $form->return->setLabel('Annuler');
        $btnAnnuler->setAttrib('class', 'btn btn-secondary btn-sm');

        $this->view->form = $form;

        unset($entity);
    }

    /**
     * action d'affichage d'une entité
     */
    function displayAction() {
        $fc = Zend_Controller_Front::getInstance();

        /*
         * récupération et transmission du menu de navigation global
         * à la vue sous-jacente
         */
        $registry = Zend_Registry::getInstance();
        $this->view->naviglobal = $registry->get('naviglobal_data');

        $this->view->title = 'Afficher ' . $this->libelle_entite;

        $entity = new Dossier();
        $metadata = $entity->getMetadataCache();
        $options = array(
            'action' => 'display',
            'metadata' => $metadata
        );
        $form = new DossierForm($options);
        $fiche_data = array();
        if ($this->_request->isPost()) {
            $this->_redirect('/' . $this->_request->controller);
        } else {
            $id = (int) $this->_request->getParam('id', 0);
            if ($id <= 0) {
                $this->_redirect('/' . $this->_request->controller);
            }
            $fiche_data = $this->placerDatas($entity, $id, $options['action']);
        }
        $form->genererForm($fiche_data);
        $btnRetour = $form->return->setLabel('Retour');
        $btnRetour->setAttrib('class', 'btn btn-secondary btn-sm');
        $this->view->form = $form;
        unset($entity);
    }

    /**
     * Alimentation des colonnes de la table sous-jacente avec les champs
     * du formulaire
     * Attention : la colonne "identifiant" est exclue de l'opération car elle
     *   ne peut pas être prise en compte par l'INSERT, pas plus que par l'UPDATE
     *  (dans le cas de l'UPDATE, la colonne "identifiant" est de tout façon
     *  définie dans la clause WHERE de la requête générée par Zend_Db)
     * @param <type> $form
     * @param <type> $row
     * @param <type> $metadata
     */
    private function rangerDatas($form, &$row, $metadata) {

        $rows = $form->getValues();
        foreach ($rows as $key => $value) {
            /*
             * si certains champs du formulaire sont "disabled" alors le
             * formulaire les renvoie avec une valeur NULL, il faut donc les
             * ignorer pour ne pas écraser de données dans la table SQL
             */
            if (!is_null($value)) {
                   Zend_Debug::dump($metadata[$key]) ;
                if (array_key_exists($key, $metadata)
                        && $metadata[$key]['PRIMARY'] !== true) {
                    $row->$key = $value;
                }
            }
        }
    }

    /**
     * Alimentation des champs du formulaire avec les colonnes de la table
     * SQL sous-jacente
     * @param <type> $entity
     * @param <type> $id_entity
     * @return <type> $fiche_data
     */
    private function placerDatas(&$entity, &$id_entity, $type_action) {

        $fiche = $entity->fetchRow('id=' . $id_entity);
        $fiche_data = $fiche->toArray();

        /*
         * calcul d'un montant qui n'appartient pas à la table
         * SQL sous-jacente
         */
        $fiche_data['prov_totale'] = floatval(
                        $fiche_data['encours_mnt'] +
                        $fiche_data['solde_brut']
                );
        $fiche_data['prov_totale'] = number_format($fiche_data['prov_totale'], 2, '.', '');

        return $fiche_data;
    }

    /**
     * action d'export aux formats XLS ou DOC
     */
    public function exportAction($type_export, $sql) {
        /*
         * on courc-circuite les helpers et layouts standards
         */
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        /*
         * on force les headers avec les paramètres adéquats
         */
        $headers_params = ExportOffice::params($type_export);
        foreach ($headers_params as $key => $value) {
            $this->_response->setheader($key, $value);
        }

        /*
         * on passe la requête SQL au script de rendu
         */
        $this->view->sql = $sql;
        $this->render('export');
    }

    /**
     * action d'export au format PDF
     */
    public function exportPDFAction($sql) {
        /*
         * on courc-circuite les helpers et layouts standards
         */
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        /*
         * on force les headers avec les paramètres adéquats
         */
        $headers_params = ExportOffice::params('pdf');
        foreach ($headers_params as $key => $value) {
            $this->_response->setheader($key, $value);
        }

        /*
         * on passe la requête SQL au script de rendu
         */
        $this->view->sql = $sql;
        $this->render('exportpdf');
    }

}
