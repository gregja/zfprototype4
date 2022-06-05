<?php
abstract class Sanitize {
    /*
     * méthode pour protéger les paramètres GET de différents types d'attaques
     */
    public static function blinderGet($getchk, $san_type = "", $san_func = "" ) {
        if (array_key_exists($getchk, $_GET)) {
            $tmp_get = $_GET[$getchk] ;
            $getclean = self::sanitizeVar($tmp_get, $san_type, $san_func) ;
        } else {
            $getclean = '' ;
        }
        return $getclean ;
    }


    /*
     *  méthode pour protéger les paramètres POST de différents types d'attaques
     */
    public static function blinderPost($postchk, $san_type = "", $san_func = "") {
        if (array_key_exists($postchk, $_POST)) {
            $tmp_post = $_POST[$postchk] ;
            $postclean = self::sanitizeVar($tmp_post, $san_type, $san_func) ;
        } else {
            $postclean = '' ;
        }
        return $postclean ;
    }


    public static function sanitizeVar($san_var, $san_type = "", $san_func = "") {

        $san_var = trim($san_var) ;
        $tmp_var = '' ;
        $lng_var = mb_strlen($san_var) ;
        /*
         *  Les chaînes de plus de 5000 caractères sont rejetées systématiquement
         */
        if ($lng_var > 0 && $lng_var < 5000) {
            /*
             *  Convertit les caractères spéciaux en entités HTML, y compris les quotes et double-quotes
             */
            $tmp_var = htmlspecialchars($san_var, ENT_QUOTES);

            /*
             *  force un type particulier si précisé dans l'appel de la fonction
             */
            $arr_types = array("boolean", "bool", "integer", "int", "float", "string") ;
            if ($san_type != '' && in_array($san_type, $arr_types)) {
                if (!settype($tmp_var, $san_type)) {
                    $tmp_var = '';
                }
            }

            /*
             *  applique une fonction particulière si précisée dans l'appel de la fonction
             */
            if ($san_func != '' && is_callable($san_func)) {
                /*
                 * apply functions to the variables, you can use the standard PHP
                 * functions, but also use your own for added flexibility.
                 */
                $tmp_var = $san_func($tmp_var) ;
            }
        }
        return $tmp_var ;
    }

    /*
     * Expression régulière proposée sur le site sitepoint :
     * http://www.sitepoint.com/blogs/2005/04/19/character-encodings-and-input/
     * élimine les caractères non compatibles avec la norme utf-8
     */
    public static function nettoyageChaine ($chaine_sale) {
        return preg_replace('/[^\x09\x0A\x0D\x20-\x7F\xC0-\xFF]/', ' ', $chaine_sale);
    }

    /*
     * 
     */
    public static function controle_type ($var, $type) {
        $retour = false ;
        $type = mb_strtolower($type);
        $var  = trim($var);
        switch ($type) {
            case 'intabs':{
                $retour = strval($var) == strval(abs(intval($var)));
                break;
            }
            case 'int':{
                $retour = strval($var) == strval(intval($var));
                break;
            }
            case 'float':{
                $retour = strval($var) == strval(floatval($var));
                break;
            }
        }
        return $retour ;
    }

}
