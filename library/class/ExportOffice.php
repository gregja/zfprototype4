<?php

/**
 * Description of class ExportOffice
 *
 * @author gregory
 */
abstract class ExportOffice {

    public static function params($type_export, $nom_fichier='') {
        $type_export = strtolower($type_export);
        if ($type_export == 'xls') {
            return self::excel($nom_fichier);
        } else {
            if ($type_export == 'doc') {
                return self::word($nom_fichier);
            } else {
                if ($type_export == 'pdf') {
                    return self::pdf($nom_fichier);
                } else {
                    /*
                     * renvoi d'un tableau vide (par précaution)
                     */
                    return array();
                }
            }
        }
    }

    public static function excel($nom_fichier='') {
        $params = array();
        $params['Content-type'] = 'application/x-msexcel';
//    $params['Content-type'] = 'application/x-msdownload';
        $nom_fichier = trim($nom_fichier);
        if ($nom_fichier == '') {
            $nom_fichier = 'export_data' ;
        }
        $params['Content-Disposition'] = "attachment; filename={$nom_fichier}.xls";
        $params['Pragma'] = 'no-cache';
        $params['Cache-Control'] = 'no-store, no-cache, must-revalidate';
        $params['Expires'] = 'Sat, 26 Jul 1997 05:00:00 GMT';

        return $params;
    }

    public static function word($nom_fichier='') {
        $params = array();
        $params['Content-type'] = 'application/doc';
        $nom_fichier = trim($nom_fichier);
        if ($nom_fichier == '') {
            $nom_fichier = 'export_data' ;
        }
        $params['Content-Disposition'] = "attachment; filename={$nom_fichier}.doc";
        $params['Pragma'] = 'no-cache';
        $params['Cache-Control'] = 'no-store, no-cache, must-revalidate';
        $params['Expires'] = 'Sat, 26 Jul 1997 05:00:00 GMT';

        return $params;
    }

    /*
     * l'export PDF ne peut être réalisé aussi simplement que l'export XLS ou
     * DOC, mais l'initialisation de l'entête obéit au même principe
     */

    public static function pdf($nom_fichier='') {
        $params = array();
        $nom_fichier = trim($nom_fichier);
        if ($nom_fichier == '') {
            $nom_fichier = 'export_data';
        }

        $params['Content-type'] = 'application/pdf';
        //   $params['Content-Length'] = $size;
        $params['Content-Disposition'] = "attachment; filename={$nom_fichier}.pdf";
        $params['Pragma'] = 'no-cache';
        $params['Cache-Control'] = 'no-store, no-cache, must-revalidate';
        $params['Expires'] = 'Sat, 26 Jul 1997 05:00:00 GMT';

        return $params;
    }

    public static function json() {
        $params = array();
        $params['Content-type'] = 'application/json';
        $params['Pragma'] = 'no-cache';
        $params['Cache-Control'] = 'no-store, no-cache, must-revalidate';
        $params['Expires'] = 'Sat, 26 Jul 1997 05:00:00 GMT';

        return $params;
    }
}
