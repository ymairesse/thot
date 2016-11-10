<?php

session_start();
require_once '../config.inc.php';
// définition de la class Application
require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();

$fileId = isset($_POST['fileId']) ? $_POST['fileId'] : null;

require_once INSTALL_DIR.'/inc/classes/Files.class.php';
$Files = new Files();

require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
$User = unserialize($_SESSION['THOT']);

$matricule = $User->getMatricule();
$classe = $User->getClasse();
$niveau = substr($classe, 0, 1);
$listeCoursEleve = $User->listeCoursEleve();
$listeCoursString = "'".implode("','", $listeCoursEleve)."'";

$listeSharedFiles = $Files->getSharedFiles($matricule, $classe, $niveau, $listeCoursString);

if (in_array($fileId, $listeSharedFiles)) {
    $path = $Files->getPathByFileId($fileId);
    $infos = $Files->getFileData($fileId);
    $acronyme = $infos['acronyme'];

    $ds = DIRECTORY_SEPARATOR;
    require_once INSTALL_DIR.'/inc/classes/class.Treeview.php';
    $Treeview = new Treeview(INSTALL_ZEUS.$ds.'upload'.$ds.$acronyme.$path);
    
    require_once(INSTALL_DIR."/smarty/Smarty.class.php");
    $smarty = new Smarty();
    $smarty->template_dir = "../templates";
    $smarty->compile_dir = "../templates_c";

    $smarty->assign('tree', $Treeview->getTree());
    $smarty->assign('fileId', $fileId);
    echo $smarty->fetch('files/treeview.tpl');
}
