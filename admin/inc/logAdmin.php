<?php
session_start();

require_once('config.inc.php');
require_once('inc/fonctions.inc.php');

// définition de la class Application
require_once (INSTALL_DIR."/inc/classes/classApplication.inc.php");
$Application = new Application();

// définition de la class User
require_once (INSTALL_DIR."/inc/classes/classUserAdmin.inc.php");

// extraire l'identifiant et le mot de passe
// l'identifiant est passé en majuscules => casse sans importance
$userName = (isset($_POST['userName']))?$_POST['userName']:Null;
$mdp = (isset($_POST['mdp']))?$_POST['mdp']:Null;

// Les données userName et mdp ont été postées dans le formulaire de la page accueil.php
if (!empty($userName) && !empty($mdp)) {
	// recherche de toutes les informations sur l'utilisateur et les applications activées
	$UserAdmin = new userAdmin($userName);

	// noter le passage de l'utilisateur dans les logs
	$UserAdmin->loggerAdmin($UserAdmin);
	// vérification du mot de passe
	if ($UserAdmin->getAdminPasswd() == md5($mdp)) {
		afficher($UserAdmin->getAdminPasswd());
		afficher(md5($mdp));
		// mettre à jour la session avec les infos de l'utilisateur
		$_SESSION[APPLICATION] = serialize($User);
		afficher($_SESSION);
		header("Location: indexAdmin.php");
		}
		else header("Location: accueil.php?message=faux");
	}
	else
	// le nom d'utilisateur ou le mot de passe n'ont pas été donnés
	header("Location: accueil.php?message=manque");
?>    
