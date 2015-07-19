<?php

// définition de la class USER utilisée en variable de SESSION
require_once(INSTALL_DIR."/admin/inc/classes/classUserAdmin.inc.php");

// définition de la class Application
require_once (INSTALL_DIR."/inc/classes/classApplication.inc.php");
$Application = new Application();

// définition de la class Chrono
require_once (INSTALL_DIR."/inc/classes/classChrono.inc.php");
$chrono = new chrono();

$Application->Normalisation();
$User = isset($_SESSION[APPLICATION])?unserialize($_SESSION[APPLICATION]):Null;
var_dump($_SESSION);
if (!(isset($User)))
 	header ("Location: accueil.php");

require_once(INSTALL_DIR."/smarty/Smarty.class.php");
$smarty = new Smarty();
$smarty->assign('titre', TITREGENERAL);

// toutes les informations d'identité, y compris nom, prénom,,...
$smarty->assign('identite',$User->getIdentite());

// toutes les informations d'identification réseau (adresse IP, jour et heure)
$smarty->assign ('identiteReseau', $User->identiteReseau());
$smarty->assign('nom',$User->userName());

// récupération de 'action' et 'mode' qui définissent toujours l'action principale à prendre
// d'autres paramètres peuvent être récupérés plus loin
$action = isset($_REQUEST['action'])?$_REQUEST['action']:Null;
$mode = isset($_REQUEST['mode'])?$_REQUEST['mode']:Null;
$etape = isset($_REQUEST['etape'])?$_REQUEST['etape']:Null;


/* pas de balise ?> finale, c'est volontaire */
