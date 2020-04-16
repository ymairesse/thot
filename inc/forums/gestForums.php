<?php

$identite = $User->getIdentite();

$matricule = $identite['matricule'];
$classe = $identite['classe'];
$niveau = substr($classe, 0, 1);

require_once INSTALL_DIR.'/inc/classes/class.thotForum.php';
$Forum = new ThotForum();

require_once INSTALL_DIR.'/inc/classes/classEcole.inc.php';
$Ecole = new Ecole();

$listeCoursGrp = array_keys($Ecole->listeCoursGrpEleve($matricule));
$listeMatieres = ($Ecole->getListeMatieresEleve($listeCoursGrp));

$listeTypes = array(
    'ecole' => 'Tous les élèves',
    'niveau' => 'Ton niveau d\'études',
    'classe' => 'Ta classe',
    'matiere' => 'Une matière',
    'coursGrp' => 'Un cours',
    'groupe' => 'Un groupe',
);
$smarty->assign('listeTypes', $listeTypes);

$listeSujets = $Forum->getListeSujets4eleve($matricule, $classe, $niveau, $listeMatieres, $listeCoursGrp);
$smarty->assign('listeSujets', $listeSujets);

$smarty->assign('corpsPage', 'forums/forums');
