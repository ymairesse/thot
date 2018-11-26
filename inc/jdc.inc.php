<?php

require_once INSTALL_DIR.'/inc/classes/classJdc.inc.php';
$Jdc = new Jdc();

$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
$viewState = isset($_POST['viewState']) ? $_POST['viewState'] : null;

$categories = $Jdc->categoriesTravaux();
$classe = $User->getClasse();
$listeCours = $User->listeDetailCoursEleve();

$smarty->assign('legendeCouleurs', $categories);
$smarty->assign('categories', $categories);
$smarty->assign('identite', $User->getIdentite());
$smarty->assign('classe', $classe);
$smarty->assign('listeCours', $listeCours);

switch ($mode) {
    case 'perso':
        $categories = $Jdc->categoriesTravaux();
        $smarty->assign('categories', $categories);
        $smarty->assign('corpsPage', 'jdc/jdcPerso');
        break;

    default:
        $smarty->assign('corpsPage', 'jdc');
        break;
}
