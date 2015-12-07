<?php

class Application
{
    public function __construct()
    {
        self::lireConstantes();
        // sorties PHP en français
        setlocale(LC_ALL, 'fr_FR.utf8');
    }

    /**
     * lecture de toutes les constantes du fichier config.ini.
     *
     * @param void()
     */
    public static function lireConstantes()
    {
        // lecture des paramètres généraux dans le fichier .ini, y compris la constante "PFX"
        $constantes = parse_ini_file(INSTALL_DIR.'/config.ini');
        foreach ($constantes as $key => $value) {
            define("$key", $value);
        }
    }

    /**
     * suppression de tous les échappements automatiques dans le tableau passé en argument.
     *
     * @param $tableau
     *
     * @return array
     */
    private function Normaliser($tableau)
    {
        foreach ($tableau as $clef => $valeur) {
            if (!is_array($valeur)) {
                $tableau [$clef] = stripslashes($valeur);
            } else {
                // appel récursif
                $tableau [$clef] = self::Normaliser($valeur);
            }
        }

        return $tableau;
    }

    ### --------------------------------------------------------------------###
    public function Normalisation()
    {
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
     * le programme est éventuellement interrompu si demandé.
     *
     * @param :    $data n'importe quel tableau ou variable
     * @param bool $die  : si l'on souhaite interrompre le programme avec le dump
     * */
    public static function afficher($data, $die = false)
    {
        if (count($data) == 0) {
            echo 'Tableau vide';
        } else {
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            echo '<hr />';
        }
        if ($die) {
            die();
        }
    }

    /**
     * renvoie le temps écoulé depuis le déclenchement du chrono.
     *
     * @param
     *
     * @return string
     */
    public static function chrono()
    {
        $temps = explode(' ', microtime());

        return $temps[0] + $temps[1];
    }

    /**
     * Connexion à la base de données précisée.
     *
     * @param PARAM_HOST : serveur hôte
     * @param PARAM_BD : nom de la base de données
     * @param PARAM_USER : nom d'utilisateur
     * @param PARAM_PWD : mot de passe
     *
     * @return connexion à la BD
     */
    public static function connectPDO($host, $bd, $user, $mdp)
    {
        try {
            // indiquer que les requêtes sont transmises en UTF8
            // INDISPENSABLE POUR EVITER LES PROBLEMES DE CARACTERES ACCENTUES
            $connexion = new PDO('mysql:host='.$host.';dbname='.$bd, $user, $mdp,
                                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        } catch (Exception $e) {
            $date = date('d/m/Y H:i:s');
            echo "<style type='text/css'>";
            echo '.erreurBD {width: 500px; margin-left: auto; margin-right: auto; border: 1px solid red; padding: 1em;}';
            echo '.erreurBD .erreur {color: green; font-weight: bold}';
            echo '</style>';

            echo "<div class='erreurBD'>";
            echo '<h3>A&iuml;e, a&iuml;e, a&iuml;e... Caramba...</h3>';
            echo "<p>Une erreur est survenue lors de l'ouverture de la base de donn&eacute;es.<br>";
            echo "Si vous &ecirc;tes l'administrateur et que vous tentez d'installer le logiciel, veuillez v&eacute;rifier le fichier config.inc.php </p>";
            echo "<p>Si le probl&egrave;me se produit durant l'utilisation r&eacute;guli&egrave;re du programme, essayez de rafra&icirc;chir la page (<span style='color: red;'>touche F5</span>)<br>";
            echo "Dans ce cas, <strong>vous n'&ecirc;tes pour rien dans l'apparition du souci</strong>: le serveur de base de donn&eacute;es est sans doute trop sollicit&eacute;...</p>";
            echo "<p>Veuillez rapporter le message d'erreur ci-dessous &agrave; l'administrateur du syst&egrave;me.</p>";
            echo "<p class='erreur'>Le $date, le serveur dit: ".$e->getMessage().'</p>';
            echo '</div>';
            die();
        }

        return $connexion;
    }

    /**
     * Déconnecte la base de données.
     *
     * @param $connexion
     */
    public static function DeconnexionPDO($connexion)
    {
        $connexion = null;
    }

    /**
     * retourne le nom du répertoire actuel.
     *
     * @param void()
     *
     * @return string
     */
    public static function repertoireActuel()
    {
        $dir = array_reverse(explode('/', getcwd()));

        return $dir[0];
    }

    /**
     * convertir les dates au format usuel jj/mm/AAAA en YY-mm-dd pour MySQL.
     *
     * @param string $date date au format usuel
     *
     * @return string date au format MySQL
     */
    public static function dateMysql($date)
    {
        $dateArray = explode('/', $date);
        $sqlArray = array_reverse($dateArray);
        $date = implode('-', $sqlArray);

        return $date;
    }

    /**
     * convertir les date au format MySQL vers le format usuel.
     *
     * @param string $date date au format MySQL
     *
     * @return string date au format usuel français
     */
    public static function datePHP($dateMysql)
    {
        $dateArray = explode('-', $dateMysql);
        $phpArray = array_reverse($dateArray);
        $date = implode('/', $phpArray);

        return $date;
    }

    /**
     * convertir les heures au format MySQL vers le format ordinaire à 24h.
     *
     * @param string $heure l'heure à convertir
     *
     * @return string l'heure au format usuel
     */
    public static function heureMySQL($heure)
    {
        $heureArray = explode(':', $heure);
        $sqlArray = array_reverse($heureArray);
        $heure = implode(':', $sqlArray);

        return $heure;
    }

    /**
     * converir les heures au format PHP vers le format MySQL.
     *
     * @param string $heure
     *
     * @return string
     */
    public static function heurePHP($heure)
    {
        $heureArray = explode(':', $heure);
        $sqlArray = array_reverse($heureArray);
        $heure = implode(':', $sqlArray);

        return $heure;
    }

    /**
     * retourne le jour de la semaine correspondant à une date au format MySQL.
     *
     * @param string $dataMySQL
     *
     * @return string
     */
    public static function jourSemaineMySQL($dateMySQL)
    {
        $timeStamp = strtotime($dateMySQL);

        return strftime('%A', $timeStamp);
    }

    /**
     * Fonction de conversion de date du format français (JJ/MM/AAAA) en Timestamp.
     *
     * @param string $date Date au format français (JJ/MM/AAAA)
     *
     * @return int Timestamp en seconde
     *             http://www.julien-breux.com/2009/02/17/fonction-php-date-francaise-vers-timestamp/
     */
    public static function dateFR2Time($date)
    {
        list($day, $month, $year) = explode('/', $date);
        $timestamp = mktime(0, 0, 0, $month, $day, $year);

        return $timestamp;
    }

    /**
     * date d'aujourd'hui.
     *
     * @param void()
     *
     * @return string
     */
    public static function dateNow()
    {
        return date('d/m/Y');
    }

    /**
     * filtrage des actions par utilisateur.
     *
     * @param $action : action envisagée
     * @param $userType : type d'utilisateur
     *
     * @return string : l'action permise ou Null
     */
    public function filtreAction($action, $userType)
    {
        switch ($userType) {
            case 'eleves':
                $permis = array('bulletin', 'anniversaires', 'jdc', 'parents', 'logoff', 'annonces', 'contact');
                if (!(in_array($action, $permis))) {
                    $action = null;
                }
                break;
            case 'parents':
                $permis = array('bulletin', 'jdc', 'profil', 'logoff', 'annonces', 'contact');
                if (!(in_array($action, $permis))) {
                    $action = null;
                }
                break;
            case 'admin':
                break;
            default:
                // wtf
                break;
        }

        return $action;
    }

    /**
     * retourne la liste des élèves pour une classe donnée.
     *
     * @param string $classe
     *
     * @return array()
     */
    public function listeEleves($classe)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT userName, nom, prenom, statut, mail ';
        $sql .= 'FROM '.PFX.'users ';
        $sql .= "WHERE classe = '$classe' ";
        $sql .= 'ORDER  BY nom, prenom ';
        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $userName = $ligne['userName'];
                $liste[$userName] = $ligne;
            }
        }
        self::DeconnexionPDO($connexion);

        return $liste;
    }

    /**
     * liste structurée des profs liés à une liste de coursGrp (liste indexée par coursGrp).
     *
     * @param string | array : $listeCoursGrp
     *
     * @return array
     */
    public function listeProfsListeCoursGrp($listeCoursGrp, $type = 'string')
    {
        if (is_array($listeCoursGrp)) {
            $listeCoursGrpString = "'".implode("','", array_keys($listeCoursGrp))."'";
        } else {
            $listeCoursGrpString = "'".$listeCoursGrp."'";
        }
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT coursGrp, nom, prenom, sexe, '.PFX.'profsCours.acronyme ';
        $sql .= 'FROM '.PFX.'profsCours ';
        $sql .= 'JOIN '.PFX.'profs ON ('.PFX.'profsCours.acronyme = '.PFX.'profs.acronyme) ';
        $sql .= "WHERE coursGrp IN ($listeCoursGrpString) ";
        $sql .= 'ORDER BY nom';

        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $coursGrp = $ligne['coursGrp'];
                $acronyme = $ligne['acronyme'];
                $sexe = $ligne['sexe'];
                $ved = ($sexe == 'M') ? 'M. ' : 'Mme';
                if ($type == 'string') {
                    if (isset($liste[$coursGrp])) {
                        $liste[$coursGrp] .= ', '.$ved.' '.$ligne['prenom'].' '.$ligne['nom'];
                    } else {
                        $liste[$coursGrp] = $ved.' '.$ligne['prenom'].' '.$ligne['nom'];
                    }
                } else {
                    $liste[$coursGrp][$acronyme] = $ligne;
                }
                // on supprime le cours dont le prof a été trouvé
                unset($listeCoursGrp[$coursGrp]);
            }
        }
        self::DeconnexionPDO($connexion);
            // on rajoute tous les cours dont les affectations de profs sont inconnues
            if ($listeCoursGrp != null) {
                foreach ($listeCoursGrp as $coursGrp => $wtf) {
                    $liste[$coursGrp] = PROFNONDESIGNE;
                }
            }

        return $liste;
    }

    /**
     * retourne la liste des utilisateurs uniques connectés depuis une date donnée.
     *
     * @param $date
     *
     * @return array
     */
    public function listeConnectesDate($date)
    {
        $date = $this->dateMysql($date);
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT DISTINCT user, classe, nom, prenom ';
        $sql .= 'FROM '.PFX.'parentLogins AS lo ';
        $sql .= 'JOIN '.PFX.'users AS users ON users.userName = lo.user ';
        $sql .= "WHERE date >= '$date' ";
        $sql .= 'ORDER by classe, nom, prenom ';

        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $userName = $ligne['user'];
                $liste[$userName] = $ligne;
            }
            self::DeconnexionPDO($connexion);

            return $liste;
        }
    }

    /**
     * liste structurée des profs liés à une liste de coursGrp (liste indexée par coursGrp).
     *
     * @param string | array : $listeCoursGrp
     *
     * @return array
     */

    /**
     * retourne la liste structurée par type de destinataire des annonces destinées à l'élève dont on donne le matricule et la classe.
     *
     * @param $matricule
     * @param $classe
     *
     * @return array
     */
    public function listeAnnonces($matricule, $classe, $listeCours)
    {
        $niveau = substr($classe, 0, 1);
        $listeCoursString = "'".implode('\',\'', $listeCours)."'";
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT dtn.id, type, proprietaire, destinataire, objet, texte, dateDebut, dateFin, urgence, dtn.mail, accuse, dp.nom, dp.sexe ';
        $sql .= 'FROM '.PFX.'thotNotifications AS dtn ';
        $sql .= 'LEFT JOIN '.PFX.'profs AS dp ON dp.acronyme = dtn.proprietaire ';
        $sql .= "WHERE destinataire IN ('$matricule', '$classe', '$niveau', 'ecole', $listeCoursString) ";
        $sql .= 'AND (dateFin > NOW() AND dateDebut <= NOW()) ';
        $sql .= 'ORDER BY urgence DESC, dateDebut ';
        $resultat = $connexion->query($sql);
        $listeAnnonces = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $type = $ligne['type'];
                // $destinataire = $ligne['destinataire'];
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
                $listeAnnonces[$type][$id] = $ligne;
            }
        }
        self::DeconnexionPDO($connexion);

        return $listeAnnonces;
    }

    /**
     * Liste des accusés de lecture demandés à un élève dont on fournit le matricule.
     *
     * @param $matricule : le matricule de l'élève
     *
     * @return array : la liste des accusés triés par id
     */
    public function listeAccusesEleve($matricule)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT id, dateHeure ';
        $sql .= 'FROM '.PFX.'thotAccuse ';
        $sql .= "WHERE matricule = '$matricule' ";
        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $id = $ligne['id'];
                $ligne['dateHeure'] = isset($ligne['dateHeure']) ? $this->dateHeure($ligne['dateHeure']) : null;
                $liste[$id] = $ligne;
            }
        }
        self::DeconnexionPDO($connexion);

        return $liste;
    }

    /**
     * Liste des annonces et des demandes d'accusés de lecture correspondant (si existant).
     *
     * @param $listeAnnonces : liste des annonces triées par id de l'annonce
     * @param $listeAccuses : liste des demandes d'accusés de lecture triées par id
     *
     * @return array : combinaison des deux arrays de données
     */
    public function listeAnnoncesAccuses($listeAnnonces, $listeAccuses)
    {
        foreach ($listeAnnonces as $type => $listeType) {
            foreach ($listeType as $id => $dataAnnonce) {
                $dateHeure = isset($listeAccuses[$id]) ? $listeAccuses[$id]['dateHeure'] : null;
                $listeAnnonces[$type][$id]['dateHeure'] = $dateHeure;
            }
        }

        return $listeAnnonces;
    }

    /**
     * retourne une liste simple des accusés de lecture pour une liste d'annonces; la liste est classée par groupe d'annonces.
     *
     * @param $listeAnnonces : la liste complète des annonces (voir la fonction listeAnnonces)
     * @param $matricule : matricule de l'élève concerné
     * @param $classe: classe de l'élève
     *
     * @return array : pour chaque type d'annonces, le nombre d'accusés de lecture demandés
     */
    public function shortListeAccuses($listeAnnonces, $matricule, $classe)
    {
        // détermination du niveau d'étude
        $niveau = substr($classe, 0, 1);
        // initialisation du tableau
        $listeAccuses = array('eleves' => 0, 'cours' => 0, 'classes' => 0, 'niveau' => 0, 'ecole' => 0);

        foreach ($listeAnnonces as $unType => $unPaquet) {
            foreach ($unPaquet as $destinataire => $uneAnnonce) {
                $type = $uneAnnonce['type'];
                if (($uneAnnonce['accuse'] == 1) && ($uneAnnonce['dateHeure'] == null)) {
                    $listeAccuses[$type]++;
                }
            }
        }

        // Application::afficher($listeAnnonces,true);
        // if (isset($listeAnnonces[$matricule])) {
        // 	$listeEleve = $listeAnnonces[$matricule];
        // 	foreach ($listeEleve as $id=>$uneAnnonce) {
        // 		if (($uneAnnonce['accuse'] == 1) && ($uneAnnonce['dateHeure'] == Null))
        // 			$listeAccuses['eleve']++;
        // 		}
        // 	}
        //
        // if (isset($listeAnnonces[$classe])) {
        // 	$listeClasse = $listeAnnonces[$classe];
        // 	foreach ($listeClasse as $uneAnnonce) {
        // 		if (($uneAnnonce['accuse'] == 1) && ($uneAnnonce['dateHeure'] == Null))
        // 			$listeAccuses['classe']++;
        // 		}
        // 	}
        // if (isset($listeAnnonces[$niveau])) {
        // 	$listeNiveau = $listeAnnonces[$niveau];
        // 	foreach ($listeNiveau as $uneAnnonce) {
        // 		if (($uneAnnonce['accuse'] == 1) && ($uneAnnonce['dateHeure'] == Null))
        // 			$listeAccuses['niveau']++;
        // 		}
        // 	}
        // if (isset($listeAnnonces['ecole'])) {
        // 	$listeEcole = $listeAnnonces['ecole'];
        // 	foreach ($listeEcole as $uneAnnonce) {
        // 		if (($uneAnnonce['accuse'] == 1) && ($uneAnnonce['dateHeure'] == Null))
        // 			$listeAccuses['ecole']++;
        // 		}
        // 	}
        return $listeAccuses;
    }

    /**
     * renvoie le nombre d'accusés de lecture pour une liste de demande d'accusés fournie.
     *
     * @param $listeAccuses : liste des accusés de lecture par type
     *
     * @return integer: nombre total d'accusés de lecture demandés
     */
    public function nbAccuses($listeAccuses)
    {
        $nb = 0;
        foreach ($listeAccuses as $type => $nombre) {
            $nb += $nombre;
        }

        return $nb;
    }

    /**
     * marque une notification lue pour un élève donné.
     *
     * @param $matricule: identité de l'élève
     * @param $id : id de la notification
     *
     * @return string: jour et heure de lecture
     */
    public function marqueAccuse($matricule, $id)
    {
        $dateHeure = '';
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'UPDATE '.PFX.'thotAccuse ';
        $sql .= 'SET dateHeure = NOW() ';
        $sql .= "WHERE id='$id' AND matricule='$matricule' ";
        $resultat = $connexion->exec($sql);
        if ($resultat) {
            $sql = 'SELECT dateHeure ';
            $sql .= 'FROM '.PFX.'thotAccuse ';
            $sql .= "WHERE id='$id' AND matricule='$matricule' ";

            $resultat = $connexion->query($sql);
            if ($resultat) {
                $resultat->setFetchMode(PDO::FETCH_ASSOC);
                $ligne = $resultat->fetch();
                $dateHeure = $this->dateHeure($ligne['dateHeure']);
            }
        }
        self::DeconnexionPDO($connexion);

        return $dateHeure;
    }

    /**
     * conversion des dateHeures comprenant la date et l'heure au format "classique" pour les dates et
     * en ajustant aux minutes pour les heures.
     *
     * @param $dateHeure : combinaison de date et d'heure au format MySQL Ex: "2015-07-30 11:33:59"
     *
     * @return string : la même chose au format "30/07/2015 11:33"
     */
    private function dateHeure($dateHeure)
    {
        $dateHeure = explode(' ', $dateHeure);
        $date = $dateHeure[0];
        $date = self::datePHP($date);
        $dateHeure = $date.' à '.substr($dateHeure[1], 0, 5);

        return $dateHeure;
    }

    /**
     * liste des parents déclarés pour un utilisateur "élève", d'après son matricule.
     *
     * @param $matricule
     *
     * @return array
     */
    public function listeParents($matricule)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT nom, prenom, formule, userName, mail, lien, md5pwd ';
        $sql .= 'FROM '.PFX.'thotParents ';
        $sql .= "WHERE matricule = '$matricule' ";
        $sql .= 'ORDER BY nom, prenom, userName ';
        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $userName = $ligne['userName'];
                $liste[$userName] = $ligne;
            }
        }
        self::DeconnexionPDO($connexion);

        return $liste;
    }

    /**
     * Vérification de l'existence éventuelle d'un utilisateur "parent".
     *
     * @param $userName
     *
     * @return bool
     */
    public function parentExiste($userName)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELCT * FROM '.PFX.'thotParents ';
        $sql .= "WHERE userName = '$userName' ";
        $resultat = $connexion->query($sql);
        self::DeconnexionPDO($connexion);

        return $resultat > 0;
    }

    /**
     * Enregistre les informations relatives à un parent et provenant d'un formulaire.
     *
     * @param $post : array le contenu du formulaire
     *
     * @return interger nombre d'enregistrement réussis
     */
    public function saveParent($post)
    {
        $ok = true;
        $formule = $post['formule'];
        if ($formule == '') {
            $ok = false;
        }
        $nomParent = $post['nomParent'];
        if ($nomParent == '') {
            $ok = false;
        }
        $prenomParent = $post['prenomParent'];
        if ($prenomParent == '') {
            $ok = false;
        }
        $userName = $post['userName'];
        if ($userName == '') {
            $ok = false;
        }
        $mail = $post['mail'];
        if ($mail == '') {
            $ok = false;
        }
        $matricule = $post['matricule'];
        if ($matricule == '') {
            $ok = false;
        }
        $lien = $post['lien'];
        if ($lien == '') {
            $ok = false;
        }
        $passwd = $post['passwd'];
        $passwd2 = $post['passwd2'];
        if (($passwd == '') || ($passwd2 != $passwd)) {
            $ok = false;
        }
        $resultat = 0;
        if ($ok == true) {
            $passwd = md5($passwd);
            $userName = $userName.$matricule;
            $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
            $sql = 'INSERT INTO '.PFX.'thotParents ';
            $sql .= "SET userName='$userName', matricule='$matricule', formule='$formule', nom='$nomParent', prenom='$prenomParent', ";
            $sql .= "mail='$mail', lien='$lien', md5pwd='$passwd' ";
            $sql .= 'ON DUPLICATE KEY UPDATE ';
            $sql .= "formule='$formule', nom='$nomParent', prenom='$prenomParent', ";
            $sql .= "mail='$mail', lien='$lien', md5pwd='$passwd' ";
            $resultat = $connexion->exec($sql);
            if ($resultat) {
                $resultat = 1;
            }  // pour éviter 2 modifications si DUPLICATE KEY
            self::DeconnexionPDO($connexion);
        }

        return $resultat;
    }

    /**
     * Enregistrement d'un profil modifié dans le formulaire ad-hoc.
     *
     * @param $post : le contenu du formulaire
     *
     * @return bool
     */
    public function saveProfilParent($post, $userName)
    {
        $ok = true;
        $formule = $post['formule'];
        if ($formule == '') {
            $ok = false;
        }
        $nom = $post['nom'];
        if ($nom == '') {
            $ok = false;
        }
        $prenom = $post['prenom'];
        if ($prenom == '') {
            $ok = false;
        }
        $mail = $post['mail'];
        if ($mail == '') {
            $ok = false;
        }
        $lien = $post['lien'];
        if ($lien == '') {
            $ok = false;
        }
        $passwd = $post['passwd'];
        $sqlPasswd = '';
        if ($passwd != '') {
            $passwd2 = $post['passwd2'];
            if ($passwd == $passwd2) {
                $passwd = md5($passwd);
                $sqlPasswd = ",md5pwd='$passwd' ";
            } else {
                $ok = false;
            }
        }
        $resultat = 0;
        if ($ok == true) {
            $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
            $sql = 'UPDATE '.PFX.'thotParents ';
            $sql .= "SET formule='$formule', nom='$nom', prenom='$prenom', mail='$mail', lien='$lien' ";
            $sql .= $sqlPasswd;
            $sql .= "WHERE userName = '$userName' ";
            $resultat = $connexion->exec($sql);
            self::DeconnexionPDO($connexion);
        }

        return $resultat;
    }

    /**
     * recherche les informations sur les parents d'un élève dont on fournit le matricule.
     *
     * @param $matricule : le matricule de l'élève (figure dans la fiche "parent")
     *
     * @return array
     */
    public function infoParents($matricule)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT formule, nom, prenom, userName, mail, lien, md5pwd ';
        $sql .= 'FROM '.PFX.'thotParents ';
        $sql .= "WHERE matricule = '$matricule' ";
        $resultat = $connexions->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $userName = $ligne['userName'];
                $liste[$userName] = $ligne;
            }
        }
        self::DeconnexionPDO($connexion);

        return $liste;
    }

    /**
     * recherche les informations d'identité d'un parent dont on fournit le userName.
     *
     * @param string $userName
     *
     * @return array
     */
    public function identiteParent($userName)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT tp.matricule, userName, formule, tp.nom, tp.prenom, mail, lien, de.nom AS nomEl, de.prenom AS prenomEl ';
        $sql .= 'FROM '.PFX.'thotParents AS tp ';
        $sql .= 'JOIN '.PFX.'eleves AS de ON de.matricule = tp.matricule ';
        $sql .= "WHERE userName = '$userName' ";
        $resultat = $connexion->query($sql);
        $ligne = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $ligne = $resultat->fetch();
        }
        self::DeconnexionPDO($connexion);

        return $ligne;
    }

    /**
     * recherche la présence d'un token donné dans la BD pour un utilisateur donné.
     *
     * @param $token : le token cherché
     * @param $user : le nom d'utilisateur correspondant au token
     *
     * @return bool
     */
    public function chercheToken($token, $user)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT user, token, date ';
        $sql .= 'FROM '.PFX.'lostPasswd ';
        $sql .= "WHERE token='$token' AND user='$user' AND date >= NOW() ";
        $sql .= 'LIMIT 1 ';

        $resultat = $connexion->query($sql);
        $userName = '';
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $ligne = $resultat->fetch();
            $userName = $ligne['user'];
        }
        self::DeconnexionPDO($connexion);

        return $userName;
    }

    /**
     * Enregistre le mot de passe provenant du formulaire et correspondant à l'utilisateur indiqué.
     *
     * @param array  $post     : contenu du formulaire
     * @param string $userName : nom d'utilisateur
     *
     * @return nombre d'enregistrements réussis (normalement 1)
     */
    public function savePasswd($post, $userName)
    {
        $passwd = isset($post['passwd']) ? $post['passwd'] : null;
        $passwd2 = isset($post['passwd2']) ? $post['passwd2'] : null;
        $nb = 0;
        if (($passwd == $passwd2) && ($passwd != '') && (strlen($passwd) >= 9)) {
            $passwd = md5($passwd);
            $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
            $sql = 'UPDATE '.PFX.'thotParents ';
            $sql .= "SET md5pwd = '$passwd' ";
            $sql .= "WHERE userName = '$userName' ";
            $resultat = $connexion->exec($sql);
            if ($resultat) {
                $nb = 1;
            }
            // suppression de tous les tokens de cet utilisateur dans la table des mots de passe à récupérer
            $sql = 'DELETE FROM '.PFX.'lostPasswd ';
            $sql .= "WHERE user = '$userName' ";
            $resultat = $connexion->exec($sql);
            self::DeconnexionPDO($connexion);
        }

        return $nb;
    }

    /**
     * Vérification de l'existence d'un utilisateur dont on fournit l'identifiant ou l'adresse mail.
     *
     * @param string $parametre : identifiant ou adresse mail
     * @param string $critere   : 'userName' ou 'mail'
     *
     * @return array : l'identité complète de l'utilisateur ou Null
     */
    public function verifUser($parametre, $critere)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT matricule, formule, nom, prenom, userName, mail, lien ';
        $sql .= 'FROM '.PFX.'thotParents ';

        if ($critere == 'userName') {
            $sql .= "WHERE userName = '$parametre' ";
            $sql .= 'LIMIT 1 ';
        } else {
            $sql .= "WHERE mail = '$parametre' ";
            $sql .= 'LIMIT 1 ';
        }

        $resultat = $connexion->query($sql);
        $identite = null;
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $identite = $resultat->fetch();
        }
        self::DeconnexionPDO($connexion);

        return $identite;
    }

    /**
     * Création d'un lien enregistré dans la base de données pour la récupération du mdp.
     *
     * @param void()
     *
     * @return string
     */
    public function createPasswdLink($userName)
    {
        $link = md5(microtime());
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'INSERT INTO '.PFX.'lostPasswd ';
        $sql .= "SET user='$userName', token='$link', date=NOW() + INTERVAL 2 DAY ";
        $sql .= "ON DUPLICATE KEY UPDATE token='$link', date=NOW() + INTERVAL 2 DAY ";
        $resultat = $connexion->exec($sql);
        self::DeconnexionPDO($connexion);

        return $link;
    }

    /**
     * Envoie un mail de rappel de mot de passe à l'utlisateur dont on a l'adresse.
     *
     * @param $link : le lien de l'adresse où changer le mdp
     * @param $identite	: toutes les informations d'identité de l'utilisateur
     * @param $identiteReseau : informations relatives à la connexion (IP,...)
     *
     * @return bool
     */
    public function mailPasswd($link, $identite, $identiteReseau)
    {
        $jSemaine = strftime('%A');
        $date = date('d/m/Y');
        $heure = date('H:i');

        $smarty = new Smarty();
        $smarty->assign('date', $date);
        $smarty->assign('heure', $heure);
        $smarty->assign('jour', $jSemaine);
        $smarty->assign('expediteur', MAILADMIN);
        $smarty->assign('identiteReseau', $identiteReseau);
        $smarty->assign('identite', $identite);
        $smarty->assign('ECOLE', ECOLE);
        $smarty->assign('ADRESSETHOT', ADRESSETHOT);
        $smarty->assign('link', $link);
        $texteFinal = $smarty->fetch('../mdp/templates/texteMailmdp.tpl');

        require_once '../phpMailer/class.phpmailer.php';
        $mail = new PHPmailer();
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->From = MAILADMIN;
        $mail->FromName = ADMINNAME;
        $mail->AddAddress($identite['mail']);
        $mail->Subject = RESET;
        $mail->Body = $texteFinal;

        return !$mail->Send();
    }

    /**
     * Effacement de toutes les notifications périmées et qui ne sont pas gelées par leur propriétaire.
     *
     * @param void()
     */
    public function delPerimes()
    {
        $date = date('Y-m-d');
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'DELETE FROM '.PFX.'thotNotifications ';
        $sql .= "WHERE dateFin < '$date' AND freeze = 0 ";

        $resultat = $connexion->exec($sql);
        self::DeconnexionPDO($connexion);
    }

    /**
     * retourne les éléments d'identité d'un prof dont on fournit l'acronyme.
     *
     * @param $acronyme
     *
     * @return array : formule, nom, prenom, mail
     */
    public function identiteProf($acronyme)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT sexe, nom, prenom, mail ';
        $sql .= 'FROM '.PFX."profs WHERE acronyme='$acronyme' ";
        $resultat = $connexion->query($sql);
        $ligne = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $ligne = $resultat->fetch();
            if ($ligne['sexe'] == 'F') {
                $ligne['formule'] = 'Mme';
            } else {
                $ligne['formule'] = 'M.';
            }
            $ligne['initiale'] = substr($ligne['prenom'], 0, 1).'. ';
        }
        self::DeconnexionPDO($connexion);

        return $ligne;
    }
}
