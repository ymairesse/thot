<?php

require_once '../config.inc.php';
session_start();

require_once INSTALL_DIR.'/inc/classes/classApplication.inc.php';
$Application = new Application();

// définition de la class USER utilisée en variable de SESSION
require_once INSTALL_DIR.'/inc/classes/classUser.inc.php';
$User = isset($_SESSION[APPLICATION]) ? unserialize($_SESSION[APPLICATION]) : null;

$listeCoursEleve = $User->listeCoursEleve();
$listeCoursString = "'".implode("','", $listeCoursEleve)."'";

$identite = $User->getIdentite();
$matricule = $identite['matricule'];
$classe = $identite['groupe'];

$start = $_GET['start'];
$end = $_GET['end'];

$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
$sql = 'SELECT id, destinataire, idCategorie, type, proprietaire, title, url, class, allDay, startDate, endDate ';
$sql .= 'FROM '.PFX.'thotJdc ';
$sql .= "WHERE startDate BETWEEN '$start' AND '$end' ";
$sql .= "AND destinataire in ($listeCoursString) OR destinataire = '$classe' OR destinataire = 'ecole' ";

$resultat = $connexion->query($sql);
$liste = array();
if ($resultat) {
    $resultat->setFetchMode(PDO::FETCH_ASSOC);
    while ($ligne = $resultat->fetch()) {
        $liste[] = array(
            'id' => $ligne['id'],
            'title' => $ligne['title'],
            'url' => $ligne['url'],
            'className' => 'cat_'.$ligne['idCategorie'],
            'start' => $ligne['startDate'],
            'end' => $ligne['endDate'],
            );
    }
}
Application::DeconnexionPDO($connexion);
echo json_encode($liste);
