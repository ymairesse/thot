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

        // lecture dans la table PFX."config" de la BD
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT parametre,valeur ';
        $sql .= 'FROM '.PFX.'config ';
        $resultat = $connexion->query($sql);
        if ($resultat) {
            while ($ligne = $resultat->fetch()) {
                $key = $ligne['parametre'];
                $valeur = $ligne['valeur'];
                define("$key", $valeur);
            }
        } else {
            die('config table not present');
        }
        self::DeconnexionPDO($connexion);
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
                $permis = array('bulletin', 'documents', 'anniversaires', 'jdc', 'parents', 'logoff', 'annonces', 'contact', 'form');
                if (!(in_array($action, $permis))) {
                    $action = null;
                }
                break;
            case 'parents':
                $permis = array('bulletin', 'documents', 'jdc', 'profil', 'logoff', 'annonces', 'contact', 'reunionParents', 'form');
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
     * retourne la liste des profs titulaires pour un élève dont on fourni le matricule.
     *
     * @param $matricule
     *
     * @return array
     */
    public function listeTitulaires($matricule)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT DISTINCT dt.acronyme, dt.classe, dp.nom, dp.prenom, dp.sexe ';
        $sql .= 'FROM '.PFX.'eleves AS de ';
        $sql .= 'LEFT JOIN '.PFX.'titus AS dt ON dt.classe = de.groupe ';
        $sql .= 'JOIN '.PFX.'profs AS dp ON dp.acronyme = dt.acronyme ';
        $sql .= "WHERE matricule = '$matricule' ";
        $sql .= 'ORDER BY nom, prenom ';

        $liste = array();
        $resultat = $connexion->query($sql);
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $acronyme = $ligne['acronyme'];
                $liste[$acronyme] = $ligne;
            }
        }
        self::DeconnexionPDO($connexion);

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
                    ++$listeAccuses[$type];
                }
            }
        }

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

    /**
     * renvoie la liste des locaux pour une RP de date donnée.
     *
     * @param $date
     *
     * @return array
     */
    public function listeLocaux($date)
    {
        $date = $this::dateMysql($date);
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT acronyme, local ';
        $sql .= 'FROM '.PFX.'thotRpLocaux ';
        $sql .= "WHERE date = '$date' ";
        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $acronyme = $ligne['acronyme'];
                $liste[$acronyme] = $ligne['local'];
            }
        }
        self::deconnexionPDO($connexion);

        return $liste;
    }

    /**
     * Renvoie la liste des dates de réunions de parents prévues.
     *
     * @param $active : la réunion de parents est active et donc visible
     * @param $ouvert : la réunion de parents est ouverte à l'inscription
     *
     * @return array
     */
    public function listeDatesReunion($active = 0, $ouvert = 0)
    {
        // une réunion de parents inactive n'est certainement pas ouverte
        $ouvert = ($active == 0) ? 0 : $ouvert;
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = "SELECT DATE_FORMAT(date,'%d/%m/%Y') AS date, ouvert, active, notice ";
        $sql .= 'FROM '.PFX.'thotRp ';
        if ($ouvert != 0 && $active != 0) {
            $sql .= "WHERE active='$active' AND ouvert='$ouvert' ";
        } elseif ($active != 0) {
            $sql .= "WHERE active='1' ";
        } elseif ($ouvert != 0) {
            $sql .= "WHERE ouvert='1' ";
        }
        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $date = $ligne['date'];
                $liste[$date] = $date;
            }
        }

        self::deconnexionPDO($connexion);

        return $liste;
    }

    /**
     * renvoie la liste des RV pris pour un élève donné et pour une date donnée.
     *
     * @param $matricule : le matricule de l'élève
     * @param $date : la date de la réunion de parents
     *
     * @return array
     */
    public function getRVeleve($matricule, $date)
    {
        $date = $this::dateMysql($date);
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = "SELECT id, rv.matricule, date, DATE_FORMAT(heure,'%H:%i') AS heure, rv.acronyme, ";
        $sql .= 'dp.sexe, dp.nom, dp.prenom, userParent, ';
        $sql .= "'' AS formule, '' AS nomParent, '' AS prenomParent ,'' AS lien ";
        $sql .= 'FROM '.PFX.'thotRpRv AS rv ';
        $sql .= 'JOIN '.PFX.'profs AS dp ON rv.acronyme = dp.acronyme ';
        $sql .= "WHERE rv.matricule = '$matricule' AND date='$date' ";
        $sql .= 'ORDER BY heure ';

        $listeBrute = array();
        $resultat = $connexion->query($sql);
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $heure = $ligne['heure'];
                // on suppose qu'il n'y a pas deux RV à la même période
                $listeBrute[$heure] = $ligne;
            }
        }
        self::deconnexionPDO($connexion);

        // établir le lien avec la table des parents
        $listeUserParents = array_filter(array_column($listeBrute, 'userParent'));
        $listeParents = $this->listeParentsUserNames($listeUserParents);

        foreach ($listeBrute as $heure => $data) {
            $userParent = $data['userParent'];
            // s'il y a un userParent défini (inscription réalisée par un parent et non par le secrétariat)
            if ($userParent != '') {
                $parent = $listeParents[$userParent];
                $listeBrute[$heure]['formule'] = $parent['formule'];
                $listeBrute[$heure]['nomParent'] = $parent['nom'];
                $listeBrute[$heure]['prenomParent'] = $parent['prenom'];
                $listeBrute[$heure]['mail'] = $parent['mail'];
                $listeBrute[$heure]['lien'] = $parent['lien'];
                $listeBrute[$heure]['userName'] = $parent['userName'];
            }
        }

        // établir le lien avec la table des locaux
        $listeLocaux = $this->listeLocaux($date);
        foreach ($listeBrute as $heure => $data) {
            $acronyme = $listeBrute[$heure]['acronyme'];
            $listeBrute[$heure]['local'] = (isset($listeLocaux[$acronyme])) ? $listeLocaux[$acronyme] : null;
        }

        return $listeBrute;
    }

    /**
     * retourne la liste d'attentes des RV de l'élève dont on fournit le matricule pour la réunion dont on indique la date.
     *
     * @param $matricule : matricule de l'élève
     * @param $date : date de la réunion
     *
     * @return array
     */
    public function getListeAttenteEleve($matricule, $date)
    {
        $date = $this::dateMysql($date);
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT at.acronyme, dp.sexe, dp.nom AS nomProf, dp.prenom AS prenomProf, ';
        $sql .= 'at.userName, periode, tp.formule, tp.nom AS nomParent, tp.prenom AS prenomParent ';
        $sql .= 'FROM '.PFX.'thotRpAttente AS at ';
        $sql .= 'JOIN '.PFX.'profs AS dp ON dp.acronyme = at.acronyme ';
        $sql .= 'LEFT JOIN '.PFX.'thotParents AS tp ON tp.userName = at.userName ';
        $sql .= "WHERE date='$date' AND at.matricule='$matricule' ";
        $sql .= 'ORDER BY periode, acronyme ';

        $liste = array();
        $resultat = $connexion->query($sql);
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $periode = $ligne['periode'];
                // on suppose qu'il n'y a pas deux RV à la même période
                $liste[] = $ligne;
            }
        }
        self::deconnexionPDO($connexion);

        return $liste;
    }

    /**
     * Envoie en liste d'attente un élève dont on donne le matricule,
     * pour le prof dont on indique l'acronyme
     * pour la RP dont on indique la date avec la période indiquée (entre 1 et 3).
     *
     * @param $matricule: le matricule de l'élève
     * @param $acronyme : l'acronyme du prof
     * @param $date : la date de la RP
     * @param $periode : la période choisie pour un RV éventuel
     *
     * @return int : le nombre d'insertions (en principe, 1 ou 0 si échec de l'enregistrement)
     */
    public function setListeAttenteEleve($userName, $matricule, $acronyme, $date, $periode)
    {
        $date = self::dateMysql($date);
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'INSERT INTO '.PFX.'thotRpAttente     ';
        $sql .= "SET userName='$userName', matricule='$matricule', acronyme='$acronyme', date='$date', periode='$periode' ";
        $resultat = $connexion->exec($sql);
        self::deconnexionPDO($connexion);

        return $resultat;
    }

    /**
     * Suppression d'une présence en liste d'attente pour le prof dont on fournit l'acronyme et pour la période donnée.
     *
     * @param $acronyme : l'acronyme du prof
     * @param $periode : la période demandée pour le RV éventuel
     *
     * @return int : le nombre de suppressions (0 ou 1)
     */
    public function delAttente($acronyme, $periode)
    {
        // précaution
        if (!(is_numeric($periode))) {
            die();
        }
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'DELETE FROM '.PFX.'thotRpAttente ';
        $sql .= 'WHERE acronyme=:acronyme AND periode=:periode ';
        $requete = $connexion->prepare($sql);
        $data = array(':acronyme' => $acronyme, ':periode' => $periode);
        $requete->execute($data);
        $nb = $requete->rowCount();
        self::deconnexionPDO($connexion);

        return $nb;
    }

    /**
     * retourne la liste des cours d'un élève dont on fournit le matricule.
     *
     * @param $matricule
     *
     * @return array
     */
    public function listeProfsCoursEleve($matricule)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = "SELECT ec.coursGrp, SUBSTR(ec.coursGrp,1,LOCATE('-',ec.coursGrp)-1)  AS cours, ";
        $sql .= 'libelle, nbheures, pc.acronyme, nom, prenom, sexe ';
        $sql .= 'FROM '.PFX.'elevesCours AS ec ';
        $sql .= 'JOIN '.PFX.'profsCours AS pc ON pc.coursGrp = ec.coursGrp ';
        $sql .= 'JOIN '.PFX."cours AS dc ON dc.cours = SUBSTR(ec.coursGrp,1,LOCATE('-',ec.coursGrp)-1) ";
        $sql .= 'JOIN '.PFX.'profs AS dp ON dp.acronyme = pc.acronyme ';
        $sql .= "WHERE matricule = '$matricule' ";
        $sql .= 'ORDER BY nom, prenom ';
        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $acronyme = $ligne['acronyme'];
                $liste[$acronyme] = $ligne;
            }
        }
        self::deconnexionPDO($connexion);

        return $liste;
    }

    /**
     * retourne une liste cohérente du personnel d'encadrement (prof + direction et al)
     * à partir de la liste des profs (avec cours) et de la liste du personnel à statut "spécial".
     *
     * @param $listeProfsCours : liste des profs avec leurs coursGrp
     * @param $listeStatutsSpeciaux : liste des membres du personnel à statut "spécial"
     *
     * @return array
     */
    public function encadrement($listeProfsCours, $listeStatutsSpeciaux)
    {
        $listeEncadrement = $listeProfsCours;
        foreach ($listeStatutsSpeciaux as $acronyme => $data) {
            $listeEncadrement[$acronyme] = array(
                'coursGrp' => '',
                'cours' => '',
                'libelle' => 'Direction et encadrement',
                'nbheures' => '',
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'sexe' => $data['sexe'],
            );
        }

        return $listeEncadrement;
    }

    /**
     * recherche les informations d'un RV dont on fournit l'id.
     *
     * @param $id : l'identifiant du RV
     *
     * @return array
     */
    public function getInfoRV($id)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT acronyme, rv.matricule, formule, nom, prenom, userParent, ';
        $sql .= "DATE_FORMAT( date, '%d/%m/%Y' ) AS date, DATE_FORMAT(heure,'%Hh%i') AS heure, dispo, mail ";
        $sql .= 'FROM '.PFX.'thotRpRv AS rv ';
        $sql .= 'LEFT JOIN '.PFX.'thotParents AS tp ON tp.matricule = rv.matricule ';
        $sql .= "WHERE id = '$id' ";
        $resultat = $connexion->query($sql);
        $ligne = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $ligne = $resultat->fetch();
        }
        self::deconnexionPDO($connexion);

        return $ligne;
    }

    /**
     * inscription à un RV donné des parents d'un élève dont on fournit le amtricule
     * procédure pour l'admin afin d'inscrire un parent dont on a reçu une demande de RV "papier".
     * le nombre maximum de rendez-vous est passé en paramètre.
     *
     * @param $id : l'identifiant du RV
     * @param $matricule : le matricule de l'élève dont on inscrit un parent
     * @param $max : le nombre max de RV
     *
     * @return int : -1  si inscription over quota ($max), 0 si écriture impossible dans la BD, 1 si tout OK
     */
    public function inscriptionEleve($id, $matricule, $max, $userParent = null)
    {
        // rechercher les heures de RV existantes à la date de la RP pour l'élève
        $infoRV = $this->getInfoRV($id);
        $date = self::dateMysql($infoRV['date']);
        // on a la date, on peut chercher la liste des heures de RV (entre des guillemets simples)
        $listeRV = $this->getRVeleve($matricule, $date);
        $listeHeures = "'".implode("','", array_keys((isset($listeRV[$matricule])) ? $listeRV[$matricule] : $listeRV))."'";

        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        // compter le nombre de RV
        $sql = 'SELECT count(*) AS nb ';
        $sql .= 'FROM '.PFX.'thotRpRv ';
        $sql .= "WHERE matricule = '$matricule' ";
        $resultat = $connexion->query($sql);
        $ligne = $resultat->fetch();
        if ($resultat) {
            if ($ligne['nb'] >= $max) {
                return -1;
            }
        }

        // l'élève a-t-il déjà un RV à cette heure-là
        $sql = 'SELECT heure ';
        $sql .= 'FROM '.PFX.'thotRpRv ';
        $sql .= "WHERE id='$id' AND heure IN ($listeHeures) ";
        $resultat = $connexion->query($sql);
        if ($resultat) {
            $ligne = $resultat->fetch();
            if (isset($ligne['heure'])) {
                return -2;
            }
        }

        // le prof a-t-il déjà un RV à cette heure-là?
        $sql = 'SELECT matricule ';
        $sql .= 'FROM '.PFX.'thotRpRv ';
        $sql .= "WHERE id='$id' AND matricule != '' ";
        $resultat = $connexion->query($sql);
        if ($resultat) {
            $ligne = $resultat->fetch();
            if (isset($ligne['matricule'])) {
                return -3;
            }
        }

        // tout va bien, on peut l'inscrire
        $sql = 'UPDATE '.PFX.'thotRpRv ';
        $sql .= 'SET matricule=:matricule, userParent=:userParent, dispo=0 ';
        $sql .= 'WHERE id=:id ';
        $requete = $connexion->prepare($sql);
        $data = array(':matricule' => $matricule, ':userParent' => $userParent, ':id' => $id);
        $resultat = $requete->execute($data);
        self::deconnexionPDO($connexion);

        return $resultat;
    }

    /**
     * Effacement d'un RV dont on fournit l'identifiant.
     *
     * @param $id : l'identifiant
     *
     * @return interger : le nombre de suppressions (0 ou 1)
     */
    public function delRV($id)
    {
        // précaution
        if (!(is_numeric($id))) {
            die();
        }
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'UPDATE '.PFX.'thotRpRv ';
        $sql .= 'SET matricule=Null, userParent=Null, dispo=1 ';
        $sql .= 'WHERE id=:id ';
        $requete = $connexion->prepare($sql);
        $data = array(':id' => $id);
        $requete->execute($data);
        $nb = $requete->rowCount();
        self::deconnexionPDO($connexion);

        return $nb;
    }

    /**
     * recherche les caractéristiques d'une réunion de parents dont on fournit la date.
     *
     * @param $date
     *
     * @return array
     */
    public function getInfoRp($date)
    {
        $date = self::dateMysql($date);
        $heuresLimites = $this->heuresLimite($date);
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT rp.date, ouvert, active, notice, typeRP, ';
        $sql .= "DATE_FORMAT(minPer1,'%H:%i') AS minPer1, DATE_FORMAT(maxPer1,'%H:%i') AS maxPer1, ";
        $sql .= "DATE_FORMAT(minPer2,'%H:%i') AS minPer2, DATE_FORMAT(maxPer2,'%H:%i') AS maxPer2, ";
        $sql .= "DATE_FORMAT(minPer3,'%H:%i') AS minPer3, DATE_FORMAT(maxPer3,'%H:%i') AS maxPer3 ";
        $sql .= 'FROM '.PFX.'thotRp AS rp ';
        $sql .= 'JOIN '.PFX.'thotRpHeures AS rh ON rh.date = rp.date ';
        $sql .= "WHERE rp.date = '$date' ";

        $resultat = $connexion->query($sql);
        $ligne = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $ligne = $resultat->fetch();
        }
        self::deconnexionPDO($connexion);
        $tableau = array(
            'date' => $date,
            'heuresLimites' => $heuresLimites,
            'typeRP' => $ligne['typeRP'],
            'generalites' => array('ouvert' => $ligne['ouvert'], 'active' => $ligne['active'], 'notice' => $ligne['notice']),
            'heures' => array(
                'minPer1' => $ligne['minPer1'],
                'minPer2' => $ligne['minPer2'],
                'minPer3' => $ligne['minPer3'],
                'maxPer1' => $ligne['maxPer1'],
                'maxPer2' => $ligne['maxPer2'],
                'maxPer3' => $ligne['maxPer3'], ),
            );

        return $tableau;
    }

    /**
     * retourne les heures de début et de fin d'une réunion dont on fournit la date.
     *
     * @param $date
     *
     * @return array : les deux limites
     */
    public function heuresLimite($date)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT MIN(heure) AS min, MAX(heure) AS max ';
        $sql .= 'FROM '.PFX.'thotRpRv ';
        $sql .= "WHERE date = '$date' ";
        $resultat = $connexion->query($sql);
        $ligne = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $ligne = $resultat->fetch();
        }
        self::deconnexionPDO($connexion);

        return $ligne;
    }

        /**
         * retourne la liste des nom, prenom et classe des élèves dont on passe la liste des matricules.
         *
         * @param $matricules : array|integer
         *
         * @return array : trié sur les matricules
         */
        public function listeElevesMatricules($listeEleves)
        {
            if (is_array($listeEleves)) {
                $listeMatricules = implode(',', $listeEleves);
            } else {
                $listeMatricules = $listeEleves;
            }

            $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
            $sql = 'SELECT matricule, groupe, nom, prenom ';
            $sql .= 'FROM '.PFX.'eleves ';
            $sql .= "WHERE matricule IN ($listeMatricules) ";
            $resultat = $connexion->query($sql);
            $listeEleves = array();
            if ($resultat) {
                $resultat->setFetchMode(PDO::FETCH_ASSOC);
                while ($ligne = $resultat->fetch()) {
                    $matricule = $ligne['matricule'];
                    $listeEleves[$matricule] = $ligne;
                }
            }
            self::deconnexionPDO($connexion);

            return $listeEleves;
        }

        /**
         * retourne la liste des nom, prenom, mail des parents dont on fournit la liste des userNames.
         *
         * @param array (ou pas) de la liste des userNames
         *
         * @return array
         */
        public function listeParentsUserNames($listeUserNames)
        {
            if (is_array($listeUserNames)) {
                $listeUserNamesString = "'".implode("','", $listeUserNames)."'";
            } else {
                $listeUserNamesString = "'".$listeUserNames."'";
            }
            $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
            $sql = 'SELECT formule, nom, prenom, mail, lien, userName ';
            $sql .= 'FROM '.PFX.'thotParents ';
            $sql .= "WHERE userName IN ($listeUserNamesString) ";

            $resultat = $connexion->query($sql);
            $listeParents = array();
            if ($resultat) {
                $resultat->setFetchMode(PDO::FETCH_ASSOC);
                while ($ligne = $resultat->fetch()) {
                    $userName = $ligne['userName'];
                    $listeParents[$userName] = $ligne;
                }
            }
            self::deconnexionPDO($connexion);

            return $listeParents;
        }

    /**
     * renvoie la liste des RV pris pour un prof donné et pour une date donnée.
     *
     * @param $acronyme : l'acronyme du profs
     * @param $date : la date de la réunion de parents
     *
     * @return array
     */
    public function getRVprof($acronyme, $date)
    {
        $date = self::dateMysql($date);
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = "SELECT id, rv.matricule, userParent, TIME_FORMAT(heure,'%H:%i') AS heure, dispo, ";
        $sql .= "'' AS formule, '' AS nomParent, '' AS prenomParent, '' AS userName, '' AS mail, '' AS lien, ";
        $sql .= "'' AS nom, '' AS prenom, '' AS groupe ";
        $sql .= 'FROM '.PFX.'thotRpRv AS rv ';
        $sql .= "WHERE acronyme = '$acronyme' AND date = '$date' ";
        $sql .= 'ORDER BY heure ';

        $listeBrute = array();
        $resultat = $connexion->query($sql);

        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $id = $ligne['id'];
                $matricule = $ligne['matricule'];
                $listeBrute[$id] = $ligne;
            }
        }
        self::deconnexionPDO($connexion);

        // retrouver les caractéristiques des élèves qui figurent dans le tableau des RV
        $listeMatricules = array_filter(array_column($listeBrute, 'matricule'));
        $listeEleves = $this->listeElevesMatricules($listeMatricules);

        // retrouver les caractéristiques des parents qui figurent dans le tableau des RV
        $listeUserParents = array_filter(array_column($listeBrute, 'userParent'));
        $listeParents = $this->listeParentsUserNames($listeUserParents);

        // recombinaison des trois listes
        foreach ($listeBrute as $id => $data) {
            if ($data['matricule'] != '') {
                $matricule = $data['matricule'];
                $eleve = $listeEleves[$matricule];
                $listeBrute[$id]['nom'] = $eleve['nom'];
                $listeBrute[$id]['prenom'] = $eleve['prenom'];
                $listeBrute[$id]['groupe'] = $eleve['groupe'];
            }
            if ($data['userParent'] != '') {
                $userName = $data['userParent'];
                $parent = $listeParents[$userName];
                $listeBrute[$id]['formule'] = $parent['formule'];
                $listeBrute[$id]['nomParent'] = $parent['nom'];
                $listeBrute[$id]['prenomParent'] = $parent['prenom'];
                $listeBrute[$id]['mail'] = $parent['mail'];
                $listeBrute[$id]['lien'] = $parent['lien'];
                $listeBrute[$id]['userName'] = $parent['userName'];
            }
        }

        return $listeBrute;
    }

    /**
     * retourne les périodes pour les listes d'attente pour une RP dont on donne la date.
     *
     * @param $date
     *
     * @return array
     */
    public function getListePeriodes($date)
    {
        $infoRp = $this->getInfoRp($date);
        $liste = $infoRp['heures'];
        $listeHeures = array(
            '1' => array('min' => $liste['minPer1'], 'max' => $liste['maxPer1']),
            '2' => array('min' => $liste['minPer2'], 'max' => $liste['maxPer2']),
            '3' => array('min' => $liste['minPer3'], 'max' => $liste['maxPer3']),
        );

        return $listeHeures;
    }

    /**
     * vérification que l'id passé est compatible avec la date de la RP envisagée.
     *
     * @param $id : l'id de la réunion de parent
     * @param $date
     *
     * @return bool
     */
    public function validIdDate($id, $date)
    {
        $date = $this::dateMysql($date);
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT * FROM '.PFX.'thotRpRv ';
        $sql .= "WHERE id='$id' AND date = '$date' ";
        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            while ($ligne = $resultat->fetch()) {
                $liste[] = $ligne;
            }
        }
        self::deconnexionPDO($connexion);

        return count($liste) == 1;
    }

    /**
     * vérification que lA DATE passéE est vraiment une date de RP.
     *
     * @param $date
     *
     * @return bool
     */
    public function validDate($date)
    {
        $date = self::dateMysql($date);
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT * FROM '.PFX.'thotRp ';
        $sql .= "WHERE date = '$date' ";
        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            while ($ligne = $resultat->fetch()) {
                $liste[] = $ligne;
            }
        }
        self::deconnexionPDO($connexion);

        return count($liste) == 1;
    }

    /**
     * Vérifier que le RV avec le prof donné n'est pas un doublon (déjà RV).
     *
     * @param $matricule : le matricule de l'élève
     * @param $acronyme  : l'acronyme du profs
     * @param $date: la date de la RP
     *
     * @return bool
     */
    public function rdvIsDoublon($matricule, $acronyme, $date)
    {
        $listeRVEleve = $this->getRVeleve($matricule, $date);
        $doublon = false;
        foreach ($listeRVEleve as $heure => $data) {
            if (!($doublon)) {
                if ($data['acronyme'] == $acronyme) {
                    $doublon = true;
                }
            }
        }

        return $doublon;
    }

    /**
     * retourne la liste des membres du peresonnel à statut spécial (direction, PMS,...)
     * qui doivent apparaître dans liste des RV possibles.
     *
     * @param void()
     *
     * @return array
     */
    public function listeStatutsSpeciaux()
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT DISTINCT rv.acronyme,  nom, prenom, sexe ';
        $sql .= 'FROM '.PFX.'thotRpRv AS rv ';
        $sql .= 'JOIN '.PFX.'profs AS dp ON dp.acronyme = rv.acronyme ';
        $sql .= "WHERE rv.statut = 'dir' ";
        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $acronyme = $ligne['acronyme'];
                $liste[$acronyme] = $ligne;
            }
        }
        self::deconnexionPDO($connexion);

        return $liste;
    }

    /**
     * vérifie qu'un membre du personnel dont l'acronyme est fourni existe.
     *
     * @param $acronyme
     *
     * @return bool
     */
    public function profExiste($acronyme)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT acronyme FROM '.PFX.'profs ';
        $sql .= "WHERE acronyme='$acronyme' ";
        $resultat = $connexion->query($sql);
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $ligne = $resultat->fetch();
            $acro = $ligne['acronyme'];
        }

        self::deconnexionPDO($connexion);

        return $acronyme = $acro;
    }

    /**
     * Vérifier que le propriétaire de l'entrevue $id est bien l'utilisateur actuel $userName.
     *
     * @param $id : l'identifiant de l'entrevue
     * @param $userName : le nom de l'utilisateur courant
     *
     * @return bool
     */
    public function isOwnerRV($id, $userName)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT userParent ';
        $sql .= 'FROM '.PFX.'thotRpRv ';
        $sql .= "WHERE id='$id' ";

        $resultat = $connexion->query($sql);
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $ligne = $resultat->fetch();
            $userParent = $ligne['userParent'];
        }

        self::deconnexionPDO($connexion);

        return $userParent == $userName;
    }

    /**
     * Vérifier que le propriétaire de la demande de liste d'attente ($acronyme du prof et période) est bien l'utilisateur actuel $userName.
     *
     * @param $acronyme : acronyme du prof
     * @param $periode : période demandée
     * @param $userName : nom de l'utilisateur courant
     *
     * @return bool
     */
    public function isOwnerAttente($acronyme, $periode, $userParent)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT userName ';
        $sql .= 'FROM '.PFX.'thotRpAttente ';
        $sql .= "WHERE acronyme='$acronyme' AND periode='$periode' AND userName='$userParent' ";
        $resultat = $connexion->query($sql);
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $ligne = $resultat->fetch();
            $userName = $ligne['userName'];
        }

        self::deconnexionPDO($connexion);

        return $userParent == $userName;
    }

    /**
     * retourne le nombre de RV déjà pris pour la RP de la date donnée.
     *
     * @param $date : la date de la RP
     *
     * @return int
     */
    public function nbRv($date)
    {
        $date = $this->dateMysql($date);
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT COUNT(*) AS nb ';
        $sql .= 'FROM '.PFX."thotRpRv WHERE matricule != '' AND date = '$date' ";
        $resultat = $connexion->query($sql);
        if ($resultat) {
            $ligne = $resultat->fetch();
        }
        self::deconnexionPDO($connexion);

        return $ligne['nb'];
    }

// Fonctions pour la gestion des RV hors des réunions de parents **************************

    /**
     * retourne les dates pour lesquelles un RV est encore possible avec le membre du personnel mentionné.
     *
     * @param $acronyme : le membre du personnel
     *
     * @return array : la liste des dates au double format PHP et MySQL
     */
    public function listeDatesRV($acronyme)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT DISTINCT date ';
        $sql .= 'FROM '.PFX.'thotRv ';
        $sql .= "WHERE contact = '$acronyme' AND md5conf is Null ";

        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $date = $ligne['date'];
                $jourSemaine = $this->jourSemaineMySQL($date);
                $datePHP = $this->datePHP($ligne['date']);
                $ligne = array('date' => $date, 'datePHP' => $datePHP, 'jourSemaine' => $jourSemaine);
                $liste[] = $ligne;
            }
        }

        self::deconnexionPDO($connexion);

        return $liste;
    }

    /**
     * renvoie la liste des RV disponibles pour une date donnée.
     *
     * @param $date : la date visée
     * @param $confirme : boolean false (défaut) si l'on ne souhatie que les plages encore libres
     *
     * @return array
     */
    public function listeHeuresRV($date, $confirme = false)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT id, heure ';
        $sql .= 'FROM '.PFX.'thotRv ';
        $sql .= "WHERE date = '$date' ";
        if ($confirme == false) {
            $sql .= 'AND md5conf is Null ';
        }
        $sql .= 'ORDER BY heure ';
        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $id = $ligne['id'];
                $liste[$id] = $ligne;
            }
        }

        self::deconnexionPDO($connexion);

        return $liste;
    }

    /**
     * renvoie les caractéristiques d'un moment de RV dont on fournit l'identifiant.
     *
     * @param $id : l'identifiant du RV dans la BD
     *
     * @return array
     */
    public function getRvById($id)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = "SELECT id, contact, DATE_FORMAT(date,'%d/%m/%Y') AS date, DATE_FORMAT(heure,'%Hh%i') AS heure, ";
        $sql .= 'nom, prenom, email, dateHeure, md5conf, confirme ';
        $sql .= 'FROM '.PFX.'thotRv ';
        $sql .= "WHERE id='$id' ";
        $resultat = $connexion->query($sql);
        $ligne = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $ligne = $resultat->fetch();
        }
        self::deconnexionPDO($connexion);

        return $ligne;
    }

    /**
     * renvoie les caractéristiques d'un moment de RV dont on fournit le token de réservation.
     *
     * @param $token : le token de réservation du RV dans la BD
     *
     * @return array
     */
    public function getRvByToken($token)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = "SELECT id, contact, DATE_FORMAT(date,'%d/%m/%Y') AS date, DATE_FORMAT(heure,'%Hh%i') AS heure, ";
        $sql .= 'nom, prenom, email, dateHeure, md5conf, confirme ';
        $sql .= 'FROM '.PFX.'thotRv ';
        $sql .= "WHERE md5conf='$token' ";
        $resultat = $connexion->query($sql);
        $ligne = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $ligne = $resultat->fetch();
        }
        self::deconnexionPDO($connexion);

        return $ligne;
    }

    /**
     * vérifier qu'un moment de RV est encore libre.
     *
     * @param $id : l'identifiant du RV
     *
     * @return bool
     */
    public function isFreeRV($id)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT md5conf, confirme ';
        $sql .= 'FROM '.PFX.'thotRv ';
        $sql .= "WHERE id='$id' ";
        $resultat = $connexion->query($sql);
        // si l'identifiant n'existe pas, le RV n'est pas disponible
        $free = false;
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $ligne = $resultat->fetch();
            $md5conf = $ligne['md5conf'];
            $confirme = $ligne['confirme'];
            // si le RV n'est ni en attente de confirmation, ni confirme, il est disponible
            $free = ($md5conf == null);
        }
        self::deconnexionPDO($connexion);

        return $free;
    }

    /**
     * enregistrement d'une réservation pour un RV; la confirmation devra suivre.
     *
     * @param $post : les informations provenant du formulaire de réservation du RV
     *
     * @return string: l'adresse mail déclarée encryptée en md5
     */
    public function saveRV($post)
    {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        if ($this->isFreeRV($id)) {
            $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
            $sql = 'UPDATE '.PFX.'thotRv ';
            $sql .= 'SET nom=:nom, prenom=:prenom, email=:email, dateHeure=NOW(), md5conf=:md5conf ';
            $sql .= 'WHERE id=:id ';
            $requete = $connexion->prepare($sql);
            $md5conf = md5($post['email'].time());
            $data = array(
                ':nom' => $post['nom'],
                ':prenom' => $post['prenom'],
                ':email' => $post['email'],
                ':md5conf' => $md5conf,
                ':id' => $id,
            );
            $resultat = $requete->execute($data);

            self::deconnexionPDO($connexion);

            return $md5conf;
        }

        return;
    }

    /**
     * Confirmation du RV correspondant à un id donné.
     *
     * @param $id
     *
     * @return string : le token si l'opération a réussi sinon une chaîne vide
     */
    public function confirmRv($id)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'UPDATE '.PFX.'thotRv ';
        $sql .= "SET confirme='1' ";
        $sql .= "WHERE id='$id' ";
        $resultat = $connexion->exec($sql);
        if ($resultat) {
            $nb = 1;
        } else {
            $nb = 0;
        }
        self::deconnexionPDO($connexion);

        return $nb;
    }

    /**
     * mise à jour des demandes de RV non confirmées (fonction appelée à chaque entrée sur l'application).
     *
     * @param $heures : le délai de péremption en heures
     */
    public function refreshTableRv($heures)
    {
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        // remise à zéro des lignes périmées depuis plus de 4 heures et qui n'ont jamais été confirmées
        $sql = "UPDATE didac_thotRv SET md5conf = Null WHERE (dateHeure < NOW() - INTERVAL $heures HOUR AND confirme = 0) ";
        $connexion->exec($sql);
        self::deconnexionPDO($connexion);
    }

    // fonctions pour la gestion des e-docs

    /**
    * retourne la liste des e-docs disponibles pour un élève dont on fournit le matricules
    * @param $matricule
    *
    * @return array
    */
    public function listeEdocs($matricule){
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT matricule, edoc, date ';
        $sql .= 'FROM '.PFX.'thotEdocs ';
        $sql .= "WHERE matricule = '$matricule' ";

        $liste = array();
        $resultat = $connexion->query($sql);
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $matricule = $ligne['matricule'];
                $edoc = $ligne['edoc'];
                $date = $this->datePhp($ligne['date']);
                $liste[$matricule][] = array('date'=>$date, 'doc'=>$edoc);
                }
        }
        self::deconnexionPDO($connexion);

        return $liste;
    }

    /**
    * retourne la date déclarée pour un e-doc donné pour un élève donné
    *
    * @param $matricule
    * @param $typeEdoc (pia, competences)
    *
    * @return string
    */
    public function getDocDate($matricule, $typeDoc){
        $connexion = self::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT date FROM '.PFX.'thotEdocs ';
        $sql .= "WHERE matricule='$matricule' AND edoc='$typeDoc' ";
        $resultat = $connexion->query($sql);
        $date = Null;
        if ($resultat) {
            $ligne = $resultat->fetch();
            $date = $ligne['date'];
        }
        self::deconnexionPDO($connexion);

        return self::datePHP($date);
    }

}
