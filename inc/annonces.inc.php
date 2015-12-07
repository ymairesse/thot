<?php
// effacement des annoncés périmées
$Application->delPerimes();

$matricule = $User->getMatricule();
$classe = $User->getClasse();
$niveau = substr($classe,0,1);
$listeCoursEleve = $User->listeCoursEleve();

// création de la liste des annonces pour l'élève, fonction de son matricule, de sa classe
// -et donc de son niveau d'étude- et de sa liste de cours pour chacune des catégories: élève, cours, classe, niveau, école
$listeAnnonces = $Application->listeAnnonces($matricule,$classe,$listeCoursEleve);

// liste des annonces dans la table des accusés de lecture
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
