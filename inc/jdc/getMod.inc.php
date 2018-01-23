<?php

require_once '../../config.inc.php';

require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();
session_start();

if (!(isset($_SESSION[APPLICATION]))) {
    echo "<script type='text/javascript'>document.location.replace('".BASEDIR."');</script>";
    exit;
}

require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
$User = unserialize($_SESSION[APPLICATION]);

$matricule = $User->getMatricule();

$id = isset($_POST['id']) ? $_POST['id'] : Null;

if ($id != Null) {
    require_once INSTALL_DIR."/inc/classes/classJdc.inc.php";
    $Jdc = new Jdc();

    if ($id != $Jdc->verifIdRedacteur($id, $matricule))
        die('Cette note au JDC ne vous appartient pas');

    $categories = $Jdc->categoriesTravaux();
    $listePeriodes = $Jdc->lirePeriodesCours();
    $classe = $User->getClasse();
    $listeCours = $User->listeDetailCoursEleve();
    $travail = $Jdc->getTravail($id);

    if ($travail['redacteur'] == $matricule) {
        require_once INSTALL_DIR.'/inc/classes/classEcole.inc.php';
        $Ecole = new Ecole();
        switch ($travail['type']) {
            case 'classe':
                $classe = $travail['destinataire'];
                $titus = $Ecole->titusDeGroupe($classe);
                $travail['nom'] = $titus;
                break;
            case 'cours':
                $coursGrp = $travail['destinataire'];
                $titus = $Ecole->getProfs4CoursGrp($coursGrp);
                $travail['nom'] = $titus;
                break;
            }
        }

    require_once INSTALL_DIR.'/smarty/Smarty.class.php';
    $smarty = new Smarty();
    $smarty->template_dir = '../../templates';
    $smarty->compile_dir = '../../templates_c';

    $smarty->assign('travail', $travail);
    $smarty->assign('categories', $categories);
    $smarty->assign('listeCours', $listeCours);
    $smarty->assign('classe', $classe);
    $smarty->assign('listePeriodes', $listePeriodes);

    $smarty->assign('startDate', $travail['startDate']);
    $smarty->assign('heure', $heure);

    $smarty->display('jdc/modalEdit.tpl');
}
