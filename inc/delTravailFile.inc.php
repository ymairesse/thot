<?php

require_once '../config.inc.php';

require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();
session_start();

require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
$User = unserialize($_SESSION['THOT']);

$matricule = $User->getMatricule();

require_once INSTALL_DIR.'/inc/classes/Files.class.php';
$Files = new Files();

$idTravail = isset($_POST['idTravail']) ? $_POST['idTravail'] : null;
$dataTravail = $Files->getDetailsTravail($idTravail, $matricule);
// Application::afficher($dataTravail, true);
$fileName = $dataTravail['fileInfos']['fileName'];
$acronyme = $dataTravail['acronyme'];

$ds = DIRECTORY_SEPARATOR;
$path = INSTALL_ZEUS.$ds.'upload'.$ds.$acronyme.$ds.'#thot'.$ds.$idTravail.$ds.$matricule.$ds.$fileName;

require_once(INSTALL_DIR."/smarty/Smarty.class.php");
$smarty = new Smarty();
$smarty->template_dir = "../templates";
$smarty->compile_dir = "../templates_c";

if (@unlink($path)) {
    $dataTravail = $Files->getDetailsTravail($idTravail, $matricule);
    $Files->travailRemis($idTravail, $matricule, false);
    $smarty->assign('data', $dataTravail);
    $smarty->assign('idTravail', $idTravail);
    echo $smarty->fetch('files/unCasier.tpl');
    }
    else echo 0;
