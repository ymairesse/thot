<?php

class user {
	private $userName;
	private $userType;			// eleves ou parents
	private $identite;			// données personnelles
	private $identiteReseau;  	// données réseau IP,...


	/**
	 * constructeur de l'objet user
	 */
	function __construct($userName=Null, $userType='eleve') {
		$this->identiteReseau = $this->identiteReseau();
		if (isset($userName)) {
			$this->userName = $userName;
			$this->userType = $userType;
			$this->setIdentite($userType);
			}
	}

	/**
	 * recherche toutes les informations de la table des utilisateurs pour l'utilisateur actif et les reporte dans l'objet User
	 * @param $userType : parent ou eleve
	 * @return void()
	 */
	public function setIdentite ($userType){
		$userName = $this->userName;
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		switch ($userType) {
			case 'eleves':
				$sql = "SELECT el.matricule, nom, prenom, classe, groupe, mailDomain, md5pwd ";
				$sql .= "FROM ".PFX."eleves AS el ";
				$sql .= "JOIN ".PFX."passwd AS ppw ON ppw.matricule = el.matricule ";
				$sql .= "WHERE ppw.user = '$userName' LIMIT 1 ";
				break;
			case 'parents':
				$sql = "SELECT formule, userName, tp.matricule, tp.nom, tp.prenom, lien, mail, classe, groupe, md5pwd, ";
				$sql .= "de.nom AS nomEl, de.prenom AS prenomEl ";
				$sql .= "FROM ".PFX."thotParents AS tp ";
				$sql .= "JOIN ".PFX."eleves AS de ON de.matricule = tp.matricule ";
				$sql .= "WHERE userName = '$userName' LIMIT 1 ";
				break;
			default:
				die('invalid userType');
				break;
		}
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
	 * renvoie le amtricule de l'utilisateur actif
	 * @param void()
	 * @return string
	 */
	public function getMatricule(){
		$userName = $this->userName;
		preg_match('/[0-9]+$/',$userName,$matches);
		return($matches[0]);
		}

	/**
	 * renvoie le groupe dont fait partie l'utilisateur
	 * @param void()
	 * @return string
	 */
	public function getClasse() {
		$identite = $this->identite;
		$classe = $identite['groupe'];
		return $classe;
		}

	/**
	 * retourne l'année d'étude de l'utilisateur
	 * @param void()
	 * @return integer
	 */
	public function getAnnee(){
		$identite = $this->identite;
		$annee = $identite['groupe'][0];
		return $annee;
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
	* renvoie le nom de l'élève correspondant au parent
	* @parem void()
	* @return string
	*/
	public function getNomEleve(){
		$prenom = isset($this->identite['prenomEl'])?$this->identite['prenomEl']:Null;
		$nom = isset($this->identite['nomEl'])?$this->identite['nomEl']:Null;
		if (($nom != Null) && ($prenom != Null))
			return $prenom." ".$nom;
			else return Null;
		}

	/**
	 * fournit le mot de passe MD5 de l'utilisateur
	 * @param
	 * @return string
	 */
	public function getPasswd() {
		return $this->identite['md5pwd'];
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
	* retourne le type d'utilisateur (parent ou eleve)
	* @param void()
	* @return string
	*/
	public function getUserType(){
		return $this->userType;
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
	public static function userExists($userName) {
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "SELECT userName FROM ".PFX."users ";
		$sql .= "WHERE userName = '$userName'";
		$resultat = $connexion->query($sql);
		if ($resultat) {
			$resultat->setFetchMode(PDO::FETCH_ASSOC);
			$ligne = $resultat->fetch();
			}
		Application::DeconnexionPDO($connexion);
		return ($ligne['userName']);
		}


	/**
	 * vérifier que l'utilisateur dont on fournit le userName est signalé comme loggé depuis l'adresse ip dans la BD
	 * @param $userName : string
	 * @param $ip : string
	 */
	public function islogged($userName,$ip) {
		$userName = $this->userName();
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "SELECT user, ip ";
		$sql .= "FROM ".PFX."sessions ";
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
	public function logger ($user) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$date = date("Y-m-d");
		$heure = date("H:i");
		$userName = $user->getuserName();
		$connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
		$sql = "INSERT INTO ".PFX."thotLogins ";
		$sql .= "SET user='$userName', date='$date', heure='$heure', ip='$ip', host='$hostname'";
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
	 * si un avatar est présent, retourne l'userName de l'utilisateur; sinon, retourne Null
	 * @param $userName
	 */
	public function photoExiste () {
		if (file_exists(INSTALL_DIR."/avatars/".$this->getuserName().".jpg"))
			return $this->userName;
			else return Null;
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


}

?>
