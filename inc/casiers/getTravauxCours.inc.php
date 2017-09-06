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

require_once INSTALL_DIR.'/inc/classes/Files.class.php';
$Files = new Files();

$coursGrp = $Application->postOrCookie('coursGrp');
$idTravail = $Application->postOrCookie('idTravail');

$listeTravauxCours = $Files->getTravaux4Cours($coursGrp, array('readonly', 'readwrite', 'termine'));
$listeArchives = $Files->getTravaux4Cours($coursGrp, array('archive'));

require_once(INSTALL_DIR.'/smarty/Smarty.class.php');
$smarty = new Smarty();
$smarty->template_dir = '../../templates';
$smarty->compile_dir = '../../templates_c';

$smarty->assign('listeTravauxCours', $listeTravauxCours);
$smarty->assign('listeArchives', $listeArchives);
$smarty->assign('coursGrp', $coursGrp);
$smarty->assign('idTravail', $idTravail);

$smarty->display('casiers/listeTravauxCours.tpl');
