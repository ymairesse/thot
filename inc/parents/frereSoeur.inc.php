<?php

$userName = $User->getUserName();
$fratrie = $User->getComptesFratrie($userName);
$eleves = $User->getEleve4Parent($fratrie);

$smarty->assign('nomEleve', $User->getNomEleve());
$smarty->assign('fratrie', $fratrie);
$smarty->assign('eleves', $eleves);

$smarty->assign('corpsPage', 'parents/frereSoeur');
