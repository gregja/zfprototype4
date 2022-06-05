<?php
/* 
 * Menu de navigation pour la langue FR
 */

$naviglobal_data = array(
    array(
        'link' => 'index',
        'description' => 'Accueil',
        'auth' => '*all'
    ),
    array(
        'link' => 'dossiers',
        'description' => 'Dossiers',
        'auth' => '*auth'
    ),
    array(
        'link' => 'personnes',
        'description' => 'Personnes',
        'auth' => '*auth'
    ),
    array(
        'link' => 'login',
        'description' => 'Login',
        'auth' => '*noauth'
    ),
    array(
        'link' => 'login/logout',
        'description' => 'Logout',
        'auth' => '*auth'
    )
);
