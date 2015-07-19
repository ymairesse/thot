<?php
session_start();
require_once('config.inc.php');
include ('inc/entetes.inc.php');

// définition de la class USER
require_once (INSTALL_DIR."/inc/classes/classUser.inc.php");

$smarty->assign('nom',$User->getNom());

switch ($action) {
	case 'admin':

		break;
	case 'annonces':
		require_once('inc/annonces.inc.php');
		break;
	case 'bulletin':
		require_once('inc/bulletin.inc.php');
		break;
	case 'anniversaires':
		require_once('inc/anniversaires.inc.php');
		break;
	case 'jdc':
		require_once('inc/jdc.inc.php');
		break;
	case 'parents':
		require_once('inc/parents.inc.php');
		break;
	case 'logoff':
		include_once('logout.php');
		break;
	default:
		require_once('inc/annonces.inc.php');
		break;

}


$smarty->assign('action',$action);

// toutes les informations d'identification réseau (adresse IP, jour et heure)
$smarty->assign ('identiteReseau', user::identiteReseau());

$smarty->assign('TITREGENERAL',TITREGENERAL);
$smarty->assign('executionTime', round($chrono->stop(),6));
$smarty->display ('index.tpl');
?>
