<?php

class Files
{
    public function __construct()
    {
        setlocale(LC_ALL, 'fr_FR.utf8');
    }

    /**
     * Effacement complet d'un répertoire et des fichiers/répertoires contenus.
     *
     * @param $dir : le répertoire à effacer
     *
     * @return bool : 1 si OK, 0 si pas OK
     */
    public function delTree($dir)
    {
        $files = glob($dir.'*', GLOB_MARK);
        $resultat = true;
        foreach ($files as $file) {
            if (substr($file, -1) == '/') {
                if ($resultat == true) {
                    $resultat = $this->delTree($file);
                }
            } else {
                $resultat = unlink($file);
            }
        }

        if (is_dir($dir) && ($resultat == true)) {
            $resultat = rmdir($dir);
        }

        return ($resultat == true) ? 1 : 0;
    }

    /**
     * recherche de l'id d'un fichier dont on fournit le nom et le path.
     *
     * @param $fileName : le nom du fichier
     * @param $path : le path
     * @param $acronyme : l'abréviation de l'utilisateur actif
     *
     * @return is_integer
     */
    public function findFileId($path, $fileName, $acronyme)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT id FROM '.PFX.'thotFiles ';
        $sql .= "WHERE acronyme='$acronyme' AND path='$path' AND fileName='$fileName' ";
        $resultat = $connexion->query($sql);
        $id = null;
        if ($resultat) {
            $ligne = $resultat->fetch();
            $id = $ligne['id'];
        }

        return $id;
    }

    /**
     * retrouve le path à partir du fileId d'un partage de répertoire.
     *
     * @param $fileId : identifiant du répertoire dans la BD
     *
     * @return string
     */
    public function getPathByFileId($fileId)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT path ';
        $sql .= 'FROM '.PFX.'thotFiles ';
        $sql .= 'WHERE fileId=:fileId ';
        $requete = $connexion->prepare($sql);
        $path = '';
        if (is_numeric($fileId)) {
            $data = array(':fileId' => $fileId);
            $resultat = $requete->execute($data);
            if ($resultat) {
                $requete->setFetchMode(PDO::FETCH_ASSOC);
                $ligne = $requete->fetch();
                $path = $ligne['path'];
            }
        } else {
            die('bad fileId');
        }

        Application::DeconnexionPDO($connexion);

        return $path;
    }

    /**
     * Enregistrement du partage d'un fichier.
     *
     * @param $post : contenu du formulaire
     *
     * @return int : nombre d'enregistrements de partage
     */
    public function share($post, $acronyme)
    {
        $fileName = $post['fileName'];
        $path = $post['path'];
        $type = $post['type'];
        $groupe = $post['groupe'];
        $commentaire = $post['commentaire'];
        $tous = isset($post['TOUS']) ? $post['TOUS'] : null;
        $membres = isset($post['membres']) ? $post['membres'] : null;

        $id = $this->findFileId($path, $fileName, $acronyme);

        $resultat = null;
        if ($id != 0) {
            $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
            // enregistrer les partages
            $sql = 'INSERT IGNORE INTO '.PFX.'thotShares ';
            $sql .= 'SET id=:id, type=:type, groupe=:groupe, commentaire=:commentaire, destinataire=:destinataire ';
            $requete = $connexion->prepare($sql);
            $resultat = 0;
            $data = array(':id' => $id, ':type' => $type, ':groupe' => $groupe, ':commentaire' => $commentaire);
            if ($tous != null) {
                $data[':destinataire'] = 'all';
                $resultat = $requete->execute($data);
            } else {
                if ($membres != null) {
                    foreach ($membres as $unMembre) {
                        $data[':destinataire'] = $unMembre;
                        $resultat += $requete->execute($data);
                    }
                }
            }
            Application::DeconnexionPDO($connexion);
        }

        return $resultat;
    }

    /**
     * retourne le nombre de partages d'un fichier dont on fournit l'identifiant en BD.
     *
     * @param $id
     *
     * @return int
     */
    public function nbShares($id)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT count(*) AS nb ';
        $sql .= 'FROM '.PFX.'thotShares ';
        $sql .= "WHERE id='$id' ";
        $resultat = $connexion->query($sql);
        $nb = 0;
        if ($resultat) {
            $ligne = $resultat->fetch();
            $nb = $ligne['nb'];
        }
        Application::DeconnexionPDO($connexion);

        return $nb;
    }

    /**
     * retourne la liste des fileIds qui sont accessibles à l'élève dont on fournit le matricule, la classe, le niveau d'étude et la liste des cours.
     *
     * @param $matricule
     *
     * @return array
     */
    public function getSharedFiles($matricule, $classe, $niveau, $listeCoursString)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT files.fileId ';
        $sql .= 'FROM '.PFX.'thotShares AS share ';
        $sql .= 'JOIN '.PFX.'thotFiles AS files ON files.fileId = share.fileId ';
        $sql .= "WHERE destinataire = '$matricule' ";
        $sql .= "OR groupe = '$classe' ";
        $sql .= "OR groupe = 'niveau' AND destinataire = '$niveau' ";
        $sql .= "OR groupe IN ($listeCoursString) ";
        $sql .= "OR groupe = 'ecole' ";

        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $fileId = $ligne['fileId'];
                array_push($liste, $fileId);
            }
        };

        Application::DeconnexionPDO($connexion);

        return $liste;
    }

    /**
     * retourne la liste des documents partagés avec l'élève dont on fournit le matricule.
     *
     * @param $matricule
     * @param $classe
     * @param $niveau
     * @param $listeCoursString : ses cours (chaînes séparées par des virgules)
     *
     * @return array : la liste des documents classés par école, niveau, classe, coursGrp
     */
    public function listeElevesShares($matricule, $classe, $niveau, $listeCoursString)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT files.fileId, shareId, type, groupe, destinataire, commentaire, path, fileName, ';
        $sql .= 'files.acronyme, nom, prenom, sexe, libelle ';
        $sql .= 'FROM '.PFX.'thotShares AS share ';
        $sql .= 'JOIN '.PFX.'thotFiles AS files ON files.fileId = share.fileId ';
        $sql .= 'LEFT JOIN '.PFX.'profs AS dp ON dp.acronyme = files.acronyme ';
        $sql .= 'LEFT JOIN didac_cours AS dc ON SUBSTR(share.groupe, 1, LOCATE("-",share.groupe)-1) = dc.cours ';
        $sql .= "WHERE destinataire = '$matricule' ";
        $sql .= "OR groupe = '$classe' ";
        $sql .= "OR groupe = 'niveau' AND destinataire = '$niveau' ";
        $sql .= "OR groupe IN ($listeCoursString) ";
        $sql .= "OR groupe = 'ecole' ";

        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $type = $ligne['type'];
                $fileId = $ligne['fileId'];
                $liste[$type][$fileId] = $ligne;
            }
        }
        Application::DeconnexionPDO($connexion);

        return $liste;
    }

    /**
     * renvoie la liste des documents fournie en arguments en triant les documents par cours.
     *
     * @param $listeDocuments : array
     *
     * @return array
     */
    public function sortByCours($listeDocs)
    {
        $liste = array();
        foreach ($listeDocs as $fileId => $dataDoc) {
            $nomCours = $dataDoc['libelle'];
            $liste[$nomCours][$fileId] = $dataDoc;
        }
        ksort($liste);

        return $liste;
    }

    /**
     * retourne la liste des 'id' des documents auxquels un élève a accès.
     *
     * @param $matricule
     * @param $classe
     * @param $niveau
     * @param $listeCoursString : ses cours (chaînes séparées par des virgules)
     *
     * @return array : le tableau de la liste des id's
     */
    public function listeDocsEleve($matricule, $classe, $niveau, $listeCoursString)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT share.fileId ';
        $sql .= 'FROM '.PFX.'thotShares AS share ';
        $sql .= 'JOIN '.PFX.'thotFiles AS files ON files.fileId = share.fileId ';
        $sql .= 'LEFT JOIN '.PFX.'profs AS dp ON dp.acronyme = files.acronyme ';
        $sql .= 'LEFT JOIN didac_cours AS dc ON SUBSTR(share.groupe, 1, LOCATE("-",share.groupe)-1) = dc.cours ';
        $sql .= "WHERE destinataire = '$matricule' ";
        $sql .= "OR groupe = '$classe' ";
        $sql .= "OR groupe = 'niveau' AND destinataire = '$niveau' ";
        $sql .= "OR groupe IN ($listeCoursString) ";
        $sql .= "OR groupe = 'ecole' ";

        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $fileId = $ligne['fileId'];
                $liste[$fileId] = $fileId;
            }
        }
        Application::DeconnexionPDO($connexion);

        return $liste;
    }

    /**
     * retrouver le path et le fileName d'un fichier dont on fournit l'identifiant.
     *
     * @param $id : l'identifiant du fichier dans la BD
     *
     * @return array ('path'=> $path, 'fileName'=>$fileName)
     */
    public function getFileData($fileId)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT path, fileName, acronyme ';
        $sql .= 'FROM '.PFX.'thotFiles ';
        $sql .= "WHERE fileId='$fileId' ";
        $resultat = $connexion->query($sql);
        $data = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $ligne = $resultat->fetch();
            $data = array(
                    'path' => $ligne['path'],
                    'fileName' => $ligne['fileName'],
                    'acronyme' => $ligne['acronyme'],
                );
        }
        Application::DeconnexionPDO($connexion);

        return $data;
    }

    /**
     * retourne la liste des documents attendus pour chaque cours de la liste fournie en paramètre
     * pour l'utilisateur dont on donne le matricule.
     *
     * @param $listeCoursSTring : string
     * @param $matricule
     *
     * @return array
     */
    public function listeDocumentsCasiers($listeCoursString, $matricule)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT tt.idTravail, tt.acronyme, nom, prenom, coursGrp, tt.titre, consigne, dateDebut, dateFin, tt.statut, ';
        $sql .= 'cote, max, remarque, evaluation, statutEleve, libelle, nbheures ';
        $sql .= 'FROM '.PFX.'thotTravaux AS tt ';
        $sql .= 'JOIN '.PFX.'thotTravauxRemis AS tr ON tt.idTravail = tr.idTravail ';
        $sql .= 'JOIN '.PFX.'profs AS dp ON dp.acronyme = tt.acronyme ';
        $sql .= 'JOIN '.PFX."cours AS dc ON (dc.cours = SUBSTR(coursGrp, 1, LOCATE('-', coursGrp)-1)) ";
        $sql .= "WHERE coursGrp IN ($listeCoursString) AND matricule='$matricule' ";
        $sql .= 'ORDER BY nbheures, libelle ';
        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $coursGrp = $ligne['coursGrp'];
                $idTravail = $ligne['idTravail'];
                $acronyme = $ligne['acronyme'];
                $fileInfos = $this->getFileInfos($matricule, $idTravail, $acronyme);
                $ligne['dateDebut'] = Application::datePHP($ligne['dateDebut']);
                $ligne['dateFin'] = Application::datePHP($ligne['dateFin']);
                $ligne['fileInfos'] = $fileInfos;
                $libelle = sprintf('%s : %dh', $ligne['libelle'], $ligne['nbheures']);
                if (!(isset($liste[$coursGrp]))) {
                    $liste[$coursGrp] = array('libelle' => $libelle, 'travaux' => array());
                }
                $liste[$coursGrp]['travaux'][$idTravail] = $ligne;
            }
        }
        Application::DeconnexionPDO($connexion);

        return $liste;
    }

    /**
     * recherche les détails relatifs à un fichier déposé par l'élève $matricule pour un $idTravail donné.
     *
     * @param $matricule
     * @param $idTravail
     *
     * @return array
     */
    private function getFileInfos($matricule, $idTravail, $acronyme)
    {
        $ds = DIRECTORY_SEPARATOR;
        $dir = INSTALL_ZEUS.$ds.'upload'.$ds.$acronyme.$ds.'#thot'.$ds.$idTravail.$ds.$matricule;
        $infos = array('fileName' => null, 'size' => '', 'dateRemise' => 'Non remis');
        $files = @scandir($dir);
        // ce répertoire est-il défini?
        if ($files != null) {
            $files = array_diff($files, array('..', '.'));
            // le premier fichier significatif est le numéro 2 (.. et . ont été supprimés)
            if (isset($files[2])) {
                $file = $files[2];
                $infos = array(
                    'fileName' => $file,
                    'size' => $this->unitFilesize(filesize($dir.'/'.$file)),
                    'dateRemise' => strftime('%x %X', filemtime($dir.'/'.$file)),
                );
            }
        }

        return $infos;
    }

    /**
     * convertit les tailles de fichiers en valeurs usuelles avec les unités.
     *
     * @param $bytes : la taille en bytes
     *
     * @return string : la taille en unités usuelles
     */
    public function unitFilesize($size)
    {
        $precision = ($size > 1024) ? 2 : 0;
        $units = array('octet(s)', 'Ko', 'Mo', 'Go', 'To', 'Po', 'Eo', 'Zo', 'Yo');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;

        return number_format($size / pow(1024, $power), $precision, '.', ',').' '.$units[$power];
    }

    /**
     * retourne les détails d'un travail dont on fournit l'idTravail et le matricule de l'élève.
     *
     * @param $idTravail
     * @param $matricule
     *
     * @return array
     */
    public function getDetailsTravail($idTravail, $matricule)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT tt.idTravail, tt.acronyme, nom, prenom, coursGrp, tt.titre, consigne, dateDebut, dateFin, tt.statut, ';
        $sql .= 'cote, max, evaluation, remarque,  statutEleve, libelle, nbheures ';
        $sql .= 'FROM '.PFX.'thotTravaux AS tt ';
        $sql .= 'JOIN '.PFX.'thotTravauxRemis AS tr ON tt.idTravail = tr.idTravail ';
        $sql .= 'JOIN '.PFX.'profs AS dp ON dp.acronyme = tt.acronyme ';
        $sql .= 'JOIN '.PFX."cours AS dc ON (dc.cours = SUBSTR(coursGrp, 1, LOCATE('-', coursGrp)-1)) ";
        $sql .= 'WHERE tt.idTravail=:idTravail AND matricule=:matricule ';
        $requete = $connexion->prepare($sql);
        $data = array(':idTravail' => $idTravail, ':matricule' => $matricule);
        $details = array();
        $resultat = $requete->execute($data);
        if ($resultat) {
            $requete->setFetchMode(PDO::FETCH_ASSOC);
            $details = $requete->fetch();
            $acronyme = $details['acronyme'];
            $details['dateDebut'] = Application::datePHP($details['dateDebut']);
            $details['dateFin'] = Application::datePHP($details['dateFin']);
            $fileInfos = $this->getFileInfos($matricule, $idTravail, $acronyme);
            $details['fileInfos'] = $fileInfos;
        }

        Application::DeconnexionPDO($connexion);

        return $details;
    }

    /**
     * vérifie que l'élève $matricule est effectivement affecté à un travail dont on fournit l'idTravail.
     *
     * @param $matricule
     * @param $idTravail
     *
     * @return bool
     */
    public function verifEleve4Travail($matricule, $idTravail)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT coursGrp ';
        $sql .= 'FROM '.PFX.'thotTravaux ';
        $sql .= 'WHERE idTravail=:idTravail ';
        $requete = $connexion->prepare($sql);
        $requete->bindValue(':idTravail', $idTravail, PDO::PARAM_INT);
        $verif = false;
        $resultat = $requete->execute();
        if ($resultat) {
            $ligne = $requete->fetch();
            $coursGrp = $ligne['coursGrp'];
            $sql = 'SELECT matricule ';
            $sql .= 'FROM '.PFX.'elevesCours ';
            $sql .= 'WHERE coursGrp =:coursGrp';
            $requete = $connexion->prepare($sql);
            $requete->bindValue(':coursGrp', $coursGrp, PDO::PARAM_STR);
            $resultat = $requete->execute();
            if ($resultat) {
                $requete->setFetchMode(PDO::FETCH_ASSOC);
                while ($ligne = $requete->fetch()) {
                    $leMatricule = $ligne['matricule'];
                    $liste[] = $leMatricule;
                }
                $verif = in_array($matricule, $liste);
            }
        }
        Application::DeconnexionPDO($connexion);

        return $verif;
    }

    /**
     * enregistre une remarque de l'élève $matricule pour un travail dont on fournit l'$idTravail.
     *
     * @param $idTravail
     * @param $matricule
     * @param $matricule
     *
     * @return int : nombre d'enregistrements réussis (0 ou 1)
     */
    public function saveRemarqueEleve($remarque, $idTravail, $matricule)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'INSERT INTO '.PFX.'thotTravauxRemis ';
        $sql .= 'SET remarque=:remarque, matricule=:matricule, idTravail=:idTravail ';
        $sql .= 'ON DUPLICATE KEY UPDATE remarque=:remarque ';
        $requete = $connexion->prepare($sql);
        $data = array(':remarque' => $remarque, ':matricule' => $matricule, ':idTravail' => $idTravail);
        $resultat = $requete->execute($data);

        $nb = ($resultat > 0) ? 1 : 0;

        Application::DeconnexionPDO($connexion);

        return $nb;
    }

    /**
     * marque le travail $idTravail comme remis par l'élève $matricule.
     *
     * @param $idTravail : identifiant du travail
     * @param $matricule : identifiant de l'élève
     */
    public function travailRemis($idTravail, $matricule, $remis = true)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'INSERT INTO '.PFX.'thotTravauxRemis ';
        if ($remis == true) {
            $sql .= 'SET remis = 1, idTravail=:idTravail, matricule=:matricule ';
        } else {
            $sql .= 'SET remis = 0, idTravail=:idTravail, matricule=:matricule ';
        }
        $sql .= 'ON DUPLICATE KEY UPDATE ';
        if ($remis == true) {
            $sql .= 'remis = 1 ';
        } else {
            $sql .= 'remis = 0 ';
        }

        $requete = $connexion->prepare($sql);
        $data = array(':matricule' => $matricule, ':idTravail' => $idTravail);
        $resultat = $requete->execute($data);
        Application::DeconnexionPDO($connexion);

        return;
    }

    /**
     * Supprime un fichier $fileName correspondant à un travail $idTravail appartenant à $acronyme pour l'élève $matricule.
     *
     * @param $idTravail
     * @param $acronyme
     * @param $matricule
     * @param $fileName
     *
     * @return int : nombre de fichiers supprimés
     */
    public function delTravailFile($acronyme, $idTravail, $matricule, $fileName)
    {
        Application::afficher(array($acronyme, $idTravail, $matricule, $fileName), true);
    }
}
