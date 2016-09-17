<?php

require_once 'config.inc.php';

require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();

session_start();

// définition de la class USER utilisée en variable de SESSION
require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
$User = isset($_SESSION[APPLICATION]) ? unserialize($_SESSION[APPLICATION]) : null;

// si pas d'utilisateur authentifié en SESSION et répertorié dans la BD, on renvoie à l'accueil
if ($User == null) {
    header('Location: accueil.php');
}
$matricule = $User->getMatricule();

require_once INSTALL_DIR.'/inc/classes/Files.class.php';
$Files = new Files();

// téléchargement sur base du fileId ou du nom du fichier et du path?
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : null;
// éventuellement, le $fileId
$fileId = isset($_REQUEST['f']) ? $_REQUEST['f'] : null;
// éventuellement, le nom du fichier et son path depuis le répertoire partagé
$file = isset($_REQUEST['file']) ? $_REQUEST['file'] : null;

$fileNotFound = 'Document non identifié';
if ($fileId == null) {
    die($fileNotFound);
}

// vérifier dans la table des shares si l'utilisateur courant a accès au fichier
$matricule = $User->getMatricule();
$classe = $User->getClasse();
$niveau = substr($classe, 0, 1);
$listeCoursEleve = $User->listeCoursEleve();
$listeCoursString = "'".implode("','", $listeCoursEleve)."'";

$listeDocs = $Files->listeDocsEleve($matricule, $classe, $niveau, $listeCoursString);

if (in_array($fileId, $listeDocs)) {
    // récupérer les données du fichier
    $fileData = $Files->getFileData($fileId);
} else {
    die('Vous n\'avez pas accès à ce document.');
}

$ds = DIRECTORY_SEPARATOR;

if ($type == 'pfN') {
    $download_path = INSTALL_ZEUS.$ds.'upload'.$ds.$fileData['acronyme'].$fileData['path'];
    if (file_exists($download_path.$file)) {
        $args = array(
                'download_path' => $download_path,
                'file' => $file,
                'extension_check' => true,
                'referrer_check' => false,
                'referrer' => null,
                );
    }
    else die('Fichier inexistant');
} else {
    $download_path = INSTALL_ZEUS.$ds.'upload'.$ds.$fileData['acronyme'].$fileData['path'].$ds;
    $args = array(
            'download_path' => $download_path,
            'file' => $fileData['fileName'],
            'extension_check' => true,
            'referrer_check' => false,
            'referrer' => null,
            );
}

require_once INSTALL_DIR.'/inc/classes/class.chip_download.php';
$download = new chip_download($args);

/*
|-----------------
| Pre Download Hook
|------------------
*/

$download_hook = $download->get_download_hook();

if ($download_hook['download'] != 1) {
    echo "Ce type de fichier n'est pas autorisé";
}
// $download->chip_print($download_hook);
// exit;

/*
|-----------------
| Download
|------------------
*/

if ($download_hook['download'] == true) {

    /* You can write your logic before proceeding to download */

    /* Let's download file */
    $download->get_download();
}
