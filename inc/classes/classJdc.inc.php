<?php

class Jdc
{
    /**
     * constructeur de l'objet jdc.
     */
    public function __construct()
    {
    }

    /**
     * renvoie la liste d'événements entres deux dates start et end.
     *
     * @param int $from : date de début = timestamp Unix en millisecondes
     * @param int $to   : date de fin = timestamp Unix en millisecondes
     *
     * @return string liste json
     */
    public function retreiveEvents($start, $end, $niveau, $classe, $matricule, $listeCoursString, $redacteur=Null)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT id, destinataire, idCategorie, type, proprietaire, redacteur, title, enonce, class, allDay, startDate, endDate ';
        $sql .= 'FROM '.PFX.'thotJdc ';
        $sql .= 'WHERE startDate BETWEEN :start AND :end ';
        if ($redacteur == Null) {
            $sql .= "AND destinataire in ($listeCoursString) OR destinataire = :classe ";
            $sql .= "OR destinataire = :matricule OR destinataire = 'all' OR destinataire = :niveau ";
        }
        else {
            $sql .= 'AND redacteur = :redacteur ';
        }
        $requete = $connexion->prepare($sql);

        if ($redacteur == Null) {
            $requete->bindParam(':classe', $classe, PDO::PARAM_STR, 6);
            $requete->bindParam(':matricule', $matricule, PDO::PARAM_INT);
            $requete->bindParam(':niveau', $niveau, PDO::PARAM_INT);
        }
        else {
            $requete->bindParam(':redacteur', $redacteur, PDO::PARAM_INT);
        }
        $requete->bindParam(':start', $start, PDO::PARAM_STR, 20);
        $requete->bindParam(':end', $end, PDO::PARAM_STR, 20);

        $resultat = $requete->execute();
        $liste = array();
        if ($resultat) {
            $requete->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $requete->fetch()) {
                $destinataire = $ligne['destinataire'];
                preg_match('/[0-9].*:(.*)-[0-9]*/', $destinataire, $matches);
                $cours = $matches[1];
                $liste[] = array(
                    'id' => $ligne['id'],
                    'title' => $ligne['title'],
                    'enonce' => mb_strimwidth(strip_tags(html_entity_decode($ligne['enonce'])), 0, 200,'...'),
                    'className' => 'cat_'.$ligne['idCategorie'],
                    'start' => $ligne['startDate'],
                    'end' => $ligne['endDate'],
                    'allDay' => ($ligne['allDay'] != 0),
                    'destinataire' => $destinataire,
                    'cours' => $cours
                    );
            }
        }
        Application::DeconnexionPDO($connexion);

        return $liste;
    }

    /**
     * retrouve une notification dont on fournit l'identifiant.
     *
     * @param int $itemId : l'identifiant dans la BD
     *
     * @return array
     */
    public function getTravail($itemId)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = "SELECT destinataire, type, proprietaire, redacteur, title, enonce, class, id, DATE_FORMAT(startDate,'%d/%m/%Y') AS startDate, ";
        $sql .= "DATE_FORMAT(startDate,'%H:%i') AS heure, endDate, TIMEDIFF(endDate, startDate) AS duree, allDay, DATE_FORMAT(lastModif, '%d/%m/%Y %H:%i') AS lastModif, ";
        $sql .= 'jdc.idCategorie, categorie, sexe, nom, prenom, libelle, nbheures, nomCours ';
        $sql .= 'FROM '.PFX.'thotJdc AS jdc ';
        $sql .= 'LEFT JOIN '.PFX.'profs AS dp ON dp.acronyme = jdc.proprietaire ';
        $sql .= 'JOIN '.PFX.'thotJdcCategories AS cat ON cat.idCategorie = jdc.idCategorie ';
        $sql .= 'LEFT JOIN '.PFX."cours AS dc ON cours = SUBSTR(destinataire,1,LOCATE('-',destinataire)-1) ";
        $sql .= 'LEFT JOIN '.PFX.'profsCours AS dpc ON dpc.coursGrp = destinataire ';
        $sql .= 'WHERE id= :itemId ';
        $requete = $connexion->prepare($sql);

        $requete->bindParam(':itemId', $itemId, PDO::PARAM_INT);

        $travail = array();
        $resultat = $requete->execute();
        if ($resultat) {
            $requete->setFetchMode(PDO::FETCH_ASSOC);
            $travail = $requete->fetch();

            if($travail != Null) {
                if (isset($travail['sexe'])) {
                    if ($travail['sexe'] == 'F') {
                        $nom = 'Mme ';
                    } else {
                        $nom = 'M. ';
                        }
                    }
                    else $nom = '';

                if ($travail['prenom'] != '') {
                    $nom .= mb_substr($travail['prenom'], 0, 1, 'UTF-8').'.';
                }
                $travail['nom'] = $nom.' '.$travail['nom'];

                $travail['heure'] = date('H:i', strtotime($travail['heure']));
                $travail['duree'] = date('H:i', strtotime($travail['duree']));
                if ($travail['allDay'] == 0) {
                    unset($travail['allDay']);
                }
            }
        }
        Application::DeconnexionPDO($connexion);

        return $travail;
    }

    /**
     * retrouve les PJ liées au JDC dont on fournit l'identifiant $idJdc
     *
     * @param int $idJdc : l'identifiant du journal de classe
     *
     * @return array : la liste des fichiers joints
     */
    public function getPj($idJdc) {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT tjdc.shareId, shares.fileId, path, fileName ';
        $sql .= 'FROM '.PFX.'thotJdcPJ AS tjdc ';
        $sql .= 'JOIN '.PFX.'thotShares AS shares ON shares.shareId = tjdc.shareId ';
        $sql .= 'JOIN '.PFX.'thotFiles AS files ON files.fileId = shares.fileId ';
        $sql .= 'WHERE idJdc = :idJdc ';
        $requete = $connexion->prepare($sql);

        $requete->bindParam(':idJdc', $idJdc, PDO::PARAM_INT);

        $liste = array();
        $resultat = $requete->execute();
        if ($resultat) {
            $requete->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $requete->fetch()){
                $shareId = $ligne['shareId'];
                $liste[$shareId] = $ligne;
            }
        }

        Application::deconnexionPDO($connexion);

        return $liste;
    }

    /**
     * vérification du caractère "éditable" d'une note au JDC rédigée par un élève
     * la note est éditable si elle vient du formulaire 'write', quelle a été rédigée par l'élève $matricule
     * et que le prof ne l'a pas encore approuvée
     *
     * @param array $travail
     * @param int $redacteur : matricule de l'élève rédacteur
     * @param string $origine
     *
     * @return bool
     */
    public function editable($travail, $redacteur, $origine) {
        return ($travail['proprietaire'] == '') && ($travail['matricule'] = $redacteur) && ($origine == 'write');
    }

    /**
     * retourne les différentes catégories de travaux disponibles (interro, devoir,...).
     *
     * @param void
     *
     * @return array
     */
    public function categoriesTravaux()
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT idCategorie, categorie ';
        $sql .= 'FROM '.PFX.'thotJdcCategories ';
        $sql .= 'ORDER BY ordre ';
        $resultat = $connexion->query($sql);
        $liste = array();
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $id = $ligne['idCategorie'];
                $liste[$id] = $ligne;
            }
        }
        Application::DeconnexionPDO($connexion);

        return $liste;
    }

    /**
     * retourne true si l'élève dont on indique le matricule est en charge du JDC à la date actuelle
     *
     * @param int $matricule
     *
     * @return boolean
     */
    public function isChargeJDC($matricule) {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT dateDebut, dateFin, NOW() AS today ';
        $sql .= 'FROM '.PFX.'thotJdcEleves ';
        $sql .= 'WHERE matricule = :matricule ';

        $requete = $connexion->prepare($sql);

        $requete->bindParam(':matricule', $matricule, PDO::PARAM_INT);
        $enCharge = false;
        $resultat = $requete->execute();
        if ($resultat) {
            $ligne = $requete->fetch();
            $dateDebut = isset($ligne['dateDebut']) ? $ligne['dateDebut'] : Null;
            $dateFin = isset($ligne['dateFin']) ? $ligne['dateFin'] : Null;
            $today = $ligne['today'];
            if (($dateDebut != Null && $dateFin != Null) && (($dateDebut <= $today) && ($dateFin >= $today)))
                $enCharge = true;
        }

        Application::DeconnexionPDO($connexion);

        return $enCharge;
    }

    /**
     * renvoie la liste des heures de cours données dans l'école.
     *
     * @param void
     *
     * @return array
     */
    public function lirePeriodesCours()
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT debut, fin ';
        $sql = "SELECT DATE_FORMAT(debut,'%H:%i') as debut, DATE_FORMAT(fin,'%H:%i') as fin ";
        $sql .= 'FROM '.PFX.'presencesHeures ';
        $sql .= 'ORDER BY debut, fin';

        $resultat = $connexion->query($sql);
        $listePeriodes = array();
        $periode = 1;
        if ($resultat) {
            while ($ligne = $resultat->fetch()) {
                $debut = $ligne['debut'];
                $fin = $ligne['fin'];
                $listePeriodes[$periode++] = array('debut' => $debut, 'fin' => $fin);
            }
        }
        Application::deconnexionPDO($connexion);

        return $listePeriodes;
    }

    /**
     * renvoie l'heure de la période de cours la plus proche de l'heure passée en argument
     *
     * @param string $heure
     *
     * @return string
     */
    public function heureLaPlusProche($heure){
        $listePeriodes = $this->lirePeriodesCours();
        $time = explode(':', $heure);
        $time = mktime($heure[0], $heure[1]);

        $n = 1;
        while (($listePeriodes[$n]['fin'] < $heure) && ($n < count($listePeriodes))) {
            $n++;
        }
        $timeDebut = explode(':', $listePeriodes[$n]['debut']);
        $timeFin = explode(':', $listePeriodes[$n]['fin']);

        if (((float) $time - (float) $timeDebut) > ((float) $timeFin - (float) $time))
            return $listePeriodes[$n]['debut'];
            else return $listePeriodes[$n]['fin'];
    }

    /**
     * enregistre une notification au JDC.
     *
     * @param array $post : tout le contenu du formulaire
     * @param int $matricule : matricule de l'élève
     *
     * @return int: nombre d'enreigstrements résussis (0 ou 1)
     */
    public function saveJdc($post, $matricule)
    {
        $id = isset($post['id']) ? $post['id'] : null;
        $destinataire = isset($post['destinataire']) ? $post['destinataire'] : null;
        $type = isset($post['type']) ? $post['type'] : null;
        $date = Application::dateMysql($post['date']);
        $duree = isset($post['duree']) ? $post['duree'] : null;
        $allDay = isset($post['journee']) ? 1 : 0;

        if ($allDay == 0) {
            $heure = $post['heure'];
            $startDate = $date.' '.$heure;
            // $duree peut être exprimé en minutes ou en format horaire hh:mm
            $duree = $post['duree'];
            if (!is_numeric($duree)) {
                if (strpos($duree,':') > 0) {
                    // c'est sans doute le format hh::mm
                    $duree = explode(':', $duree);
                    $duree = $duree[0] * 60 + $duree[1];
                }
                else $duree = 0;
            }
            // if (($duree <= 20) || ($duree > 400)) {
            //     $duree = 0;
            // }  // 20 minutes en cas d'erreur et pour la lisibilité

            $endDate = new DateTime($startDate);
            $endDate->add(new DateInterval('PT'.$duree.'M'));
            $endDate = $endDate->format('Y-m-d H:i');
        } else {
            $duree = null;
            $startDate = $date.' '.'00:00';
            $endDate = $startDate;
        }

        $titre = $post['titre'];
        $categorie = $post['categorie'];
        $class = $categorie;
        $enonce = $post['enonce'];

        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        if ($id == null) {
            // nouvel enregistrement
            $sql = 'INSERT INTO '.PFX.'thotJdc ';
            $sql .= 'SET destinataire=:destinataire, type=:type, proprietaire=Null, redacteur=:redacteur, idCategorie=:categorie, ';
            $sql .= 'title=:titre, enonce=:enonce, startDate=:startDate, endDate=:endDate, allDay=:allDay ';
        } else {
            // simple mise à jour
            $sql = 'UPDATE '.PFX.'thotJdc ';
            $sql .= 'SET destinataire=:destinataire, type=:type, proprietaire=Null, redacteur=:redacteur, idCategorie=:categorie, ';
            $sql .= 'title=:titre, enonce=:enonce, startDate=:startDate, endDate=:endDate, allDay=:allDay ';
            $sql .= "WHERE id= $id ";
        }
        $requete = $connexion->prepare($sql);

        $data = array(
            ':destinataire' => $destinataire,
            ':type' => $type,
            ':redacteur' => $matricule,
            ':categorie' => $categorie,
            ':titre' => $titre,
            ':enonce' => $enonce,
            ':startDate' => $startDate,
            ':endDate' => $endDate,
            ':allDay' => $allDay,
            );

        $resultat = $requete->execute($data);
        if ($id == null) {
            $lastId = $connexion->lastInsertId();
        }
        Application::DeconnexionPDO($connexion);

        if ($id == null) {
            return $lastId;
        } elseif ($resultat > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Vérifie si la note d'identifiant $id a bien été rédigée par le rédacteur $matricule
     *
     * @param int $id : identifiant de la note au Jdc
     * @param int $matricule : de l'élève
     *
     * @return int l'identifiant s'il correspond au critère, sinon -1
     */
    public function verifIdRedacteur($id, $matricule) {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT id, redacteur ';
        $sql .= 'FROM '.PFX.'thotJdc ';
        $sql .= "WHERE id='$id' AND redacteur = '$matricule' ";

        $id = -1;
        $resultat = $connexion->query($sql);
        if ($resultat){
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $ligne = $resultat->fetch();
            $id = $ligne['id'];
        }

        Application::deconnexionPDO($connexion);

        return $id;
    }

    /**
     * suppression d'une notification au journal de classe.
     *
     * @param int $id : l'identifiant de l'enregistrement
     * @param int $redacteur : le matricule de l'élève (sécurité)
     *
     * @return boolean
     */
    public function deleteJdc($id, $redacteur)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'DELETE FROM '.PFX.'thotJdc ';
        $sql .= 'WHERE id = :id AND redacteur = :redacteur ';
        $requete = $connexion->prepare($sql);

        $requete->bindParam(':redacteur', $redacteur, PDO::PARAM_INT);
        $requete->bindParam(':id', $id, PDO::PARAM_INT);

        $resultat = $requete->execute();

        $sql = 'DELETE FROM '.PFX.'thotJdcLike ';
        $sql .= 'WHERE id = :id ';
        $requete = $connexion->prepare($sql);

        $requete->bindParam(':id', $id, PDO::PARAM_INT);
        $resultat = $requete->execute();

        Application::DeconnexionPDO($connexion);

        return $resultat;
    }

    /**
     * compte le nombre de likes et de dislikes pour le JDC dont on fournit l'identifiant
     *
     * @param int $id
     *
     * @return array 'like'=> nb, 'dislike' => nb
     */
    public function countLikes ($id) {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT matricule, jeLike, commentaire ';
        $sql .= 'FROM '.PFX.'thotJdcLike ';
        $sql .= 'WHERE  id = :id ';
        $requete = $connexion->prepare($sql);

        $requete->bindParam(':id', $id, PDO::PARAM_INT);

        $count = array('like' => 0, 'dislike' => 0);
        $resultat = $requete->execute();
        if ($resultat) {
            while ($ligne = $requete->fetch()){
                $vote = $ligne['jeLike'];
                if ($vote == 0)
                    $count['dislike']++;
                    else $count['like']++;
            }
        }
        Application::DeconnexionPDO($connexion);

        return $count;
    }

    /**
     * enregistre un vote dislike (0) ou like (1) par l'élève $matricule pour un JDC dont on fournit l'id
     *
     * @param int $id : identifiant de la note
     * @param int $matricule : de l'élève
     * @param int $vote : dislike = 0, like = 1
     *
     * @return boolean : l'enregistrement s'est bien passé?
     */
    public function saveLikes ($id, $matricule, $vote, $commentaire) {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'INSERT INTO '.PFX.'thotJdcLike ';
        $sql .= 'SET id = :id, matricule = :matricule, jeLike = :vote, commentaire = :commentaire ';
        $sql .= 'ON DUPLICATE KEY UPDATE jeLike = :vote, commentaire = :commentaire ';
        $requete = $connexion->prepare($sql);

        $requete->bindParam(':id', $id, PDO::PARAM_INT);
        $requete->bindParam(':matricule', $matricule, PDO::PARAM_INT);
        $requete->bindParam(':vote', $vote, PDO::PARAM_INT);
        $requete->bindParam(':commentaire', $commentaire, PDO::PARAM_STR, 80);

        $resultat = $requete->execute();

        Application::DeconnexionPDO($connexion);

        return $resultat;
    }

    /**
     * liste les problèmes liés aux "dislikes" pour un JDC dont on fournit l'id
     *
     * @param int $id
     *
     * @return array
     */
    public function listeDislikes ($id) {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT likeTbl.matricule, commentaire, nom, prenom, groupe ';
        $sql .= 'FROM '.PFX.'thotJdcLike AS likeTbl ';
        $sql .= 'LEFT JOIN '.PFX.'eleves AS de ON likeTbl.matricule = de.matricule ';
        $sql .= 'WHERE id = :id AND jeLike = 0 ';
        $requete = $connexion->prepare($sql);

        $requete->bindParam(':id', $id, PDO::PARAM_INT);

        $liste = array();
        $resultat = $requete->execute();
        if ($resultat) {
            $requete->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $requete->fetch()) {
                $matricule = $ligne['matricule'];
                $liste[$matricule] = $ligne;
            }
        }

        Application::DeconnexionPDO($connexion);

        return $liste;
    }

    /**
     * retrouve un commentaire de dislike pour la note dont on fournit l'id et pour l'élève $matricule
     *
     * @param int $id
     * @param int $matricule
     *
     * @return string
     */
    public function retreiveDislike($id, $matricule) {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT commentaire ';
        $sql .= 'FROM '.PFX.'thotJdcLike ';
        $sql .= 'WHERE id = :id AND matricule = :matricule AND jeLike = 0 ';
        $requete = $connexion->prepare($sql);

        $requete->bindParam(':id', $id, PDO::PARAM_INT);
        $requete->bindParam(':matricule', $matricule, PDO::PARAM_INT);

        $commentaire = '';
        $resultat = $requete->execute();
        if ($resultat) {
            $ligne = $requete->fetch();
            $commentaire = $ligne['commentaire'];
        }

        APPLICATION::deconnexionPDO($connexion);

        return $commentaire;
    }

    /**
     * retourne la liste des charges JDC pour la classe indiqué
     *
     * @param string $classe
     *
     * @return array
     */
    public function getChargesJDC($classe) {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT dtj.matricule, nom, prenom, dateDebut, dateFin, SUBSTR(NOW(), 1, 10) AS today ';
        $sql .= 'FROM '.PFX.'thotJdcEleves AS dtj ';
        $sql .= 'JOIN '.PFX.'eleves AS de ON de.matricule = dtj.matricule ';
        $sql .= 'WHERE dtj.matricule IN (SELECT matricule FROM '.PFX.'eleves WHERE groupe = :classe) ';
        $sql .= 'ORDER BY nom, prenom ';

        $requete = $connexion->prepare($sql);

        $requete->bindParam(':classe', $classe, PDO::PARAM_STR, 6);
        $liste = array();
        $resultat = $requete->execute();
        if ($resultat) {
            $requete->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $requete->fetch()) {
                $matricule = $ligne['matricule'];
                // cet élève est-il en charge du JDC durant la période?
                if (($ligne['dateDebut'] <= $ligne['today']) && ($ligne['dateFin'] >= $ligne['today']))
                    $ligne['selected'] = 'selected';

                $ligne['dateDebut'] = Application::datePHP($ligne['dateDebut']);
                $ligne['dateFin'] = Application::datePHP($ligne['dateFin']);
                $liste[$matricule] = $ligne;
            }

        Application::deconnexionPDO($connexion);

        return $liste;
        }
    }

    /**
     * renvoie les notes du JDC comprises entre la date "from" et la date "to"
     * en tenant compte des options d'impression: rien que les matières vues et/ou tout
     *
     * @param array $form : formulaire provenant de la boîte modale "modalPrintJDC"
     * @param string $acronyme : identifiant de l'utilisateur (sécurité)
     *
     * @return array
     */
    public function fromToJDCList($startDate, $endDate, $listeCours, $listeCategories) {
        $startDate = Application::dateMysql($startDate).' 00:00';
        $endDate = Application::dateMysql($endDate).' 23:59';

        $listeCoursString = is_array($listeCours) ? "'".implode("','", $listeCours)."'" : "'".$listeCours."'";
        $listeCategoriesString = is_array($listeCategories) ? implode(',', $listeCategories) : "'".$listeCategories."'";

        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = 'SELECT id, categorie, libelle, destinataire, title, enonce, startDate, endDate, dtjdc.idCategorie ';
        $sql .= 'FROM '.PFX.'thotJdc AS dtjdc ';
        $sql .= 'JOIN '.PFX.'thotJdcCategories AS cate ON cate.idCategorie = dtjdc.idCategorie ';
        $sql .= 'JOIN '.PFX."cours AS dc ON dc.cours = SUBSTR(destinataire, 1, LOCATE('-', destinataire)-1) ";
        $sql .= 'WHERE startDate >= :startDate AND endDate <= :endDate ';
        $sql .= "AND dtjdc.idCategorie IN (".$listeCategoriesString.") ";
        $sql .= 'AND destinataire IN ('.$listeCoursString.') ';
        $sql .= 'ORDER BY startDate, destinataire, dtjdc.idCategorie ';
        $requete = $connexion->prepare($sql);

        $requete->bindParam(':startDate', $startDate, PDO::PARAM_STR, 15);
        $requete->bindParam(':endDate', $endDate, PDO::PARAM_STR, 15);

        $liste = array();
        $resultat = $requete->execute();
        if ($resultat) {
            $requete->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $requete->fetch()){
                $id = $ligne['id'];
                $startDate = explode(' ', $ligne['startDate']);
                $endDate = explode(' ', $ligne['endDate']);
                if ($startDate == $endDate) {
                  $ligne['startDate'] = 'Toute la journée';
                }
                else {
                  $ligne['startDate'] = Application::datePHP($startDate[0]);
                }
                $ligne['startHeure'] = $startDate[1];
                $ligne['endDate'] = Application::datePHP($endDate[0]);
                $ligne['endHeure'] = $endDate[1];
                $ligne['enonce'] = strip_tags($ligne['enonce'], '<br><p><a>');
                $liste[$id] = $ligne;
            }

        Application::deconnexionPDO($connexion);

        return $liste;
        }
    }

}
