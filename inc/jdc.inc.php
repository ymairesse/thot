<?php

require_once INSTALL_DIR.'/inc/classes/classJdc.inc.php';
$jdc = new Jdc();

$smarty->assign('legendeCouleurs', $jdc->categoriesTravaux());
$smarty->assign('identite', $User->getIdentite());
$smarty->assign('corpsPage', 'jdc');
