<?php

session_start();
require_once '../config.inc.php';
// définition de la class Application
require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();

require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';

$User = unserialize($_SESSION['THOT']);

$matricule = $User->getMatricule();
$id = isset($_POST['id']) ? $_POST['id'] : null;
$dateHeure = $Application->marqueAccuse($matricule, $id);

echo 'Lu le '.$dateHeure;
