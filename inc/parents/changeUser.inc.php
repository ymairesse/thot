<?php

require_once '../../config.inc.php';

require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();
session_start();

if (!(isset($_SESSION[APPLICATION]))) {
    echo "<script type='text/javascript'>document.location.replace('".BASEDIR."');</script>";
    exit;
}

require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
$User = unserialize($_SESSION[APPLICATION]);

$newUser = (isset($_POST['newUser'])) ? $_POST['newUser'] : null;

$User = new user($newUser, 'parent');

$identite = $User->getIdentite();

$matricule = $User->getMatricule();
$nomEleve = $User->getNomEleve();

$_SESSION[APPLICATION] = serialize($User);

$User->logger($User);

$nomEleve = $User->getNomEleve();

echo $nomEleve;
