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

    // /**
    //  * supprimer tous les partages d'un fichier dont on fournit le path, le fileName et l'acronyme de l'utilisateur.
    //  *
    //  * @param $path
    //  * @param $fileName
    //  * @param $acronyme
    //  *
    //  * @return int : le nombre d'effacements dans la BD
    //  */
    // public function delAllShares($path, $fileName, $acronyme)
    // {
    //     $id = $this->findFileId($path, $fileName, $acronyme);
    //
    //     $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
    //     $sql = 'DELETE FROM '.PFX.'thotShares ';
    //     $sql .= "WHERE id='$id' ";
    //     $resultat = $connexion->exec($sql);
    //     Application::DeconnexionPDO($connexion);
    //
    //     return $resultat;
    // }

    // /**
    //  * Enregistre un fichier dans la BD.
    //  *
    //  * @param $path : le chemin vers le fichier
    //  * @param $fileName : le nom du fichier
    //  * @param $acronyme : l 'acronyme du propriétaire
    //  *
    //  * @return int : l'id de l'enregistrement (ou 0 si pas d'enregistrement)
    //  */
    // public function saveInBD($path, $fileName, $acronyme)
    // {
    //     $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
    //     $sql = 'INSERT IGNORE INTO '.PFX.'thotFiles ';
    //     $sql .= 'SET acronyme=:acronyme, path=:path, fileName=:fileName ';
    //     $requete = $connexion->prepare($sql);
    //
    //     $data = array(':acronyme' => $acronyme, ':path' => $path, ':fileName' => $fileName);
    //     $resultat = $requete->execute($data);
    //     $id = $connexion->lastInsertId();
    //     Application::DeconnexionPDO($connexion);
    //
    //     return $id;
    // }

    // /**
    //  * Enregistre la création d'un nouveau répertoire dans la BD.
    //  *
    //  * @param $activeDir : le répertoire dans lequel se fait la création
    //  * @param $dirName : le nom du répertoire
    //  * @param $acronyme : l'acronyme de l'utilisateur
    //  *
    //  * @return bool
    //  */
    // public function dirInBD($activeDir, $dirName, $acronyme)
    // {
    //     $path = $activeDir.$dirName;
    //     $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
    //     $sql = 'INSERT IGNORE INTO '.PFX.'thotFiles ';
    //     $sql .= 'SET acronyme=:acronyme, path=:path, fileName=:fileName ';
    //     $requete = $connexion->prepare($sql);
    //
    //     $data = array(':acronyme' => $acronyme, ':path' => $path, ':fileName' => '');
    //     $resultat = $requete->execute($data);
    //
    //     $id = $connexion->lastInsertId();
    //     Application::DeconnexionPDO($connexion);
    //
    //     return $id;
    // }

    // /**
    //  * retourne la liste des partages de l'utilisateur indiqué.
    //  *
    //  * @param $acronyme : acronyme de l'utilisateur
    //  *
    //  * @return array
    //  */
    // public function listUserShares($acronyme)
    // {
    //     $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
    //     $sql = 'SELECT f.id, path, fileName, commentaire, type, s.groupe, destinataire, ';
    //     $sql .= 'el.nom AS nomEleve, el.prenom AS prenomEleve, el.classe, p.nom AS nomProf, p.prenom AS prenomProf ';
    //     $sql .= 'FROM '.PFX.'thotFiles AS f ';
    //     $sql .= 'JOIN '.PFX.'thotShares AS s ON f.id = s.id ';
    //     $sql .= 'LEFT JOIN '.PFX.'eleves AS el ON el.matricule = destinataire ';
    //     $sql .= 'LEFT JOIN '.PFX.'profs AS p ON p.acronyme = destinataire ';
    //     $sql .= "WHERE f.acronyme = '$acronyme' ";
    //     $sql .= 'ORDER BY path, fileName, type, groupe, destinataire ';
    //     $resultat = $connexion->query($sql);
    //     $liste = array();
    //     if ($resultat) {
    //         $resultat->setFetchMode(PDO::FETCH_ASSOC);
    //         while ($ligne = $resultat->fetch()) {
    //             $type = $ligne['type'];
    //             $id = $ligne['id'];
    //             $liste[$type][$id] = $ligne;
    //         }
    //     }
    //     Application::DeconnexionPDO($connexion);
    //
    //     return $liste;
    // }

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
}
