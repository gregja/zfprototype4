<?php
require_once dirname(__FILE__).'/configenv.php' ;

require_once 'Sanitize.php';
require_once 'ExportOffice.php';
require_once 'CrudDates.php';

// activation des fonctions de suppression, de création et de modification
// (système d'autorisation très "basique" pour l'instant)
$registry->set('auth_delete', true);
$registry->set('auth_create', true);
$registry->set('auth_update', true);

// type de formulaire de recherche
// 1 = recherche avec un champ "variable" et plusieurs types de critères
//     (peut convenir pour des recherches simples sur des tables de paramétrage)
// 2 = recherche avec plusieurs champs "fixes" et des recherche de type "like"
//     (plus adapté à des recherches sur des données orientées "métier")
$registry->set('typ_form_rech_personnes', 1);
$registry->set('typ_form_rech_dossiers', 2);

//  nombre de lignes maximum autorisées pour les exports de données
$registry->set('export_xls_nbl_max', 60000);
$registry->set('export_doc_nbl_max', 5000);
$registry->set('export_pdf_nbl_max', 60000);

// paginator
// styles possibles : All, Elastic, Jumping, Sliding (valeur par défaut)
Zend_Paginator::setDefaultScrollingStyle('Elastic');
Zend_View_Helper_PaginationControl::setDefaultViewPartial('partials/pagination.phtml');
$registry->set('maxbypage', 29);

// intégration de JQuery
$view = new Zend_View();
$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
$viewRenderer->setView($view);
Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

Zend_Layout::startMvc(array('layoutPath' => '../application/layouts'));

// menu de navigation global
require_once (dirname(dirname(__FILE__)).'/application/configs/menus_fr.php') ;
$registry->set('naviglobal_data', $naviglobal_data);

// $auth est une référence vers Zend_Auth (getInstance())
// $front->registerPlugin(new My_Controller_Plugin_Auth(new My_Acl_Ini('../configs/acl.ini')));
// setup controller
 try {
    $frontController = Zend_Controller_Front::getInstance();

	$router = new Zend_Controller_Router_Rewrite();
    
    //le contrôleur frontal renvoie les exceptions qu'il a rencontrées
    //à l'objet de réponse, nous offrant une possibilité élégante de les gérer.
    $frontController->throwExceptions(true);

    //setControllerDirectory() est utilisé pour
    //chercher les fichiers de
    //classes de contrôleurs d'action.
    $controller_paths = array('default' => APP_PATH_STD.'/application/controllers');
	$frontController->setControllerDirectory($controller_paths) ;
   
    $frontController->setRouter($router);

    $base_url = substr($_SERVER['PHP_SELF'], 0, -9);
    
    $frontController->setBaseUrl($base_url); // set the base url!

    //Dispatch lance notre application, fait le gros travail du contrôleur frontal.
    //Il peut facultativement prendre un objet de requête et/ou un objet de réponse,
    //permettant ainsi au développeur de fournir des objets personnalisés.
    $response = $frontController->dispatch();
 	
} catch (Zend_Controller_Exception $e) {
    //Traite les exceptions du contrôleur (généralement 404)
    include 'errors/404.phtml';
} catch (Exception $e) {
    //Traite les autres exceptions du contrôleur
     echo $e ;
   // include 'errors/500.phtml';
}

