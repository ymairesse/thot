<?php

require_once '../../config.inc.php';

require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();
session_start();

// Application::afficher($_SESSION[APPLICATION], true);

if (!(isset($_SESSION[APPLICATION]))) {
    echo "<script type='text/javascript'>document.location.replace('".BASEDIR."');</script>";
    exit;
}

require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
$User = unserialize($_SESSION[APPLICATION]);
$oldUser = $User->getUserName();
$User->delogger();

$newUser = (isset($_POST['newUser'])) ? $_POST['newUser'] : null;

if ($User->checkSameFratrie($oldUser, $newUser) == 0)
    die('tricheur');

$User = new user($newUser, 'parent');
$userName = $User->getUserName();

$_SESSION[APPLICATION] = serialize($User);

$User->logger($User);

$nomEleve = $User->getNomEleve();
echo $nomEleve;
