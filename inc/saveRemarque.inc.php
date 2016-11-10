<?php

require_once '../config.inc.php';

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
$classe = $User->getClasse();
$niveau = substr($classe, 0, 1);
$listeCoursEleve = $User->listeCoursEleve();
$listeCoursString = "'".implode("','", $listeCoursEleve)."'";

// définition de la class Chrono
require_once INSTALL_DIR.'/inc/classes/Files.class.php';
$Files = new Files();

$idTravail = isset($_POST['idTravail']) ? $_POST['idTravail'] : null;
$remarque = isset($_POST['remarque']) ? $_POST['remarque'] : null;

// vérifier que l'élève doit effectivement remettre ce travail
$verif = $Files->verifEleve4Travail($matricule, $idTravail);

if ($verif == true)  {
    $nb = $Files->saveRemarqueEleve($remarque, $idTravail, $matricule);
    require_once INSTALL_DIR.'/smarty/Smarty.class.php';
    $smarty = new Smarty();
    $smarty->template_dir = '../templates';
    $smarty->compile_dir = '../templates_c';

    $dataTravail = $Files->getDetailsTravail($idTravail, $matricule);
    $smarty->assign('data', $dataTravail);
    $smarty->assign('idTravail', $idTravail);
    echo $smarty->fetch('files/unCasier.tpl');
}
else die('erreur de travail ou de matricule');
