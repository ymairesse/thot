<?php

require_once '../../config.inc.php';

require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();
session_start();

require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
$User = unserialize($_SESSION['THOT']);

$matricule = $User->getMatricule();

$id = isset($_POST['id']) ? $_POST['id'] : Null;

if ($id != Null) {
    $Application->marqueLu($matricule, $id);
    return $id;
}
