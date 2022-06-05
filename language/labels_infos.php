<?php
/*
 * Les messages d'erreur standards utilisés par Zend_Form sont définis dans
 * les classes du composant Zend_Validate
 *
 * Par exemple : les messages de contrôle pour les données alphabétiques
 *   sont définis dans le fichier Zend/Validate/Alpha.php (cf. extraits
 *   ci-dessous)
 *
    const INVALID      = 'alphaInvalid';
    const NOT_ALPHA    = 'notAlpha';
    const STRING_EMPTY = 'alphaStringEmpty';

    protected $_messageTemplates = array(
        self::INVALID      => "Invalid type given. String expected",
        self::NOT_ALPHA    => "'%value%' contains non alphabetic characters",
        self::STRING_EMPTY => "'%value%' is an empty string"
    );

La traduction de ces messages dans le fichier labels_fr.php se fera au moyen
du code suivant :

return array(
    'alphaInvalid' => "Type de données invalide, type String attendu",
    'notAlpha' => "'%value%' contient des caractères non alphabétiques",
    'alphaStringEmpty' => "'%value%' est une chaîne vide"
);

 *
 */
