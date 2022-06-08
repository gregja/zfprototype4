<?php
/*
 * API de recherche des personnes avec fonction de pagination
 * recherche de type LIKE %XXX% quand un nom est transmis
 */
$path_current = dirname(dirname(__FILE__));
require ($path_current . DIRECTORY_SEPARATOR . 'configenv.php');

/*
 * réception du paramètre transmis par le champ en auto-complétion
 */

$critere = Sanitize::blinderGet('term', 'string');
$count = Sanitize::blinderGet('count', 'int');
$offset = Sanitize::blinderGet('offset', 'int');
if ($offset > 0) {
    $offset--;
}

$nb = 0;

$selCount = $db->select();
$selCount->from(array('prm_personne'), array('count(*) as nb'));
if ($critere != '') {
    // recherche de type contient
    $selCount->where('libelle LIKE ?', '%'.$critere.'%');
}
$datas = $db->fetchAll($selCount);

if ($datas && count($datas) > 0) {
    $nb = (int)$datas[0]['nb'];
} 


$select = $db->select();
$select->from(array('prm_personne'), array('id', 'code', 'libelle'));
if ($critere != '') {
    // recherche de type contient
    $select->where('libelle LIKE ?', '%'.$critere.'%');
}
$select->order('libelle');
if ($count != 0 || $offset != 0) {
    $select->limit($count, $offset); 
}

$datas = $db->fetchAll($select);

$resultat = [
    'count' => $nb,
    'datas' => $datas
];

echo json_encode($resultat);

