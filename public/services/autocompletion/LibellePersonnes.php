<?php
/*
 * Interface pour Auto-completion (via JQuery UI) sur formulaires :
 * la recherche s'effectue sur les premiers caractères du nom d'une personne
 */
$path_current = dirname(dirname(dirname(__FILE__)));
require ($path_current . DIRECTORY_SEPARATOR . 'configenv.php');

/*
 * réception du paramètre transmis par le champ en auto-complétion
 */
if (array_key_exists('term', $_GET)) {
    $critere = Sanitize::blinderGet('term');
} else {
    $critere = '';
}
/**
 * Objectif : extraire la liste des 20 premières personnes dont le nom
 * commence par... la saisie de l'utilisateur
 * $sql = "SELECT libelle FROM prm_personne where libelle like 'A%' limit 20 " ;
 */
if ($critere != '') {
    $critere .= '%';

    $select = $db->select();
    $select->from(array('prm_personne'), array('distinct(libelle) as libelle'));
    $select->where('libelle like ?', $critere);

    if (mb_strlen($critere)==2) {
        $limite = 10 ;
    } else {
        $limite = 15 ;
    }
    $select->limit($limite);

    $personnes = $db->fetchAll($select);

    $resultat = array() ;
    foreach($personnes as $key=>$value) {
        $resultat []= $value['libelle'] ;
    }
    
    echo json_encode($resultat);
}

