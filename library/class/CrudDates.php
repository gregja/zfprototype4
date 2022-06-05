<?php
/**
 * Description of CrudDates
 *
 * @author gregory
 */
abstract class CrudDates {

    public static function mysql2date($stDate) {
        $date = null;
        if ($stDate != null) {
            $date_year = substr($stDate,0,4);
            $date_month = substr($stDate,5,2);
            $date_day = substr($stDate,8,2);

            $date = @date("d/m/Y", mktime(0, 0, 0,
                    $date_month, $date_day, $date_year));
        }

        if ($date === '12/31/1969')
            $date = null;

        return $date;
    }

    public static function date3Parts2One($stYear, $stMonth, $stDay, $format='dmy') {
        $stYear = trim(strval($stYear));
        $stMonth = trim(strval($stMonth));
        $stDay = trim(strval($stDay));
        if (strlen($stMonth) == 1)
            $stMonth = '0' . $stMonth;
        if (strlen($stDay) == 1)
            $stDay = '0' . $stDay;

        switch (strtolower($format)) {
            case "dmy":
                $return_date = $stDay . '/' . $stMonth . '/' . $stYear;
                break;
            case "mdy":
                $return_date = $stMonth . '/' . $stDay . '/' . $stYear;
                break;
            case "ymd":
                $return_date = $stYear. '/' . $stMonth . '/' . $stDay ;
                break;
            default:
                /*
                 * si format incorrect, alors renvoi du format "dmy" par défaut
                 */
                $return_date = $stDay . '/' . $stMonth . '/' . $$stYear;
        }
        return $return_date ;
    }

}
