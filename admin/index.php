<?php
session_start();
require_once('../config.inc.php');
include ('../inc/entetes.inc.php');

// définition de la class USER (pour le footer, au moins)
require_once (INSTALL_DIR."admin/inc/classes/classUserAdmin.inc.php");

switch ($action) {
	
	
}



// toutes les informations d'identification réseau (adresse IP, jour et heure)
$smarty->assign ('identiteReseau', user::identiteReseau());

$smarty->assign('TITREGENERAL',TITREGENERAL);
$smarty->assign('executionTime', round($chrono->stop(),6));
$smarty->display ('index.tpl');
?>
