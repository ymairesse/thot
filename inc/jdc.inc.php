<?php

require_once INSTALL_DIR.'/inc/classes/classJdc.inc.php';
$jdc = new Jdc();

$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
$viewState = isset($_POST['viewState']) ? $_POST['viewState'] : null;

$smarty->assign('legendeCouleurs', $jdc->categoriesTravaux());
$smarty->assign('identite', $User->getIdentite());
$smarty->assign('corpsPage', 'jdc');
