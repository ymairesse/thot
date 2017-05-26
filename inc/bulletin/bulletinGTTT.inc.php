<?php

// liste de tous les cours suivis par cet élève durant la période $noBulletin (historique pris en compte)
$listeCoursGrp = $Bulletin->listeCoursGrpEleves($matricule, $noBulletin);

if ($listeCoursGrp) {
    $listeCoursGrp = $listeCoursGrp[$matricule];
    $listeProfsCoursGrp = $Application->listeProfsListeCoursGrp($listeCoursGrp);
    $listeSituations = $Bulletin->listeSituationsCours($matricule, array_keys($listeCoursGrp), null, true);
    $sitPrecedentes = $Bulletin->situationsPrecedentes($listeSituations, $noBulletin);
    $sitActuelles = $Bulletin->situationsPeriode($listeSituations, $noBulletin);
    $listeCompetences = $Bulletin->listeCompetencesListeCoursGrp($listeCoursGrp);
    $listeCotes = $Bulletin->listeCotes($matricule, $listeCoursGrp, $listeCompetences, $noBulletin);

    $ponderations = $Bulletin->getPonderations($listeCoursGrp, $noBulletin);
    $cotesPonderees = $Bulletin->listeGlobalPeriodePondere($listeCotes, $ponderations, $noBulletin);

    $tableauAttitudes = $Bulletin->tableauxAttitudes($matricule, $noBulletin);

    $commentairesCotes = $Bulletin->listeCommentairesTousCours($matricule, $noBulletin);
    $mentions = $Bulletin->listeMentions($matricule, $noBulletin);

    $commentairesEducs = $Bulletin->listeCommentairesEduc($matricule, $bulletin);
    $commentairesEducs = isset($commentairesEducs[$matricule][$bulletin]) ? $commentairesEducs[$matricule][$bulletin] : Null;

    $remarqueTitulaire = $Bulletin->remarqueTitu($matricule, $noBulletin);
    if ($remarqueTitulaire != null) {
        $remarqueTitulaire = $remarqueTitulaire[$matricule][$noBulletin];
    } else {
        $remarqueTitulaire = '';
    }
    $tableauAttitudes = $Bulletin->tableauxAttitudes($matricule, $noBulletin);
    $noticeDirection = $Bulletin->noteDirection($anneeEtude, $noBulletin);

    $smarty->assign('noBulletin', $noBulletin);
    $smarty->assign('nomEleve', $nomEleve);
    $smarty->assign('listeCoursGrp', $listeCoursGrp);
    $smarty->assign('listeProfsCoursGrp', $listeProfsCoursGrp);
    $smarty->assign('sitPrecedentes', $sitPrecedentes);
    $smarty->assign('sitActuelles', $sitActuelles);
    $smarty->assign('listeCotes', $listeCotes);
    $smarty->assign('listeCompetences', $listeCompetences);

    $smarty->assign('cotesPonderees', $cotesPonderees);
    $smarty->assign('commentaires', $commentairesCotes);
    $smarty->assign('attitudes', $tableauAttitudes);
    $smarty->assign('commentairesEducs', $commentairesEducs);
    $smarty->assign('remTitu', $remarqueTitulaire);
    $smarty->assign('mention', $mentions);
    $smarty->assign('noticeDirection', $noticeDirection);
    $smarty->assign('corpsPage', 'bulletin/bulletinEcranGTTT');
}
