<?php
/*
 * API de récupération d'une fiche "personne", à partir de son identifiant
 */
$path_current = dirname(dirname(__FILE__));
require ($path_current . DIRECTORY_SEPARATOR . 'configenv.php');

/*
 * réception du paramètre transmis par le champ en auto-complétion
 */
$critere = Sanitize::blinderGet('term', 'string');

/**
 * Objectif : extraire la liste des 20 premières personnes dont le nom
 * commence par... la saisie de l'utilisateur
 * $sql = "SELECT libelle FROM prm_personne where libelle like 'A%' limit 20 " ;
 */
if ($critere != '') {
    $critere = intval($critere);

    $select = $db->select();
    $select->from(array('prm_personne'), array('id', 'code', 'libelle'));
    $select->where('id = ?', $critere);

    $datas = $db->fetchAll($select);
    if (count($datas)>0) {
        echo json_encode($datas[0]);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
