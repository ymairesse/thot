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
$fileId = isset($_REQUEST['fileId']) ? $_REQUEST['fileId'] : null;
// éventuellement le idTravail
$idTravail = isset($_REQUEST['idTravail']) ? $_REQUEST['idTravail'] : null;
// éventuellement, le nom du fichier et son path depuis le répertoire partagé
$fileName = isset($_REQUEST['fileName']) ? $_REQUEST['fileName'] : null;

$fileNotFound = 'Document non identifié';
$noAccess = 'Vous n\'avez pas accès à ce document';

// vérifier dans la table des shares si l'utilisateur courant a accès au fichier
$matricule = $User->getMatricule();
$classe = $User->getClasse();
$niveau = substr($classe, 0, 1);
$listeCoursEleve = $User->listeCoursEleve();
$listeCoursString = "'".implode("','", $listeCoursEleve)."'";

$ds = DIRECTORY_SEPARATOR;

switch ($type) {
    case 'pfN':  // par fileName, pour les documents partagés par répertoires
        // il nous faut le fileName et le fileId
        if (($fileName == null) || ($fileId == null)) {
            die($fileNotFound);
        }
        $listeDocs = $Files->listeDocsEleve($matricule, $classe, $niveau, $listeCoursString);
        // si le répertoire $fileId est dans les documents partagés avec cet élève
        if (in_array($fileId, $listeDocs)) {
            $fileData = $Files->getFileData($fileId);
            $download_path = INSTALL_ZEUS.$ds.'upload'.$ds.$fileData['acronyme'].$fileData['path'];
        } else {
            die($noAccess);
        }
        break;
    case 'tr':  // récupération d'un travail personnel
        // il nous faut un idTravail et un fileName
        if (($idTravail == null) || ($fileName == null)) {
            die($fileNotFound);
        }
        $travailData = $Files->getDetailsTravail($idTravail, $matricule);
        $acronyme = $travailData['acronyme'];
        $download_path = INSTALL_ZEUS.$ds.'upload'.$ds.$acronyme.$ds.'#thot'.$ds.$idTravail.$ds.$matricule.$ds;
        break;

    case 'pId':   // lecture d'un fichier partagé par fileId
        if ($fileId == null) {
            die($fileNotFound);
        }
        $listeDocs = $Files->listeDocsEleve($matricule, $classe, $niveau, $listeCoursString);
        // si le fichier figure parmi les documents partagés avec cet élève
        if (in_array($fileId, $listeDocs)) {
            // récupérer les données du fichier
            $fileData = $Files->getFileData($fileId);
            $fileName = $fileData['fileName'];
            $download_path = INSTALL_ZEUS.$ds.'upload'.$ds.$fileData['acronyme'].$fileData['path'].$ds;

        } else {
            die($noAccess);
        }
        break;
    default:
        die('unknown type');
        break;
}

if (file_exists($download_path.$fileName)) {
    $args = array(
            'download_path' => $download_path,
            'file' => $fileName,
            'extension_check' => true,
            'referrer_check' => false,
            'referrer' => null,
            );
} else {
    die('Fichier inexistant');
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
