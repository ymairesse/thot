<?php

// require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
// $User = unserialize($_SESSION['THOT']);

$matricule = $User->getMatricule();
$classe = $User->getClasse();
$niveau = substr($classe, 0, 1);
$listeCoursEleve = $User->listeCoursEleve();
$listeCoursString = "'".implode("','", $listeCoursEleve)."'";

require_once INSTALL_DIR.'/inc/classes/Files.class.php';
$Files = new Files();
$listeDocs = $Files->listeDocumentsCasiers ($listeCoursString, $matricule);

$smarty->assign('listeDocs', $listeDocs);

// présenter les formulaires
$smarty->assign('corpsPage', 'files/casiers');
