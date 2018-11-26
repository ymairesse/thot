<?php

require_once '../../config.inc.php';

require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();
session_start();

$erreurSession = !(isset($_SESSION[APPLICATION]));
if ($erreurSession == true) {
    echo json_encode(array('ERREUR' => true));
    exit();
    }

require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
$User = unserialize($_SESSION[APPLICATION]);

$matricule = $User->getMatricule();

require_once INSTALL_DIR."/inc/classes/classJdc.inc.php";
$Jdc = new Jdc();

// récupérer le formulaire d'encodage du JDC
$formulaire = isset($_POST['formulaire']) ? $_POST['formulaire'] : null;
$form = array();
parse_str($formulaire, $form);

// est-ce une mise à jour d'un enregistrement existant?
$id = $Jdc->saveJdc($form, $matricule);

$texte = ($id != Null) ? 'Enregistrement OK' : 'Échec de l\'enregistrement';

echo json_encode(array('ERREUR' => $erreurSession, 'id' => $id, 'texte' => $texte));
