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

require_once INSTALL_DIR."/inc/classes/classJdc.inc.php";
$Jdc = new Jdc();

$id = isset($_POST['id'])?$_POST['id']:Null;

if ($id != Null) {
    if ($id != $Jdc->verifIdRedacteur($id, $matricule))
        die('Cette note au JDC ne vous appartient pas');

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
    $startDate = $travail['startDate'];
    $destinataire = $travail['destinataire'];
    $type = $travail['type'];

    $coursGrp = ($type == 'cours') ? $travail['destinataire'] : Null;
    $classe = ($type == 'classe') ? $travail['destinataire'] : Null;

    require_once(INSTALL_DIR."/smarty/Smarty.class.php");
    $smarty = new Smarty();
    $smarty->template_dir = "../../templates";
    $smarty->compile_dir = "../../templates_c";

    $smarty->assign('travail',$travail);
    $smarty->assign('startDate',$startDate);
    $smarty->assign('destinataire',$destinataire);
    $smarty->assign('type',$type);
    $smarty->assign('coursGrp',$coursGrp);
    $smarty->assign('classe',$classe);
    $smarty->display('jdc/modalDel.tpl');
    }
}
