<?php
// script de test
foreach ($_POST as $key => $value) {
    error_log($key . ' => ' . $value ) ;
}
