<?php

$matricule = $User->getMatricule();
$classe = $User->getClasse();
$niveau = substr($classe,0,1);

$listeAnnonces = $Application->listeAnnonces($matricule,$classe);
$listeAccuses = $Application->listeAccusesEleve($matricule);

$listeAnnoncesAccuses = $Application->listeAnnoncesAccuses($listeAnnonces, $listeAccuses);
$shortListeAccuses = $Application->shortListeAccuses($listeAnnoncesAccuses, $matricule, $classe);
$nbAccuses = $Application->nbAccuses($shortListeAccuses);

$smarty->assign('listeAnnonces',$listeAnnoncesAccuses);
$smarty->assign('shortListeAccuses',$shortListeAccuses);
$smarty->assign('nbAccuses',$nbAccuses);

$smarty->assign('matricule',$matricule);
$smarty->assign('classe',$classe);
$smarty->assign('niveau',$niveau);
$smarty->assign('nom',$User->getNom());
$smarty->assign('nomEleve',$User->getNomEleve());

$smarty->assign('corpsPage','annonces');
?>
