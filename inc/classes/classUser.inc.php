<?php

class user
{
    private $userName;
    private $acronyme;          // acronyme du prof en alias
    private $section;           // section pour l'élève (TQ, GT, TT, ...)
    private $userType;            // eleve, parent ou prof
    private $identite;            // données personnelles
    private $identiteReseau;    // données réseau IP,...

    /**
     * constructeur de l'objet user.
     */
    public function __construct($userName = null, $userType = 'eleve', $acronyme = null)
    {
        $this->identiteReseau = $this->identiteReseau();
        if (isset($userName)) {
            // pseudo
            $this->userName = $userName;
            // parent, eleve ou prof
            $this->userType = $userType;
            $this->acronyme = $acronyme;
            $this->setIdentite($userType);
        }
    }

    /**
     * recherche toutes les informations de la table des utilisateurs pour l'utilisateur actif et les reporte dans l'objet User.
     *
     * @param $userType : parent ou eleve
     */
    public function setIdentite($userType)
    {
        $userName = addslashes($this->userName);
        $acronyme = addslashes($this->acronyme);
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        switch ($userType) {
            case 'eleves':
                $sql = "SELECT 'eleve' AS type, el.matricule, nom, prenom, classe, groupe, section, mailDomain, md5pwd ";
                $sql .= 'FROM '.PFX.'eleves AS el ';
                $sql .= 'JOIN '.PFX.'passwd AS ppw ON ppw.matricule = el.matricule ';
                $sql .= "WHERE ppw.user = '$userName' LIMIT 1 ";
                break;
            case 'parents':
                $sql = "SELECT 'parent' AS type, formule, userName, tp.matricule, tp.nom, tp.prenom, lien, mail, classe, groupe, section, md5pwd, ";
                $sql .= 'de.nom AS nomEl, de.prenom AS prenomEl ';
                $sql .= 'FROM '.PFX.'thotParents AS tp ';
                $sql .= 'JOIN '.PFX.'eleves AS de ON de.matricule = tp.matricule ';
                $sql .= "WHERE userName = '$userName' LIMIT 1 ";
                break;
            // case 'prof':
            //     $sql = "SELECT 'prof' AS type, formule, userName, tp.matricule, nom, prenom, mail, classe, groupe, md5pwd, ";
            //     $sql .= 'dp.nom AS nomProf, dp.prenom AS prenomProf ';
            //     $sql .= 'JOIN '.PFX."profs AS dp ON dp.acronyme = '$acronyme' ";
            //     $sql .= 'JOIN '.PFX.'passwd AS ppw ON ppw.matricule = el.matricule ';
            //     $sql .= "
            default:
                die('invalid userType');
                break;
        }
        $resultat = $connexion->query($sql);
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $this->identite = $resultat->fetch();
        }
        Application::DeconnexionPDO($connexion);
    }

    /**
     * renvoie toutes les informations d'identité présentes dans l'objet User.
     *
     * @param void()
     *
     * @return array
     */
    public function getIdentite()
    {
        return $this->identite;
    }

    /**
     * renvoie le amtricule de l'utilisateur actif.
     *
     * @param void()
     *
     * @return string
     */
    public function getMatricule()
    {
        $identite = $this->identite;

        return $identite['matricule'];
    }

    /**
     * renvoie le groupe classe dont fait partie l'utilisateur.
     *
     * @param void()
     *
     * @return string
     */
    public function getClasse()
    {
        $identite = $this->identite;
        $classe = $identite['groupe'];

        return $classe;
    }

    public function getSection()
    {
        $identite = $this->identite;
        $section = $identite['section'];

        return $section;
    }

    /**
     * retourne l'année d'étude de l'utilisateur sur la base de son groupe classe.
     *
     * @param void()
     *
     * @return int
     */
    public function getAnnee()
    {
        $identite = $this->identite;
        $annee = $identite['groupe'][0];

        return $annee;
    }

    /**
     * renvoie le prénom et le nom de l'utilisateur.
     *
     * @param
     *
     * @return string
     */
    public function getNom()
    {
        $prenom = $this->identite['prenom'];
        $nom = $this->identite['nom'];

        return $prenom.' '.$nom;
    }

    /**
     * renvoie le nom de l'élève correspondant au parent.
     *
     * @parem void()
     *
     * @return string
     */
    public function getNomEleve()
    {
        $prenom = isset($this->identite['prenomEl']) ? $this->identite['prenomEl'] : null;
        $nom = isset($this->identite['nomEl']) ? $this->identite['nomEl'] : null;
        if (($nom != null) && ($prenom != null)) {
            return $prenom.' '.$nom;
        } else {
            return;
        }
    }

    /**
     * Renvoie la liste des coursGrp suivis par l'utilisateur.
     *
     * @param void()
     *
     * @return array
     */
    public function listeCoursEleve()
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $matricule = $this->getMatricule();
        $sql = 'SELECT coursGrp FROM '.PFX.'elevesCours ';
        $sql .= "WHERE matricule = '$matricule' ";
        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $liste[] = $ligne['coursGrp'];
            }
        }
        Application::deconnexionPDO($connexion);

        return $liste;
    }

    /**
     * fournit le mot de passe MD5 de l'utilisateur.
     *
     * @param
     *
     * @return string
     */
    public function getPasswd()
    {
        return $this->identite['md5pwd'];
    }

    /**
     * fournit le nom d'utilisateur de l'utilisateur actif.
     *
     * @param void()
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * retourne le type d'utilisateur (parent ou eleve).
     *
     * @param void()
     *
     * @return string
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * retourne toutes les informations concernant l'élève utilisateur (ou le parent).
     *
     * @param void()
     *
     * @return array
     */
    public function getTousDetailsEleve()
    {
        $matricule = $this->getMatricule();
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT * FROM '.PFX.'eleves ';
        $sql .= "WHERE matricule = '$matricule ' ";
        $resultat = $connexion->query($sql);
        $eleve = null;
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $eleve = $resultat->fetch();
            $eleve['DateNaiss'] = Application::datePHP($eleve['DateNaiss']);
        }

        Application::DeconnexionPDO($connexion);

        return $eleve;
    }

    /**
     * retourne le nom de l'application; permet de ne pas confondre deux applications
     * différentes qui utiliseraient la variable de SESSION pour retenir MDP et USERNAME
     * de la même façon.
     *
     * @param
     *
     * @return string
     */
    public function applicationName()
    {
        return $this->applicationName;
    }

    /**
     * Vérifie si un nom d'utilisateur est déjà défini pour un parent.
     *
     * @param string $userName
     *
     * @return bool
     */
    public function userExists($userName)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT count(*) FROM '.PFX.'thotParents ';
        $sql .= 'WHERE userName = :userName ';
        $requete = $connexion->prepare($sql);
        $data = array(':userName' => $userName);
        $resultat = $requete->execute($data);
        $nb = $requete->fetchColumn();
        Application::DeconnexionPDO($connexion);

        return $nb > 0;
    }

    /**
     * Vérifie si une adresse mail est déjà utilisée par un parent.
     *
     * @param string $mail
     *
     * @return bool
     */
    public function mailExists($mail)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT count(*) FROM '.PFX.'thotParents ';
        $sql .= "WHERE mail = '$mail' ";
        $resultat = $connexion->query($sql);
        $nb = $resultat->fetchColumn();
        Application::DeconnexionPDO($connexion);

        return $nb > 0;
    }

    /**
     * vérifier que l'utilisateur dont on fournit le userName est signalé comme loggé depuis l'adresse ip dans la BD.
     *
     * @param $userName : string
     * @param $ip : string
     */
    public function islogged($userName, $ip)
    {
        $userName = $this->userName();
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT user, ip ';
        $sql .= 'FROM '.PFX.'sessions ';
        $sql .= "WHERE user='$userName' AND ip='$ip' ";
        $resultat = $connexion->query($sql);
        if ($resultat) {
            $verif = $resultat->fetchAll();
        }
        Application::DeconnexionPDO($connexion);

        return count($verif) > 0;
    }

    /**
     * convertir l'objet $user en tableau.
     *
     * @param void()
     *
     * @return array
     */
    private function toArray()
    {
        return (array) $this;
    }

    /**
     * ajout de l'utilisateur dans le journal des logs.
     *
     * @param $userName	: userName de l'utilisateur
     *
     * @return int
     */
    public function logger($user)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $date = date('Y-m-d');
        $heure = date('H:i');
        $userName = $user->getuserName();
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'INSERT INTO '.PFX.'thotLogins ';
        $sql .= "SET user=:userName, date='$date', heure='$heure', ip='$ip', host=:hostname ";
        $requete = $connexion->prepare($sql);
        $data = array(':userName' => $userName, ':hostname' => $hostname);
        $n = $requete->execute($data);

        // indiquer une session ouverte depuis l'adresse IP correspondante
        $sql = 'INSERT INTO '.PFX.'thotSessions ';
        $sql .= "SET user=:userName, ip='$ip' ";
        $sql .= "ON DUPLICATE KEY UPDATE ip='$ip' ";
        $requete2 = $connexion->prepare($sql);

        $data = array(':userName' => $userName);
        $n = $requete2->execute($data);
        Application::DeconnexionPDO($connexion);

        return $n;
    }

    /**
     * délogger l'utilisateur indiqué de la base de données (table des sessions actives).
     *
     * @return int : nombre d'effacement dans la BD
     */
    public function delogger()
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $userName = $this->userName();
        $sql = 'DELETE FROM '.PFX.'thotSessions ';
        $sql .= "WHERE user='$userName' ";
        $resultat = $connexion->exec($sql);
        Application::DeconnexionPDO($connexion);

        return $resultat;
    }

    /**
     * renvoie le userName de l'utilisateur courant.
     *
     * @param
     *
     * @return string
     */
    public function userName()
    {
        return $this->userName;
    }

    /**
     * renvoie les informations d'identification réseau de l'utilisateur courant.
     *
     * @param
     *
     * @return array ip, hostname, date, heure
     */
    public static function identiteReseau()
    {
        $data = array();
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['hostname'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $data['date'] = date('d/m/Y');
        $data['heure'] = date('H:i');

        return $data;
    }

    /**
     * renvoie l'adresse mail de l'utilisateur courant.
     */
    public function getMail()
    {
        return $this->identite['mail'];
    }

    /**
     * renvoie l'adresse IP de connexion de l'utilisateur actuel.
     *
     * @param
     *
     * @return string
     */
    public function getIP()
    {
        $data = $this->identiteReseau();

        return $data['ip'];
    }

    /**
     * renvoie le nom de l'hôte correspondant à l'IP de l'utilisateur en cours.
     *
     * @param
     *
     * @return string
     */
    public function getHostname()
    {
        $data = $this->identiteReseau();

        return $data['hostname'];
    }

    /**
     * renvoie la liste des logs de l'utilisateur en cours.
     *
     * @param $userName
     *
     * @return array
     */
    public function getLogins()
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT * FROM '.PFX."logins WHERE user='".$this->getuserName()."' ORDER BY date,heure ASC";
        $resultat = $connexion->query($sql);
        $logins = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $logins = $resultat->fetchall();
        }
        Application::DeconnexionPDO($connexion);

        return $logins;
    }

    /**
     * liste les accès de l'utilisateur indiqué entre deux bornes.
     *
     * @param $user		nom de l'utilisateur concerné
     * @param $nombre  nombre d'accès à traiter
     * @param $from		nombre de lignes à laisser tomber en début
     *
     * @return array : liste des derniers accès à l'application
     */
    public function listeLogins()
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = "SELECT ip,host,date,DATE_FORMAT(heure,'%H:%i') as heure, reussi ";
        $sql .= 'FROM '.PFX.'logins ';
        $sql .= "WHERE user='$this->userName' ";
        $sql .= 'ORDER BY date DESC,heure DESC ';
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
