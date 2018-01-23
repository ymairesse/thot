<?php

require_once '../../config.inc.php';

require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();
session_start();

if (!(isset($_SESSION[APPLICATION]))) {
    echo "<script type='text/javascript'>document.location.replace('".BASEDIR."');</script>";
    exit;
}

// définition de la class USER utilisée en variable de SESSION
require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
$User = unserialize($_SESSION[APPLICATION]);

$matricule = $User->getMatricule();

require_once INSTALL_DIR."/inc/classes/classJdc.inc.php";
$Jdc = new Jdc();

$id = isset($_POST['id']) ? $_POST['id'] : null;
$origine = isset($_POST['origine']) ? $_POST['origine'] : null;

$titus = Null;
$redacteur = Null;
$likes = array();

if ($id != null) {
    $travail = $Jdc->getTravail($id);
    $editable = $Jdc->editable($travail, $matricule, $origine);

    if ($travail['proprietaire'] == '') {
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
        $redacteur = $User->getNom();
        $likes = $Jdc->countLikes($id);
    }
    else {
        $acronyme = $travail['proprietaire'];
        $identite = $Application->identiteProf($acronyme);
        $adresse = ($identite['sexe'] == 'F') ? 'Mme' : 'M.';
        $nom = sprintf('%s %s. %s', $adresse, mb_substr($identite['prenom'], 0, 1, 'UTF-8'), $identite['nom']);
        $titus = $nom;
    }

    require_once INSTALL_DIR.'/smarty/Smarty.class.php';
    $smarty = new Smarty();
    $smarty->template_dir = '../../templates';
    $smarty->compile_dir = '../../templates_c';

    $smarty->assign('origine', $origine);
    $smarty->assign('titus', $titus);
    $smarty->assign('matricule', $matricule);
    $smarty->assign('redacteur', $redacteur);
    $smarty->assign('likes', $likes);
    $smarty->assign('editable', $editable);
    $smarty->assign('travail', $travail);
    $smarty->display('jdc/unTravail.tpl');
}
