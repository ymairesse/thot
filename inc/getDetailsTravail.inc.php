<?php

require_once '../config.inc.php';

require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();
session_start();

require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
$User = unserialize($_SESSION['THOT']);

$matricule = $User->getMatricule();

$idTravail = isset($_POST['idTravail']) ? $_POST['idTravail'] : null;

require_once INSTALL_DIR.'/inc/classes/Files.class.php';
$Files = new Files();

$detailsTravail = $Files->getDetailsTravail($idTravail, $matricule);

echo json_encode($detailsTravail);
