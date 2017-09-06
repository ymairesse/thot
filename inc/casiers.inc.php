<?php

$matricule = $User->getMatricule();

require_once INSTALL_DIR.'/inc/classes/Files.class.php';
$Files = new Files();

$listeCours = $User->listeCoursEleve();
$listeCoursString = "'".implode("','", $listeCours)."'";

// liste de tous les travaux indexés sur les cours; les keys donnent donc la liste des cours
// "avec travaux"
$listeCoursAvecTravaux = $Files->listeDocumentsCasiers($listeCoursString, $matricule);

$idTravail = $Application->postOrCookie('idTravail');
$coursGrp = $Application->postOrCookie('coursGrp');

// si un cours a été sélectionné, retrouver les travaux pour ce cours (sauf les 'hidden' et les 'archive')
if ($coursGrp != Null) {
    $listeTravauxCours = $Files->getTravaux4Cours($coursGrp, array('readonly', 'readwrite', 'termine'));
    $listeArchives = $Files->getTravaux4Cours($coursGrp, array('archive'));
    }
    else {
        $listeTravauxCours = Null;
        $listeArchives = Null;
    }

// si le travail actuellement pointé par $idTravail figure dans ceux du cours,
// on cherche les informations détaillées pour l'affichage
if (in_array($idTravail, array_keys($listeTravauxCours))) {
    $detailsTravail = $Files->getDetailsTravail($idTravail, $matricule);
    $listeCotes = $Files->getCotesTravail ($idTravail, $matricule);
    $totalTravail = $Files->totalisation($listeCotes);
    }
    else {
        $detailsTravail = Null;
        $evaluationTravail = Null;
        $listeCotes = Null;
        $totalTravail = Null;
    }

$smarty->assign('listeCoursAvecTravaux', $listeCoursAvecTravaux);
$smarty->assign('listeTravauxCours', $listeTravauxCours);
$smarty->assign('listeArchives', $listeArchives);
$smarty->assign('idTravail', $idTravail);
$smarty->assign('coursGrp', $coursGrp);
$smarty->assign('detailsTravail', $detailsTravail);
$smarty->assign('totalTravail', $totalTravail);

$smarty->assign('listeCotes', $listeCotes);

$smarty->assign('corpsPage', 'casiers/casiers');
