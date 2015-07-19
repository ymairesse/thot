<?php

$matricule = $User->getMatricule();
$classe = $User->getClasse();

$listeAnnonces = $Application->listeAnnonces($matricule,$classe);
$smarty->assign('listeAnnonces',$listeAnnonces);
$smarty->assign('matricule',$matricule);
$smarty->assign('classe',$classe);
$smarty->assign('niveau',substr($classe,0,1));
$smarty->assign('nom',$User->getNom());

$smarty->assign('corpsPage','annonces');
?>