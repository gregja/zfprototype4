<?php

/**
 *
 * @author gregory
 */
abstract class CrudOutils {

    /**
     * génère une chaîne de caractères aléatoire
     * @param <type> $car
     * @return string 
     */
    public static function random($car) {
        $string = "";
        $chaine = "abcdefghijklmnpqrstuvwxy";
        srand((double) microtime() * 1000000);
        for ($i = 0; $i < $car; $i++) {
            $string .= $chaine[rand() % strlen($chaine)];
        }
        return $string;
    }

    public static function trim($data) {
        if (is_null($data)) {
            return '';
        } else {
            if (is_string($data)) {
                return trim($data);
            } else {
                if (is_numeric($data)) {
                    return strval($data);
                } else {
                    error_log('Type incompatible avec la fonction CrudOutils::trim(), donnée ignorée');
                    return '';
                }
            }
        }
    }

    /**
     * Sélecteur SQL pour formulaire de recherche
     * @param <type> $value
     * @return <type>
     * - si aucun paramètre transmis, alors la méthode renvoie le tableau
     *   des sélecteurs avec leurs codes littéraux (*eq...) et leurs libellés
     * - si un paramètre est transmis, sa valeur est recherchée dans le
     *   tableau des sélecteurs -> si trouvé, alors "code" sql renvoyé
     *                          -> si non trouvé, alors Null renvoyé
     */
    public static function SelectorSQL($value=null) {
        $tableau = array(
            '*eq' => array('code' => '=', 'libelle' => 'égal'),
            '*lt' => array('code' => '<', 'libelle' => 'inférieur'),
            '*le' => array('code' => '<=', 'libelle' => 'inférieur ou égal'),
            '*gt' => array('code' => '>', 'libelle' => 'supérieur'),
            '*ge' => array('code' => '>=', 'libelle' => 'supérieur ou égal'),
            '*ne' => array('code' => '<>', 'libelle' => 'différent'),
            '*lk' => array('code' => 'like', 'libelle' => 'contient'),
            '*bg' => array('code' => 'begin', 'libelle' => 'commence par')
        );
        if ($value === null) {
            $tableau_retour = array();
            foreach ($tableau as $key => $data) {
                $tableau_retour [$key] = $data['libelle'];
            }
            return $tableau_retour;
        } else {
            if (array_key_exists($value, $tableau)) {
                return $tableau[$value]['code'];
            } else {
                return null;
            }
        }
    }

}

