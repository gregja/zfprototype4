# ZFPrototype4

## Objectif du projet 

Ce projet est un prototype d'application de type CRUD (acronyme de "Create, Read, Update, Delete") destiné à servir de modèle, de base de travail, pour le développement d'applications "métiers". J'avais développé ce projet, il y a quelques années, en pensant particulièrement aux développeurs IBM i, car ZF1 est à mon avis l'un des frameworks PHP les mieux adaptés pour le développement de projets destinés à la plateforme IBM i.

Pour la petite histoire, j'avais laissé ce projet de côté, après l'annonce de l'abandon de ZF 1 par son éditeur (Zend), au profit du projet ZF 2, très différent donc incompatible avec la version 1 (une version 2 qui à mon sens n'a jamais tenu ses promesses).

J'ai réactivé ZFP4 après avoir découvert que le projet ZF 1 était remis sur les rails par une communauté active et motivée, sous le nom de "ZF1-Future".

Ce projet est donc construit sur une base de ZF 1, ou plus exactement sur la version 1.21 de ZF1-Future.
ZF1-Future ayant été révisé pour PHP8, j'ai vérifié l'ensemble du code de ZFP4 pour m'assurer qu'il était bien compatible avec PHP8 et ZF1-Future. J'en ai profité pour éliminer quelques fonctionnalités obsolètes, et pour moderniser le design en m'appuyant sur le framework CSS Booststrap 4.

Cette révision de ZFP4 m'a amené à appliquer quelques patchs sur ZF1-Future, patchs que j'ai listés dans le README du projet.

Pourquoi ce projet porte-t-il le numéro "4" ? Parce que ce projet a connu plusieurs évolutions. Cette version 4 est de loin la plus aboutie, et la première que je publie en open-source.

Prenez ZFP4 pour ce qu'il est. Ce n'est pas un projet parfait, prêt à l'emploi, c'est un exemple conçu pour vous familiariser avec ZF1, que vous pouvez adapter à votre convenance, et à vos propres spécificités "métier". 

## Installation

L'application a été testée sur le stack PHP XAMPP (Apache Friends) avec PHP 8.0.12 et PHP 8.1.5.

L'extension PHP "gc" doit être activée pour permettre aux fonctions d''export PDF de fonctionner.

L'extension "pdo_mysql" doit être activée également.

La base de données MySQL de test "zfdbproto" doit être créée et chargée dans votre serveur MySQL.
Le code SQL d'installation se trouve dans le répertoire "source/sql" du projet.

Attention : sur certaines versions de Zend Server, le composant Zend_PDF ne fonctionne pas correctement si la directive suivante n'est pas définie dans le fichier .ini du composant Zend_optimizer :
```
zend_optimizerplus.dups_fix=1 
```
A noter que cette directive n'est pas accessible via la console du ZendServer, il faut donc l'ajouter manuellement.

## Démarrage

Au démarrage de l'application, vous devrez vous identifier avec l'un des deux profils suivants :

- utilisateur "demo", mot de passe "demo"
- utilisateur "admin", mot de passe "admin"

Ces deux profils donnent les mêmes droits, c'est à dire la possibilité de gérer des dossiers et des personnes. 

## Contenu

Le framework ZF1-Future est embarqué dans le répertoire "library", il n'est donc pas nécessaire de l'installer. En revanche, je l'ai patché pour qu'il fonctionne sans problème avec ZFPrototype4. La liste des patchs est détaillée ci-après.

Repo officiel de ZF1-Future :
https://github.com/Shardj/zf1-future

Le composant ZendX est un composant complémentaire, embarqué lui aussi dans le répertoire "library". Il est destiné à faciliter l'intégration de composants de type jQuery notamment. ZendX est chargé à partir du repo ci-dessous (il n'a pas été patché) :
https://github.com/pedro151/ZendX

## Frontend et UI

On trouvera dans le formulaire d'édition d'un dossier, un exemple d'implémentation d'une fonction de recherche s'appuyant sur une fenêtre modale (de type Bootstrap). Cette fenêtre modale permet de sélectionner la personne à rattacher au contrat. Cette partie est développée en VanillaJS, à titre d'exemple. Elle pourra servir d'exemple tout en étant réimplémentée avec le framework JS de votre choix (VueJS, Angular, React ou autre...).

Pour le chargement de certaines fonctionnalités en JS, côté navigateur, je me suis servi d'une variable de type tableau, déclarée au début du "layout" principal, dans laquelle je charge les appels des fonctions que je souhaite exécuter lorsque le DOM est en place. J'effectue cette opération via l'événement DOMCONTENTLOADED :

```JS
    window.addEventListener("DOMContentLoaded", (event) => {
        console.log("DOM entièrement chargé et analysé");
        jQuery('.datepicker').datepicker({
            dateFormat: "yy-mm-dd"
        });

        if (js_hooks.length > 0) {
            for (let i=0, imax=js_hooks.length; i<imax; i++) {
                console.log(js_hooks[i]);
                setTimeout(js_hooks[i], 1);
            }
        }
    });
```

Cette manière de faire est un peu différente de ce qui se pratiquait dans les applications ZF développées il y a une dizaine d'années, mais elle a le mérite d'être simple, efficace, et conforme au standard du HTML5.

Le framework JQueryUI est utilisé pour son composant de saisie de date exclusivement. Il n'est pas embarqué dans le projet mais est chargé dynamiquement à partir de son CDN. Etant peu utilisé dans l'application, il pourra être facilement remplacé si besoin.


## Patchs

Pour que cette application fonctionne correctement, il est nécessaire de modifier légèrement le Zend Framework :

- dans Zend/Db/Table/Abstract.php, remplacer la méthode "getMetadataCache" par la méthode ci-dessous :
```PHP
    /**
     * Gets the metadata cache for information returned by Zend_Db_Adapter_Abstract::describeTable().
     *
     * @return Zend_Cache_Core or null
     */
    public function getMetadataCache() {
        /*
         * patch GJARRIGE - Begin
        */
        // return $this->_metadataCache;
        if ($this->_metadataCache === null) {
            return $this->_db->describeTable($this->_name, $this->_schema);
        } else {
            return $this->_metadataCache;
        }
        /*
         * patch GJARRIGE - End
        */
    }
```

- dans Zend/View/Abstract.php, modifier légèrement la méthode "escape" de la façon suivante :
```PHP
    public function escape($var)
    {
        if (in_array($this->_escape, array('htmlspecialchars', 'htmlentities'))) {
            return call_user_func($this->_escape, $var, ENT_COMPAT, $this->_encoding);
        }

        if (1 == func_num_args()) {
            /*
             * patch GJARRIGE - Begin
             * Modification nécessaire pour corriger des problèmes
             * d'affichage des messages d'erreur au niveau du formulaire de login
            */
            if (is_array($var)) {
                $var_tmp = array_shift($var);
                return call_user_func($this->_escape, $var_tmp);
            } else {
                return call_user_func($this->_escape, $var);
            }
            /*
             * patch GJARRIGE - End
            */
        }
        $args = func_get_args();
        return call_user_func_array($this->_escape, $args);
    }
```

A noter : l'absence de cette correction déclenchait l'apparition du message d'erreur suivant :
```
   Warning: utf8_encode() expects parameter 1 to be string,
   array given in C:\xampp\htdocs\library\ZendFramework\ZF-1.11.4\library\Zend\View\Abstract.php on line 901
```

- dans Library/ZendX/JQuery.php : upgrader les versions de JQuery et JQuery UI
```PHP
    const DEFAULT_JQUERY_VERSION = "3.6.0";
    const DEFAULT_UI_VERSION = "1.13.1";
```
puis récuperer le code source de JQuery et JQuery sur
   http://code.google.com/apis/ajaxlibs/documentation/index.html#jquery

et créer une arborescence locale reproduisant l'organisation des
répertoires de Google sur le principe suivant :
    http://localhost/ajax_googleapis_com/ajax/libs/


- dans Zend/Db/Adapter/Pdo/Ibm/Db2.php, modifier la méthode DescribeTable() :
```PHP
    public function describeTable($tableName, $schemaName = null)
    {
        /*
         * patch - Begin
         * patch proposé sur : http://framework.zend.com/issues/browse/ZF-10415
         * (suppression des fonctions UPPER à l'intérieur du code SQL, et
         * utilisation des fonction mb_strtoupper en amont)
         * + mise en variable de la clause DISTINCT qui est inutile si
         * le nom de la base est connu
        */
        $tableName = mb_strtoupper($tableName) ;
        if ($schemaName !== null) {
            $schemaName =  mb_strtoupper($schemaName);
            $distinct = '' ;
        } else {
            $distinct = 'DISTINCT' ;
        }

        $sql = "SELECT {$distinct} c.tabschema, c.tabname, c.colname, c.colno,
                c.typename, c.default, c.nulls, c.length, c.scale,
                c.identity, tc.type AS tabconsttype, k.colseq
                FROM syscat.columns c
                LEFT JOIN (syscat.keycoluse k JOIN syscat.tabconst tc
                 ON (k.tabschema = tc.tabschema
                   AND k.tabname = tc.tabname
                   AND tc.type = 'P'))
                 ON (c.tabschema = k.tabschema
                 AND c.tabname = k.tabname
                 AND c.colname = k.colname)
            WHERE "
            . $this->_adapter->quoteInto('c.tabname = ?', $tableName);
        if ($schemaName) {
            $sql .= $this->_adapter->quoteInto(' AND c.tabschema = ?', $schemaName);
        }
        /*
         * patch GJARRIGE - End
        */
```

- dans Zend/Db/Adapter/Db2.php, modifier la méthode DescribeTable() :
```PHP
    public function describeTable($tableName, $schemaName = null)
    {
        // Ensure the connection is made so that _isI5 is set
        $this->_connect();

        if ($schemaName === null && $this->_config['schema'] != null) {
            $schemaName = $this->_config['schema'];
        }
        /*
         * patch GJARRIGE - Begin
         * patch proposé sur : http://framework.zend.com/issues/browse/ZF-10415
         * (suppression des fonctions UPPER à l'intérieur du code SQL, et
         * utilisation des fonction mb_strtoupper en amont)
         * + mise en variable de la clause DISTINCT qui est inutile si
         * le nom de la base est connu
        */
        $tableName = mb_strtoupper($tableName) ;
        if ($schemaName !== null) {
            $schemaName =  mb_strtoupper($schemaName);
            $distinct = '' ;
        } else {
            $distinct = 'DISTINCT' ;
        }

        if (!$this->_isI5) {
            $sql = "SELECT {$distinct} c.tabschema, c.tabname, c.colname, c.colno,
                c.typename, c.default, c.nulls, c.length, c.scale,
                c.identity, tc.type AS tabconsttype, k.colseq
                FROM syscat.columns c
                LEFT JOIN (syscat.keycoluse k JOIN syscat.tabconst tc
                ON (k.tabschema = tc.tabschema
                    AND k.tabname = tc.tabname
                    AND tc.type = 'P'))
                ON (c.tabschema = k.tabschema
                    AND c.tabname = k.tabname
                    AND c.colname = k.colname)
                WHERE "
                . $this->quoteInto('c.tabname = ?', $tableName);

            if ($schemaName) {
               $sql .= $this->quoteInto(' AND c.tabschema = ?', $schemaName);
            }

            $sql .= " ORDER BY c.colno";

        } else {

            // DB2 On I5 specific query
            $sql = "SELECT {$distinct} C.TABLE_SCHEMA, C.TABLE_NAME, C.COLUMN_NAME, C.ORDINAL_POSITION,
                C.DATA_TYPE, C.COLUMN_DEFAULT, C.NULLS ,C.LENGTH, C.SCALE, LEFT(C.IDENTITY, 1),
                LEFT(tc.TYPE, 1) AS tabconsttype, k.COLSEQ
                FROM QSYS2.SYSCOLUMNS C
                LEFT JOIN (QSYS2.syskeycst k JOIN QSYS2.SYSCST tc
                    ON (k.TABLE_SCHEMA = tc.TABLE_SCHEMA
                      AND k.TABLE_NAME = tc.TABLE_NAME
                      AND LEFT(tc.type, 1) = 'P'))
                    ON (C.TABLE_SCHEMA = k.TABLE_SCHEMA
                       AND C.TABLE_NAME = k.TABLE_NAME
                       AND C.COLUMN_NAME = k.COLUMN_NAME)
                WHERE "
                . $this->quoteInto('C.TABLE_NAME = ?', $tableName);

            if ($schemaName) {
                $sql .= $this->quoteInto(' AND C.TABLE_SCHEMA = ?', $schemaName);
            }

            $sql .= " ORDER BY C.ORDINAL_POSITION FOR FETCH ONLY";
        }
        /*
         * patch GJARRIGE - End
         */
```

- modif facultative : ajout classe dans Zend/Form/Element/ :

```PHP
class Zend_Form_Element_Addhtml extends Zend_Form_Element
{
   private $_content;

    /**
     * Setter for the element content
     *
     * @param string
     */
   public function setContent($content)
   {
      $this->_content = $content;
   }

    /**
     * Render form element
     *
     * @param  Zend_View_Interface $view
     * @return string
     */
    public function render(Zend_View_Interface $view = null)
    {
        if(null !== $view) {
            $this->setView($view);
        }
        return $this->_content;
    }
}
```

## PDF

Pour générer les exports en PDF, je me suis appuyé sur les classes Gorilla_Pdf_Page et Gorilla_Pdf_Table, développées par Joseph Montanez, car elles simplifient beaucoup la création de rapports. Le site internet sur lequel ces classes étaient mises à disposition (gorilla3d.com) n'est plus accessible, et il y un flou artistique quant à la licence d'utilisation de ces deux classes.


## TODO 

Il y a plein de choses à améliorer dans cette application, comme par exemple :

- bloquer toute possibilité de suppression des personnes qui sont rattachées à des dossiers

- dans la table "prm_personne", il y a redondance entre la colonne "id" et la colonne "code". La première est un identifiant interne, la seconde est destinée à stocker un code plus parlant pour les utilisateurs. Mais en réalité cette colonne "code" n'est pas utilisée dans cette version de l'application, et sa présence peut prêter à confusion. Il conviendrait de faire évoluer le formulaire de saisie de dossier pour permettre la saisie du "code" des personnes, au lieu de leur "id" tout en faisant la translation "code" vers "id" avant enregistrement en base de données.

- implémenter un exemple de formulaire avec des champs en autocomplétion, soit au travers de la balise "datalist", soit au travers d'un composant JS tel que l'excellent "chosen" : https://harvesthq.github.io/chosen/

- refactoriser le code JS de la page d'édition de dossier pour qu'il soit réutilisable sur d'autres pages, 
en profiter pour externaliser le code JS dans des fichiers distincts, importés via le mot clé "import"

- le nom de la personne n'apparaît que sur le formulaire d'édition, il conviendrait de l'ajouter aux formulaires de visu et de suppression

- dans la fenêtre modale, charger la liste des personnes dynamiquement, page par page, avec un mécanisme de 
pagination intégré, car la solution actuelle n'est viable que pour des tables contenant peu de lignes (performances désastreuses sur de grosses tables). A noter que ce chargement dynamique (page par page) aura un impact sur la manière de charger le nom de la personne (sur les formulaires d'édition, d'affichage et de suppression)


Du côté de ZF1, quelques idées d'améliorations :

- développer de nouveaux décorateurs pour le composant Zend_Form, permettant de mieux exploiter les classes CSS des formulaires de Bootstrap 

- réfléchir à la possibilité d'intégrer dans le composant Zend_Form, les nouveaux types de balises "input" du HTML5 (cf. liste ci-dessous), ainsi que la balise "datalist" :
```HTML
<input type="search"> pour la saisie dans une "boîte" de recherche
<input type="email"> pour la saisie d'email
<input type="url"> pour la saisie d'URL
<input type="tel"> pour la saisie de numéro de téléphone
<input type="number"> pour la saisie de nombre
<input type="range"> pour la saisie de donnée au moyen d'un curseur ("slider")
<input type="date"> pour la saisie de date
<input type="month"> pour la saisie de mois
<input type="week"> pour la saisie de semaines
<input type="time"> pour la saisie d'heure/minutes/secondes
<input type="datetime"> pour la saisie de timestamps 
<input type="datetime-local"> pour la saisie de timestamps en temps absolu (UTC)
<input type="color"> pour des effets de type "colorpicker"
```
