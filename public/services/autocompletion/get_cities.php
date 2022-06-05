<?php

switch ($_REQUEST['country']) {
    case 'fr': {
            $cities = array(
                'Paris', 'Bordeaux', 'Lyon', 'Toulouse', 'Lille', 'Strasbourg'
            );
            break;
        }
    case 'de': {
            $cities = array(
                'Berlin', 'Bern', 'Munich', 'Francfort'
            );
            break;
        }
    case 'ie': {
            $cities = array(
                'Cork', 'Dublin', 'Galway', 'Limerick', 'Waterford'
            );
            break;
        }
    case 'uk': {
            $cities = array(
                'Bath', 'Birmingham', 'Bradford',
                'Brighton &amp; Hove', 'Bristol',
                'Cambridge', 'Canterbury', 'Carlisle',
                'Chester', 'Chichester', 'Coventry',
                'Derby', 'Durham', 'Ely', 'Exeter',
                'Gloucester', 'Hereford', 'Kingston upon Hull',
                /* and on and on! */
                'Newport', 'St David\'s', 'Swansea'
            );
            break;
        }
    default:
        $cities = false;
}
if (!$cities)
    echo 'please choose a country first';
else
    echo '<select name="city"><option>',
    join('</option><option>', $cities),
    '</select>';
?>