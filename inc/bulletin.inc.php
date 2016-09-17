<?php

$matricule = $User->getMatricule();
$anneeEtude = $User->getAnnee();
$classe = $User->getClasse();
$nomEleve = $User->getNomEleve();
require_once INSTALL_DIR.'/inc/classes/classBulletin.inc.php';
$Bulletin = new Bulletin();

// déterminer le dernier bulletin auquel cet élève a accès
$bulletinAccessible = $Bulletin->accesBulletin($matricule);
if ($bulletinAccessible != 0) {
    $dernier = $bulletinAccessible;
} else {
    $dernier = 0;
}
// quel est le bulletin demandé? Si rien demandé, on prend le dernier accessible par cet élève
$noBulletin = isset($_POST['noBulletin']) ? $_POST['noBulletin'] : $dernier;

// si le bulletin existe (entre 1 et dernier bulletin) et qu'un élève a été choisi
if (($noBulletin <= $dernier) && ($noBulletin >= 0) && ($matricule != '')) {
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
        $ficheEduc = $Bulletin->listeFichesEduc($matricule, $noBulletin);
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
        $smarty->assign('ficheEduc', $ficheEduc);
        $smarty->assign('remTitu', $remarqueTitulaire);
        $smarty->assign('mention', $mentions);
        $smarty->assign('noticeDirection', $noticeDirection);
        $smarty->assign('corpsPage', 'showBulletin');
    }
} else {
    // POUR PARER À UNE TENTATIVE D'ACCES À UN BULLETIN NON PUBLIÉ ;O)
    $smarty->assign('noBulletin', $dernier);
    $smarty->assign('corpsPage', 'default');
}
$smarty->assign('matricule', $matricule);
$smarty->assign('DERNIERBULLETIN', $dernier);
$smarty->assign('listeBulletins', range(0, $dernier));
$smarty->assign('selecteur', 'selectBulletin');
