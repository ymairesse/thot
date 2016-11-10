<?php

require_once '../../config.inc.php';

require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();

// définition de la class USER utilisée en variable de SESSION
require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
session_start();
if (!(isset($_SESSION[APPLICATION]))) {
    die("<div class='alert alert-danger'>Votre session a expiré. Veuillez vous reconnecter.</div>");
}
$User = unserialize($_SESSION['THOT']);
$matricule = $User->getMatricule();

require_once INSTALL_DIR.'/inc/classes/Files.class.php';
$Files = new Files();

$idTravail = isset($_POST['idTravail']) ? $_POST['idTravail'] : null;

require_once INSTALL_DIR.'/smarty/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = '../../templates';
$smarty->compile_dir = '../../templates_c';

$dataTravail = $Files->getDetailsTravail($idTravail, $matricule);
$smarty->assign('data', $dataTravail);
$smarty->assign('idTravail', $idTravail);
echo $smarty->fetch('files/unCasier.tpl');
