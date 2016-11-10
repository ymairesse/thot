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
    public function retreiveEvents($from, $to)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $from = $from / 1000;
        $to = $to / 1000;
        $sql = 'SELECT id, destinataire, type, proprietaire, title, url, class, start, end ';
        $sql .= 'FROM '.PFX.'thotJdc ';
        $sql .= "WHERE start BETWEEN '$from' AND '$to' ";
        $resulat = $connexion->query($sql);
        $liste = array();
        if ($resulat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            while ($ligne = $resultat->fetch()) {
                $liste[] = array(
                    'id' => $ligne['id'],
                    'title' => $ligne['title'],
                    'url' => $ligne['url'],
                    'start' => strtotime($ligne['start']).'000',
                    'end' => strtotime($ligne['end']).'000',
                    );
            }
        }
        Application::DeconnexionPDO($connexion);
        echo json_encode(array('success' => 1, 'result' => $liste));
        exit;
    }

    public function getTravail($item_id)
    {
        $connexion = Application::connectPDO(SERVEUR, BASE, NOM, MDP);
        $sql = "SELECT destinataire, type, proprietaire, title, enonce, class, id, DATE_FORMAT(startDate,'%d/%m') AS startDate, ";
        $sql .= "DATE_FORMAT(startDate,'%H:%i') AS heure, endDate, TIMEDIFF(endDate, startDate) AS duree, allDay, ";
        $sql .= 'jdc.idCategorie, categorie, sexe, nom, prenom, libelle, nbheures, nomCours ';
        $sql .= 'FROM '.PFX.'thotJdc AS jdc ';
        $sql .= 'LEFT JOIN '.PFX.'profs AS dp ON dp.acronyme = jdc.proprietaire ';
        $sql .= 'JOIN '.PFX.'thotJdcCategories AS cat ON cat.idCategorie = jdc.idCategorie ';
        $sql .= 'LEFT JOIN '.PFX."cours AS dc ON cours = SUBSTR(jdc.destinataire,1,LOCATE('-',jdc.destinataire)-1) ";
        $sql .= 'LEFT JOIN '.PFX.'profsCours AS dpc ON dpc.coursGrp = destinataire ';
        $sql .= "WHERE id='$item_id' ";

        $travail = array();
        $resultat = $connexion->query($sql);
        if ($resultat) {
            $resultat->setFetchMode(PDO::FETCH_ASSOC);
            $travail = $resultat->fetch();
            if ($travail['sexe'] == 'F') {
                $nom = 'Mme ';
            } else {
                $nom = 'M. ';
            }
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
        Application::DeconnexionPDO($connexion);

        return $travail;
    }

    /**
     * retourne les différentes catégories de travaux disponibles (interro, devoir,...).
     *
     * @param void()
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
}
