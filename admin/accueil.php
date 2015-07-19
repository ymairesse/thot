<?php
require_once('../config.inc.php');

// définition de la class Application
require_once ("../inc/classes/classApplication.inc.php");
$Application = new Application();

// définition de la class User
require_once (INSTALL_DIR."/admin/inc/classes/classUserAdmin.inc.php");
$User = new UserAdmin();
session_start();
var_dump($_SESSION);

// définition de la class Chrono
require_once ("../inc/classes/classChrono.inc.php");
$chrono = new chrono();

$Application->Normalisation();

$message = isset($_REQUEST['message'])?$_REQUEST['message']:Null;

require_once("../smarty/Smarty.class.php");
$smarty = new Smarty();

// toutes les informations d'identification réseau (adresse IP, jour et heure)
$smarty->assign ('identiteReseau', $User->identiteReseau());
$smarty->assign('message',$message);
$smarty->assign('TITREGENERAL',TITREGENERAL);
$smarty->assign('executionTime', round($chrono->stop(),6));
$smarty->display('accueil.tpl');
?>
