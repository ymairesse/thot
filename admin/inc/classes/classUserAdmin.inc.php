<?php

class userAdmin {
	private $userName;
	private $identite;			// données personnelles
	private $identiteReseau;  	// données réseau IP,...


	/** 
	 * constructeur de l'objet user
	 */
	function __construct($userName=Null) {
		$this->identiteReseau = $this->identiteReseau();
		if (isset($userName)) {
			$this->userName = $userName;
			$this->setIdentite();
			}
	}
	
	/**
	 * recherche toutes les informations de la table des utilisateurs pour l'utilisateur actif et les reporte dans l'objet User
	 * @param
	 * @return array
	 */
	public function setIdentite (){
		$userName = $this->userName;
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "SELECT acronyme, nom, prenom, mdp, statut ";
		$sql .= "FROM ".PFX."profs ";
		$sql .= "WHERE acronyme = '$userName' LIMIT 1 ";
		$resultat = $connexion->query($sql);
		$resultat->setFetchMode(PDO::FETCH_ASSOC);
		$this->identite = $resultat->fetch();
		Application::DeconnexionPDO($connexion);
		}
	
	/**
	 * renvoie toutes les informations d'identité présentes dans l'objet User
	 * @param void()
	 * @return array
	 */
	public function getIdentite() {
		return $this->identite;
		}

	/** 
	 * renvoie le prénom et le nom de l'utilisateur
	 * @param 
	 * @return string
	 */
	public function getNom() {
		$prenom = $this->identite['prenom'];
		$nom = $this->identite['nom'];
		return $prenom." ".$nom;
		}

	/**
	 * fournit le mot de passe MD5 de l'utilisateur
	 * @param
	 * @return string
	 */
	public function getAdminPasswd() {
		return $this->identite['mdp'];
	}
	
	/**
	 * fournit le nom d'utilisateur de l'utilisateur actif
	 * @param void()
	 * @return string
	 */
	public function getUserName(){
		return $this->userName;
		}

	/**
	 * retourne le nom de l'application; permet de ne pas confondre deux applications
	 * différentes qui utiliseraient la variable de SESSION pour retenir MDP et USERNAME
	 * de la même façon.
	 * @param
	 * @return string
	 */
	public function applicationName() {
		return $this->applicationName;
	}

	/**
	 * vérifie que l'utilisateur dont on fournit le nom d'utilisateur existe dans la table des utilisateurs
	 * @param $userName
	 * @return array : le userName effectivement trouvé dans la BD ou rien si pas trouvé
	 */
	public static function userExists($acronyme) {
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "SELECT acronyme FROM ".PFX."profs ";
		$sql .= "WHERE acronyme = '$acronyme' ";
		$resultat = $connexion->query($sql);
		if ($resultat) {
			$resultat->setFetchMode(PDO::FETCH_ASSOC);
			$ligne = $resultat->fetch();
			}
		Application::DeconnexionPDO($connexion);
		return ($ligne['acronyme']);
		}


	/** 
	 * vérifier que l'utilisateur dont on fournit le userName est signalé comme loggé depuis l'adresse ip dans la BD
	 * @param $userName : string
	 * @param $ip : string
	 */
	public function islogged($acronyme,$ip) {
		$userName = $this->userName();
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);		
		$sql = "SELECT acronyme, ip ";
		$sql .= "FROM ".PFX."thotSessions ";
		$sql .= "WHERE user='$userName' AND ip='$ip' ";
		$resultat = $connexion->query($sql);
		if ($resultat) {
			$verif = $resultat->fetchAll();
			}
		Application::DeconnexionPDO ($connexion);
		return (count($verif) > 0);
		}

	/** 
	 * convertir l'objet $user en tableau
	 * @param void()
	 * @return array
	 */
	private function toArray () {
		return (array) $this;
		}
	
	/**
	 * ajout de l'utilisateur dans le journal des logs
	 * @param $userName	: userName de l'utilisateur
	 * @return integer
	 */
	public function loggerAdmin ($user) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$date = date("Y-m-d");
		$heure = date("H:i");
		$userName = $user->getuserName();
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "INSERT INTO ".PFX."thotLogins ";
		$sql .= "SET user='$userName', date='$date', heure='$heure', ip='$ip', host='$hostname' ";
		$n = $connexion->exec($sql);
		
		// indiquer une session ouverte depuis l'adresse IP correspondante
		$sql = "INSERT INTO ".PFX."thotSessions ";
		$sql .= "SET user='$userName', ip='$ip' ";
		$sql .= "ON DUPLICATE KEY UPDATE ip='$ip' ";

		$n = $connexion->exec($sql);
		Application::DeconnexionPDO ($connexion);
		return $n;
	}
	
	/** 
	 * délogger l'utilisateur indiqué de la base de données (table des sessions actives)
	 * @return integer : nombre d'effacement dans la BD
	 */
	public function delogger() {
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		$userName = $this->userName();
		$sql = "DELETE FROM ".PFX."thotSessions ";
		$sql .= "WHERE user='$userName' ";
		$resultat = $connexion->exec($sql);
		Application::DeconnexionPDO ($connexion);
		return $resultat;
		}	

	/**
	 * renvoie le userName de l'utilisateur courant
	 * @param
	 * @return string
	 */
	public function userName() {
		return $this->userName;
		}
		
	/**
	 * fournit le mot de passe MD5 de l'utilisateur
	 * @param
	 * @return string
	 */
	public function getPasswd() {
		return $this->identite['mdp'];
	}
	

	/**
	 * renvoie le statut global de l'utlilisateur
	 * @param
	 * @return string
	 */
	public function getStatut() {
		return $this->identite['statut'];
	}


	/**
	 * fixer le statut global de l'application à un niveau donné
	 * @param $statut
	 * @return void()
	 */
	public function setStatut($statut) {
		$this->identite['statut'] = $statut;
		}

	/**
	 * renvoie les informations d'identification réseau de l'utilisateur courant
	 * @param
	 * @return array ip, hostname, date, heure
	 */
	public static function identiteReseau () {
		$data = array();
		$data['ip'] = $_SERVER['REMOTE_ADDR'];
		$data['hostname'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$data['date'] = date("d/m/Y");
		$data['heure'] = date("H:i");
		return $data;
	}

	/**
	 * renvoie l'adresse mail de l'utilisateur courant
	 */
	public function getMail() {
		return $this->identite['mail'];
		}

	/**
	 * renvoie l'adresse IP de connexion de l'utilisateur actuel
	 * @param
	 * @return string
	 */
	public function getIP(){
		$data = $this->identiteReseau();
		return $data['ip'];
		}

	/**
	 * renvoie le nom de l'hôte correspondant à l'IP de l'utilisateur en cours
	 * @param
	 * @return string
	 */
	public function getHostname() {
		$data = $this->identiteReseau();
		return $data['hostname'];
		}


	/**
	 * renvoie la liste des logs de l'utilisateur en cours
	 * @param $userName
	 * @return array
	 */
	public function getLogins () {
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "SELECT * FROM ".PFX."logins WHERE user='".$this->getuserName()."' ORDER BY date,heure ASC";
		$resultat = $connexion->query($sql);
		$logins = array();
		if ($resultat) {
			$resultat->setFetchMode(PDO::FETCH_ASSOC);
			$logins = $resultat->fetchall();
			}
		Application::DeconnexionPDO ($connexion);
		return $logins;
		}
		

		
	/**
	 * liste les accès de l'utilisateur indiqué entre deux bornes
	 * @param $user		nom de l'utilisateur concerné
	 * @param $nombre  nombre d'accès à traiter
	 * @param $from		nombre de lignes à laisser tomber en début
	 * @return array : liste des derniers accès à l'application
	 */
	public function listeLogins() {
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "SELECT ip,host,date,DATE_FORMAT(heure,'%H:%i') as heure, reussi ";
		$sql .= "FROM ".PFX."logins ";
		$sql .= "WHERE user='$this->userName' ";
		$sql .= "ORDER BY date DESC,heure DESC ";
		$resultat = $connexion->query($sql);
		$acces = array();
		if ($resultat) {
			$resultat->setFetchMode(PDO::FETCH_ASSOC);
			while ($ligne = $resultat->fetch()) {
				$ligne['date'] = Application::datePHP($ligne['date']);
				$acces[] = $ligne;
				}
			}
		Application::deconnexionPDO($connexion);
		return $acces;
		}



	/**
	 * retourne le plus haut grade et le niveau correspondant pour l'élève actuel
	 * @return array
	 */
	public function highestGrade() {
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "SELECT niveau, grade  ";
		$sql .= "FROM ".PFX."elevesGrades AS elgr ";
		$sql .= "JOIN ".PFX."grades AS gr ON gr.idGrade = elgr.idGrade ";
		$sql .= "WHERE userName LIKE '$this->userName' ";
		$sql .= "ORDER BY niveau DESC ";
		$resultat = $connexion->query($sql);
		$highGrade = array();
		if ($resultat) {
			$resultat->setFetchMode(PDO::FETCH_ASSOC);
			$ligne = $resultat->fetch();
			$highGrade = $ligne;
			}
		Application::DeconnexionPDO ($connexion);
		return $highGrade;
		}
		
}

?>
