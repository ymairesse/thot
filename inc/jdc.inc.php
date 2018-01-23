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
    case 'classe':
        // on vérifie une dernière fois que l'élève actif a la charge du JDC
        if (!($Jdc->isChargeJDC($matricule)))
            die('Tu n\'es pas en charge du JDC pour cette période');
        $categories = $Jdc->categoriesTravaux();
        $smarty->assign('categories', $categories);
        $smarty->assign('corpsPage', 'jdc/writeJDC');
        break;
    case 'liste':
        $listeCharges = $Jdc->getChargesJDC($classe);
        $smarty->assign('listeCharges', $listeCharges);
        $smarty->assign('corpsPage', 'jdc/listeCharges');
        break;
    default:
        $smarty->assign('corpsPage', 'jdc');
        break;
}
