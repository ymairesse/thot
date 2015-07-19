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
	* liste des derniers utilisateurs connectés
	* @param $limite
	* @return array
	*/
	public function derniersConnectes($limite) {
	   $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
	   $sql = "SELECT distinct user, id,  heure, date, ip, host ";
	   $sql .= "FROM ".PFX."parentLogins ";
	   $sql .= "ORDER BY id desc limit 0,$limite ";
	   $resultat = $connexion->query($sql);
	   if ($resultat) {
		   $resultat->setFetchMode(PDO::FETCH_ASSOC);
		   $tableau = $resultat->fetchall();
		   }
		   else $tableau = Null;
	   self::DeconnexionPDO($connexion);
	   return $tableau;
	   }

	/**
	 * renvoie "true" si l'adresse IP est déjà connue dans la table des logins pour cet utilisateur
	 * @param $ip	: adresse IP
	 * @param $user	: nom de l'utilisateur
	 */
	 public function checkIP ($ip, $user){
		$connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "SELECT COUNT(*) AS nb ";
		$sql .= "FROM ".PFX."logins ";
		$sql .= "WHERE ip='$ip' AND UPPER(user)='$user' ";
		$sql .= "ORDER BY date DESC, heure DESC";
		$resultat = $connexion->query($sql);
		$nb = 0;
		if ($resultat) {
			$ligne = $resultat->fetch();
			$nb = $ligne['nb'];
			}
		Application::deconnexionPDO($connexion);
		return $nb;
		}

	/**
	 * retourne la liste des classes qui figurent dans la base de données (tables Users)
	 * @param void()
	 * @return array()
	 */
	public function listeClasses(){
		$connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "SELECT DISTINCT classe FROM ".PFX."users ";
		$sql .= "ORDER BY classe ";
		$resultat = $connexion->query($sql);
		$liste = array();
		if ($resultat) {
			$resultat->setFetchMode(PDO::FETCH_ASSOC);
			while ($ligne = $resultat->fetch()) {
				$classe = $ligne['classe'];
				$liste[$classe]=$classe;
			}
		self::DeconnexionPDO ($connexion);
		return $liste;
		}
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
	 * retourne la liste des annonces destinées à l'élève dont on donne le matricule et la classe
	 * @param $matricule
	 * @param $classe
	 * @return array
	 */
	public function listeAnnonces($matricule,$classe) {
		$ajd = self::dateMysql(self::dateNow());
		$niveau = substr($classe,0,1);
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "SELECT proprietaire, destinataire, objet, texte, dateDebut, dateFin, urgence, mail, lu, accuseReception ";
		$sql .= "FROM ".PFX."thotNotifications ";
		$sql .= "WHERE destinataire IN ('$matricule', '$classe', '$niveau', 'ecole') ";
		$sql .= "AND (dateFin > '$ajd' AND dateDebut <= '$ajd') ";
		$sql .= "ORDER BY urgence DESC, dateDebut ";

		$resultat = $connexion->query($sql);
		$listeAnnonces = array();
		if ($resultat) {
			$resultat->setFetchMode(PDO::FETCH_ASSOC);			
			while ($ligne = $resultat->fetch()) {
				$destinataire = $ligne['destinataire'];
				$ligne['dateDebut'] = self::datePHP($ligne['dateDebut']);
				$ligne['dateFin'] = self::datePHP($ligne['dateFin']);
				$listeAnnonces[$destinataire][]=$ligne;
				}
		}
		Application::DeconnexionPDO($connexion);
		return $listeAnnonces;
	}


}
?>
