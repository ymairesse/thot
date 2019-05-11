<?php

require_once '../config.inc.php';

require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();

// définition de la class USER utilisée en variable de SESSION
require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
session_start();
$User = isset($_SESSION[APPLICATION]) ? unserialize($_SESSION[APPLICATION]) : Null;

$matricule = $User->getMatricule();

$start = $_GET['start'];
$end = $_GET['end'];

require_once INSTALL_DIR.'/inc/classes/classJdc.inc.php';
$Jdc = new Jdc();

$sharedEvents = $Jdc->retreiveSharedEvents($start, $end, $matricule, false);
$personnalEvents = $Jdc->retreivePersonnalEvents($start, $end, $matricule, true);
$liste = array_merge($personnalEvents, $sharedEvents);

echo json_encode($liste);
