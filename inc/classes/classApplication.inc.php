<?php

class Application {

	function __construct() {
		self::lireConstantes();
		// sorties PHP en français
		setlocale(LC_ALL, "fr_FR.utf8");
    }

	/**
	 * lecture de toutes les constantes du fichier config.ini
	 * @param void()
	 * @return void() définit les constantes globales pour toutes les applis
	 */
	public static function lireConstantes() {
		// lecture des paramètres généraux dans le fichier .ini, y compris la constante "PFX"
		$constantes = parse_ini_file(INSTALL_DIR."/config.ini");
		foreach ($constantes as $key=>$value) {
			define("$key", $value);
			}
		}

	/**
	 * suppression de tous les échappements automatiques dans le tableau passé en argument
	 * @param $tableau
	 * @return array
	 */
	private function Normaliser ($tableau) {
		foreach ($tableau as $clef => $valeur) {
			if (!is_array($valeur))
				$tableau [$clef] = stripslashes($valeur);
				else
				// appel récursif
				$tableau [$clef] = self::Normaliser($valeur);
			}
	return $tableau;
	}


	### --------------------------------------------------------------------###
	public function Normalisation() {
		// si magic_quotes est "ON",
		if (get_magic_quotes_gpc()) {
			$_POST = self::Normaliser($_POST);    // normaliser les $_POST
			$_GET = self::Normaliser($_GET);        // normaliser les $_GET
			$_REQUEST = self::Normaliser($_REQUEST);    // normaliser les $_REQUEST
			$_COOKIE = self::Normaliser($_COOKIE);    // normaliser les $_COOKIE
			}
		}

	/**
	 * afficher proprement le contenu d'une variable précisée
	 * le programme est éventuellement interrompu si demandé
	 * @param : $data n'importe quel tableau ou variable
	 * @param boolean  $die : si l'on souhaite interrompre le programme avec le dump
	 * @return void() : dump prêt à l'affichage
	 * */
	public static function afficher($data, $die=false) {
		if (count($data) == 0)
			echo "Tableau vide";
			else {
				echo "<pre>";
				print_r ($data);
				echo "</pre>";
				echo "<hr />";
			}
	if ($die) die();
	}

	/**
	 * renvoie le temps écoulé depuis le déclenchement du chrono
	 * @param
	 * @return string
	 */
	public static function chrono () {
		$temps = explode(' ', microtime());
		return $temps[0]+$temps[1];
		}

	/**
	 * Connexion à la base de données précisée
	 * @param PARAM_HOST : serveur hôte
	 * @param PARAM_BD : nom de la base de données
	 * @param PARAM_USER : nom d'utilisateur
	 * @param PARAM_PWD : mot de passe
	 * @return connexion à la BD
	 */
	public static function connectPDO ($host, $bd, $user, $mdp) {
		try {
			// indiquer que les requêtes sont transmises en UTF8
			// INDISPENSABLE POUR EVITER LES PROBLEMES DE CARACTERES ACCENTUES
			$connexion = new PDO('mysql:host='.$host.';dbname='.$bd, $user, $mdp,
								array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			}
		catch(Exception $e)	{
			$date = date("d/m/Y H:i:s");
			echo "<style type='text/css'>";
			echo ".erreurBD {width: 500px; margin-left: auto; margin-right: auto; border: 1px solid red; padding: 1em;}";
			echo ".erreurBD .erreur {color: green; font-weight: bold}";
			echo "</style>";

			echo ("<div class='erreurBD'>");
			echo ("<h3>A&iuml;e, a&iuml;e, a&iuml;e... Caramba...</h3>");
			echo ("<p>Une erreur est survenue lors de l'ouverture de la base de donn&eacute;es.<br>");
			echo ("Si vous &ecirc;tes l'administrateur et que vous tentez d'installer le logiciel, veuillez v&eacute;rifier le fichier config.inc.php </p>");
			echo ("<p>Si le probl&egrave;me se produit durant l'utilisation r&eacute;guli&egrave;re du programme, essayez de rafra&icirc;chir la page (<span style='color: red;'>touche F5</span>)<br>");
			echo ("Dans ce cas, <strong>vous n'&ecirc;tes pour rien dans l'apparition du souci</strong>: le serveur de base de donn&eacute;es est sans doute trop sollicit&eacute;...</p>");
			echo ("<p>Veuillez rapporter le message d'erreur ci-dessous &agrave; l'administrateur du syst&egrave;me.</p>");
			echo ("<p class='erreur'>Le $date, le serveur dit: ".$e->getMessage()."</p>");
			echo ("</div>");
			die();
		}
		return $connexion;
	}

	/**
	 * Déconnecte la base de données
	 * @param $connexion
	 * @return void()
	 */
	public static function DeconnexionPDO ($connexion) {
		$connexion = Null;
		}

	/**
	 * retourne le nom du répertoire actuel
	 * @param void()
	 * @return string
	 */
	public static function repertoireActuel () {
		$dir = array_reverse(explode("/",getcwd()));
		return $dir[0];
	}


	/**
	* convertir les dates au format usuel jj/mm/AAAA en YY-mm-dd pour MySQL
	* @param string $date date au format usuel
	* @return string date au format MySQL
	*/
	public static function dateMysql ($date) {
		$dateArray = explode("/",$date);
		$sqlArray=array_reverse($dateArray);
		$date = implode("-",$sqlArray);
		return $date;
		}

	/**
	* convertir les date au format MySQL vers le format usuel
	* @param string $date date au format MySQL
	* @return string date au format usuel français
	*/
	public static function datePHP ($dateMysql) {
		$dateArray = explode("-", $dateMysql);
		$phpArray = array_reverse($dateArray);
		$date = implode("/", $phpArray);
		return $date;
		}

	/**
	 * convertir les heures au format MySQL vers le format ordinaire à 24h
	 * @param string $heure l'heure à convertir
	 * @return string l'heure au format usuel
	 */
	public static function heureMySQL ($heure) {
		$heureArray = explode(":",$heure);
		$sqlArray = array_reverse($heureArray);
		$heure = implode(":",$sqlArray);
		return $heure;
		}

	/**
	 * converir les heures au format PHP vers le format MySQL
	 * @param string $heure
	 * @return string
	 */
	public static function heurePHP ($heure) {
		$heureArray = explode(":",$heure);
		$sqlArray = array_reverse($heureArray);
		$heure = implode(":",$sqlArray);
		return $heure;
		}

	/**
	 * retourne le jour de la semaine correspondant à une date au format MySQL
	 * @param string $dataMySQL
	 * @return string
	 */
	public static function jourSemaineMySQL ($dateMySQL) {
		$timeStamp = strtotime($dateMySQL);
		return strftime("%A", $timeStamp);
	}

	/**
	* Fonction de conversion de date du format français (JJ/MM/AAAA) en Timestamp.
	* @param string $date Date au format français (JJ/MM/AAAA)
	* @return integer Timestamp en seconde
	* http://www.julien-breux.com/2009/02/17/fonction-php-date-francaise-vers-timestamp/
	*/
	public static function dateFR2Time($date)	{
		  list($day, $month, $year) = explode('/', $date);
		  $timestamp = mktime(0, 0, 0, $month, $day, $year);
		  return $timestamp;
		}

	/**
	 * date d'aujourd'hui
	 * @param void()
	 * @return string
	 */
	public static function dateNow(){
		return date('d/m/Y');
		}

	/**
	* filtrage des actions par utilisateur
	* @param $action : action envisagée
	* @param $userType : type d'utilisateur
	* @return string : l'action permise ou Null
	*/
	public function filtreAction($action,$userType){
		switch ($userType) {
			case 'eleves':
				$permis = array('bulletin','anniversaires','jdc','parents','logoff','annonces');
				if (!(in_array($action,$permis)))
					$action = Null;
				break;
			case 'parents':
				$permis = array('bulletin','jdc','profil','logoff','annonces');
				if (!(in_array($action,$permis)))
					$action = Null;
				break;
			case 'admin':
				break;
			default:
				// wtf
				break;
		}
		if ($userType == 'eleves') {}

		return $action;
		}


	/**
	 * retourne la liste des élèves pour une classe donnée
	 * @param string $classe
	 * @return array()
	 */
	public function listeEleves($classe){
		$connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "SELECT userName, nom, prenom, statut, mail ";
		$sql .= "FROM ".PFX."users ";
		$sql .= "WHERE classe = '$classe' ";
		$sql .= "ORDER  BY nom, prenom ";
		$resultat = $connexion->query($sql);;
		$liste = array();
		if ($resultat) {
			$resultat->setFetchMode(PDO::FETCH_ASSOC);
			while ($ligne = $resultat->fetch()){
				$userName = $ligne['userName'];
				$liste[$userName] = $ligne;
				}
			}
		self::DeconnexionPDO($connexion);
		return $liste;
		}

		/**
	     * liste structurée des profs liés à une liste de coursGrp (liste indexée par coursGrp)
	     * @param string | array : $listeCoursGrp
	     * @return array
	     */
	    public function listeProfsListeCoursGrp($listeCoursGrp, $type='string') {
	        if (is_array($listeCoursGrp))
	            $listeCoursGrpString = "'" . implode("','", array_keys($listeCoursGrp)) . "'";
	            else $listeCoursGrpString = "'".$listeCoursGrp."'";
			$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
	        $sql = "SELECT coursGrp, nom, prenom, sexe, ".PFX."profsCours.acronyme ";
	        $sql .= "FROM ".PFX."profsCours ";
	        $sql .= "JOIN ".PFX."profs ON (".PFX."profsCours.acronyme = ".PFX."profs.acronyme) ";
	        $sql .= "WHERE coursGrp IN ($listeCoursGrpString) ";
			$sql .= "ORDER BY nom";

	        $resultat = $connexion->query($sql);
	        $liste = array();
	        if ($resultat) {
				$resultat->setFetchMode(PDO::FETCH_ASSOC);
				while ($ligne = $resultat->fetch()) {
					$coursGrp = $ligne['coursGrp'];
					$acronyme = $ligne['acronyme'];
					$sexe = $ligne['sexe'];
					$ved = ($sexe=='M')?'M. ':'Mme';
					if ($type == 'string') {
						if (isset($liste[$coursGrp]))
							$liste[$coursGrp] .= ', '.$ved.' '.$ligne['prenom'].' '.$ligne['nom'];
							else $liste[$coursGrp] = $ved.' '.$ligne['prenom'].' '.$ligne['nom'];
						}
						else $liste[$coursGrp][$acronyme] = $ligne;
					// on supprime le cours dont le prof a été trouvé
					unset($listeCoursGrp[$coursGrp]);
					}
				}
	        Application::DeconnexionPDO($connexion);
			// on rajoute tous les cours dont les affectations de profs sont inconnues
			if ($listeCoursGrp != Null)
				foreach ($listeCoursGrp as $coursGrp=>$wtf) {
					$liste[$coursGrp] = PROFNONDESIGNE;
					}
	        return $liste;
	    }


	/**
	 * retourne la liste des utilisateurs uniques connectés depuis une date donnée
	 * @param $date
	 * @return array
	 */
	public function listeConnectesDate ($date) {
		$date = $this->dateMysql($date);
		$connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "SELECT DISTINCT user, classe, nom, prenom ";
		$sql .= "FROM ".PFX."parentLogins AS lo ";
		$sql .= "JOIN ".PFX."users AS users ON users.userName = lo.user ";
		$sql .= "WHERE date >= '$date' ";
		$sql .= "ORDER by classe, nom, prenom ";

		$resultat = $connexion->query($sql);
		$liste = array();
		if ($resultat) {
			$resultat->setFetchMode(PDO::FETCH_ASSOC);
			while ($ligne = $resultat->fetch()){
				$userName = $ligne['user'];
				$liste[$userName]=$ligne;
			}
		self::DeconnexionPDO($connexion);
		return $liste;
		}
	}

	/**
     * liste structurée des profs liés à une liste de coursGrp (liste indexée par coursGrp)
     * @param string | array : $listeCoursGrp
     * @return array
     */

	/**
	 * retourne la liste structurée par type de destinataire des annonces destinées à l'élève dont on donne le matricule et la classe
	 * @param $matricule
	 * @param $classe
	 * @return array
	 */
	public function listeAnnonces($matricule,$classe) {
		$ajd = self::dateMysql(self::dateNow());
		$niveau = substr($classe,0,1);
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "SELECT dtn.id, proprietaire, destinataire, objet, texte, dateDebut, dateFin, urgence, dtn.mail, accuse, dp.nom, dp.sexe ";
		$sql .= "FROM ".PFX."thotNotifications AS dtn ";
		$sql .= "LEFT JOIN ".PFX."profs AS dp ON dp.acronyme = dtn.proprietaire ";
		$sql .= "WHERE destinataire IN ('$matricule', '$classe', '$niveau', 'ecole') ";
		$sql .= "AND (dateFin > '$ajd' AND dateDebut <= '$ajd') ";
		$sql .= "ORDER BY urgence DESC, dateDebut ";

		$resultat = $connexion->query($sql);
		$listeAnnonces = array();
		if ($resultat) {
			$resultat->setFetchMode(PDO::FETCH_ASSOC);
			while ($ligne = $resultat->fetch()) {
				$destinataire = $ligne['destinataire'];
				$id = $ligne['id'];
				$ligne['dateDebut'] = self::datePHP($ligne['dateDebut']);
				$ligne['dateFin'] = self::datePHP($ligne['dateFin']);
				if ($ligne['nom'] != '') {
					switch ($ligne['sexe']) {
						case 'M':
							$ligne['proprietaire'] = 'M. '.$ligne['nom'];
							break;
						case 'F':
							$ligne['proprietaire'] = 'Mme '.$ligne['nom'];
							break;
					}
				}
				$listeAnnonces[$destinataire][$id]=$ligne;
				}
			}
		Application::DeconnexionPDO($connexion);
		return $listeAnnonces;
		}

	/**
	* Liste des accusés de lecture demandés à un élève dont on fournit le matricule
	* @param $matricule : le matricule de l'élève
	* @return array : la liste des accusés triés par id
	*/
	public function listeAccusesEleve($matricule){
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "SELECT id, dateHeure ";
		$sql .= "FROM ".PFX."thotAccuse ";
		$sql .= "WHERE matricule = '$matricule' ";
		$resultat = $connexion->query($sql);
		$liste = array();
		if ($resultat) {
			$resultat->setFetchMode(PDO::FETCH_ASSOC);
			while ($ligne = $resultat->fetch()) {
				$id = $ligne['id'];
				$ligne['dateHeure'] = isset($ligne['dateHeure'])?$this->dateHeure($ligne['dateHeure']):Null;
				$liste[$id]=$ligne;
				}
			}
		Application::DeconnexionPDO($connexion);
		return $liste;
		}

	/**
	* Liste des annonces et des demandes d'accusés de lecture correspondant (si existant)
	* @param $listeAnnonces : liste des annonces triées par id de l'annonce
	* @param $listeAccuses : liste des demandes d'accusés de lecture triées par id
	* @return array : combinaison des deux arrays de données
	*/
	public function listeAnnoncesAccuses($listeAnnonces, $listeAccuses){
		foreach ($listeAnnonces as $type=>$listeType){
			foreach ($listeType as $id=>$dataAnnonce){
				$dateHeure = isset($listeAccuses[$id])?$listeAccuses[$id]['dateHeure']:Null;
				$listeAnnonces[$type][$id]['dateHeure'] = $dateHeure;
				}
			}
		return $listeAnnonces;
		}

	/**
	* retourne une liste simple des accusés de lecture pour une liste d'annonces; la liste est classée par groupe d'annonces
	* @param $listeAnnonces : la liste complète des annonces (voir la fonction listeAnnonces)
	* @param $matricule : matricule de l'élève concerné
	* @param $classe: classe de l'élève
	* @return array : pour chaque type d'annonces, le nombre d'accusés de lecture demandés
	*/
	public function shortListeAccuses($listeAnnonces, $matricule, $classe) {
		$niveau = substr($classe,0,1);
		$listeAccuses = array('eleve' => 0, 'classe' => 0, 'niveau' => 0, 'ecole' => 0);
		if (isset($listeAnnonces[$matricule])) {
			$listeEleve = $listeAnnonces[$matricule];
			foreach ($listeEleve as $id=>$uneAnnonce) {
				if (($uneAnnonce['accuse'] == 1) && ($uneAnnonce['dateHeure'] == Null))
					$listeAccuses['eleve']++;
				}
			}
		if (isset($listeAnnonces[$classe])) {
			$listeClasse = $listeAnnonces[$classe];
			foreach ($listeClasse as $uneAnnonce) {
				if (($uneAnnonce['accuse'] == 1) && ($uneAnnonce['dateHeure'] == Null))
					$listeAccuses['classe']++;
				}
			}
		if (isset($listeAnnonces[$niveau])) {
			$listeNiveau = $listeAnnonces[$niveau];
			foreach ($listeNiveau as $uneAnnonce) {
				if (($uneAnnonce['accuse'] == 1) && ($uneAnnonce['dateHeure'] == Null))
					$listeAccuses['niveau']++;
				}
			}
		if (isset($listeAnnonces['ecole'])) {
			$listeEcole = $listeAnnonces['ecole'];
			foreach ($listeEcole as $uneAnnonce) {
				if (($uneAnnonce['accuse'] == 1) && ($uneAnnonce['dateHeure'] == Null))
					$listeAccuses['ecole']++;
				}
			}
		return $listeAccuses;
		}

	/**
	* renvoie le nombre d'accusés de lecture pour une liste de demande d'accusés fournie
	* @param $listeAccuses : liste des accusés de lecture par type
	* @return integer:  nombre total d'accusés de lecture demandés
	*/
	public function nbAccuses($listeAccuses){
		$nb = 0;
		foreach ($listeAccuses as $type=>$nombre){
			$nb+= $nombre;
			}
		return $nb;
		}

	/**
	* marque une notification lue pour un élève donné
	* @param $matricule: identité de l'élève
	* @param $id : id de la notification
	* @return string: jour et heure de lecture
	*/
	public function marqueAccuse($matricule,$id) {
		$dateHeure = '';
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "UPDATE ".PFX."thotAccuse ";
		$sql .= "SET dateHeure = NOW() ";
		$sql .= "WHERE id='$id' AND matricule='$matricule' ";
		$resultat = $connexion->exec($sql);
		if ($resultat) {
			$sql = "SELECT dateHeure ";
			$sql .= "FROM ".PFX."thotAccuse ";
			$sql .= "WHERE id='$id' AND matricule='$matricule' ";

			$resultat = $connexion->query($sql);
			if ($resultat) {
				$resultat->setFetchMode(PDO::FETCH_ASSOC);
				$ligne = $resultat->fetch();
				$dateHeure = $this->dateHeure($ligne['dateHeure']);
				}
			}
		Application::DeconnexionPDO($connexion);
		return $dateHeure;
		}

	/**
	* conversion des dateHeures comprenant la date et l'heure au format "classique" pour les dates et
	* en ajustant aux minutes pour les heures
	* @param $dateHeure : combinaison de date et d'heure au format MySQL Ex: "2015-07-30 11:33:59"
	* @return  string : la même chose au format "30/07/2015 11:33"
	*/
	private function dateHeure($dateHeure){
		$dateHeure = explode(' ',$dateHeure);
		$date = $dateHeure[0];
		$date = self::datePHP($date);
		$dateHeure = $date.' à '.substr($dateHeure[1],0,5);
		return $dateHeure;
	}

	/**
	* liste des parents déclarés pour un utilisateur "élève", d'après son matricule
	* @param $matricule
	* @return array
	*/
	public function listeParents($matricule){
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "SELECT nom, prenom, formule, userName, mail, lien, md5pwd ";
		$sql .= "FROM ".PFX."thotParents ";
		$sql .= "WHERE matricule = '$matricule' ";
		$sql .= "ORDER BY nom, prenom, userName ";
		$resultat = $connexion->query($sql);
		$liste = array();
		if ($resultat) {
			$resultat->setFetchMode(PDO::FETCH_ASSOC);
			while ($ligne = $resultat->fetch()) {
				$userName = $ligne['userName'];
				$liste[$userName] = $ligne;
				}
			}
		Application::DeconnexionPDO($connexion);
		return $liste;
	}

	/**
	* Enregistre les informations relatives à un parent et provenant d'un formulaire
	* @param $post : array le contenu du formulaire
	* @return interger nombre d'enregistrement réussis
	*/
	public function saveParent($post){
		$ok = true;
		$formule = $post['formule']; if ($formule == '') $ok=false;
		$nom = $post['nom']; if ($nom == '') $ok=false;
		$prenom = $post['prenom']; if ($prenom =='') $ok=false;
		$userName = $post['userName']; if ($userName == '') $ok=false;
		$mail = $post['mail']; if ($mail == '') $ok=false;
		$matricule = $post['matricule']; if ($matricule == '') $ok=false;
		$lien = $post['lien']; if ($lien == '') $ok=false;
		$passwd = $post['passwd'];
		$passwd2 = $post['passwd2'];
		if (($passwd == '') || ($passwd2 != $passwd))  $ok=false;
		$resultat = 0;
		if ($ok == true) {
			$passwd = md5($passwd);
			$userName = $userName.$matricule;
			$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
			$sql = "INSERT INTO ".PFX."thotParents ";
			$sql .= "SET userName='$userName', matricule='$matricule', formule='$formule', nom='$nom', prenom='$prenom', ";
			$sql .= "mail='$mail', lien='$lien', md5pwd='$passwd' ";
			$sql .= "ON DUPLICATE KEY UPDATE ";
			$sql .= "formule='$formule', nom='$nom', prenom='$prenom', ";
			$sql .= "mail='$mail', lien='$lien', md5pwd='$passwd' ";
			$resultat = $connexion->exec($sql);
			if ($resultat) $resultat = 1;  // pour éviter 2 modifications si DUPLICATE KEY
			Application::DeconnexionPDO($connexion);
			}
		return $resultat;
		}

	/**
	* Enregistrement d'un profil modifié dans le formulaire ad-hoc
	* @param $post : le contenu du formulaire
	* @return boolean
	*/
	public function saveProfilParent($post,$userName){
		$ok = true;
		$formule = $post['formule']; if ($formule == '') $ok=false;
		$nom = $post['nom']; if ($nom == '') $ok=false;
		$prenom = $post['prenom']; if ($prenom =='') $ok=false;
		$mail = $post['mail']; if ($mail == '') $ok=false;
		$lien = $post['lien']; if ($lien == '') $ok=false;
		$passwd = $post['passwd'];
		$sqlPasswd = '';
		if ($passwd != '') {
			$passwd2 = $post['passwd2'];
			if ($passwd == $passwd2) {
				$passwd = md5($passwd);
				$sqlPasswd = ",md5pwd='$passwd' ";
				}
				else $ok=false;
			}

		if ($ok == true) {
			$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
			$sql = "UPDATE ".PFX."thotParents ";
			$sql .= "SET formule='$formule', nom='$nom', prenom='$prenom', mail='$mail', lien='$lien' ";
			$sql .= $sqlPasswd;
			$sql .= "WHERE userName = '$userName' ";
			$resultat = $connexion->exec($sql);
			Application::DeconnexionPDO($connexion);
			}
		return $resultat;
		}

}
?>
