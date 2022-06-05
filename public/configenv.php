<?php
/* 
 * Configuration de l'environnement applicatif
 */
error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('Europe/Paris');

define('APP_TYP_ENVIR', 'dev');
if (APP_TYP_ENVIR != 'prod') {
    ini_set('display_errors', 1);
}

// stockage du Path standard, peut être pratique dans certains cas
define('APP_PATH_STD', realpath(dirname(dirname(__FILE__))));

// stockage du Path standard, peut être pratique dans certains cas
define('APP_URL_STD', 'http://localhost:8084');

// stockage du Path dédié au Zend Framework
//define('APP_PATH_ZF', realpath(dirname(dirname(dirname(__FILE__)))).
define('APP_PATH_ZF', realpath(dirname(dirname(__FILE__))).
        '/library/zf1-future/library');

// directory setup and class loading
set_include_path('.'
        . PATH_SEPARATOR . APP_PATH_STD . '/library/'
        . PATH_SEPARATOR . APP_PATH_STD . '/library/class/'
        . PATH_SEPARATOR . APP_PATH_STD . '/application/models'
        . PATH_SEPARATOR . APP_PATH_STD . '/application/forms'
        . PATH_SEPARATOR . APP_PATH_STD . '/application/public'
        . PATH_SEPARATOR . APP_PATH_STD . '/application/vendors'
        . PATH_SEPARATOR . APP_PATH_ZF 
        . PATH_SEPARATOR . get_include_path());

//include "Zend/Loader.php";
//Zend_Loader::registerAutoload();
require_once('Zend/Loader/Autoloader.php');
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

// chargement configuration, et sauvegarde de certains paramètres en registres
$config = new Zend_Config_Ini(APP_PATH_STD . '/application/configs/config.ini', APP_TYP_ENVIR);

// ouverture d'un registre pour stockage temporaire de certains paramètres
$registry = Zend_Registry::getInstance();

$registry->set('APP_TITLE', $config->app->title);

// Démarrage de la session globale, bonne pratique référencée ici :
//   http://framework.zend.com/manual/fr/zend.session.advanced_usage.html
Zend_Session::start();

// stockage de certains paramètres en session pour faciliter leur
// récupération à différents niveaux (notamment dans les "view helpers")
$session = new Zend_Session_Namespace('resources');
$session->charset = $config->resources->charset;
$session->language = $config->resources->language;

// Mise en place de la traduction en français
// technique trouvée sur :
//  http://cogo.wordpress.com/2008/04/24/translating-zend_form-error-messages-and-more/
$translate = new Zend_Translate('Array', APP_PATH_STD . '/language/labels_fr.php', 'fr_FR');
// code à utiliser dans le cas de l'ajout d'une seconde traduction
// $translate->addTranslation(APP_PATH_STD . '/language/deutsch.php', 'de_DE');
// Définir fr_FR comme traduction par défaut
$translate->setLocale($config->resources->locale);
Zend_Registry::set('Zend_Translate', $translate);

// locale
$locale = new Zend_Locale($config->resources->locale);
Zend_Registry::set('Zend_Locale', $locale);

// setup database
$db = Zend_Db::factory($config->db);
Zend_Db_Table::setDefaultAdapter($db);

// stockage en registre des paramètres de connexion BD pour pouvoir
// les transmettre aisément à certains scripts (génération d'export XLS,
// de génération de PDF, génération de tableaux de synthèse, etc...)
$registry->set('db', $db);

// définition de l'UTF8 comme encodage interne par défaut pour l'application
mb_internal_encoding($config->resources->charset);
mb_http_output($config->resources->charset);

// passage de MYSQL en UTF8 (cette technique ne fonctionne qu'avec MySQL)
if ($config->db->params->charset != '') {
    $db->query('set names ' . $config->db->params->charset);
}
