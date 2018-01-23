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

$matricule = $User->getMatricule();

require_once INSTALL_DIR."/inc/classes/classJdc.inc.php";
$Jdc = new Jdc();

$categories = $Jdc->categoriesTravaux();

$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
$heure = isset($_POST['heure']) ? $_POST['heure'] : null;
if ($heure != Null) {
    $heure = $Jdc->heureLaPlusProche($heure);
}

$listePeriodes = $Jdc->lirePeriodesCours();

$classe = $User->getClasse();
$listeCours = $User->listeDetailCoursEleve();

require_once INSTALL_DIR.'/smarty/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = '../../templates';
$smarty->compile_dir = '../../templates_c';

$smarty->assign('categories', $categories);
$smarty->assign('listeCours', $listeCours);
$smarty->assign('classe', $classe);
$smarty->assign('listePeriodes', $listePeriodes);

$smarty->assign('startDate', $startDate);
$smarty->assign('heure', $heure);

$smarty->display('jdc/modalEdit.tpl');
