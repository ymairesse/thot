<?php

switch ($mode) {
    case 'addParent':
        // vérifier que l'adresse mail net le nom d'utilisateur ne sont pas déjà utilisés
        $mail = $_POST['mail'];
        $matricule = $_POST['matricule'];
        $userName = $_POST['userName'].$matricule;
        $motifRefus = '';

        $problemeUserName = $User->userExists($userName);
        $problemeMail = $User->mailExists($mail);
        if ($problemeUserName) {
            $motifRefus .= "Le nom d'utilisateur <strong>$userName</strong> est déjà utilisé pour une autre personne.<br>";
        }
        if ($problemeMail)
            $motifRefus .= "L'adresse mail <strong>{$mail}</strong> est déjà utilisée pour une autre personne.<br>";

        $smarty->assign('motifRefus', $motifRefus);

        if ($motifRefus == '') {
            // on enregistre ces informations s'il n'y a pas de motif de refus
            $nb = $Application->saveParent($_POST);
            $message = array(
                'title' => SAVE,
                'texte' => sprintf('%d enregistrement(s)', $nb),
                'urgence' => SUCCES, );
            $smarty->assign('message', $message);
        } else {
                // sinon, on renvoie toutes les informations dans le formulaire
                $smarty->assign('formule', $_POST['formule']);
                $smarty->assign('nomParent', $_POST['nomParent']);
                $smarty->assign('prenomParent', $_POST['prenomParent']);
                if ($problemeUserName == false) {
                    // supprimer le matricule du nom d'utilisateur à présenter
                    $to = strrpos($userName, $matricule, -1);
                    $userName = substr($userName, 0, $to);
                    $smarty->assign('userName', $userName);
                }
                if ($problemeMail == false) {
                    $smarty->assign('mail', $mail);
                }
                $smarty->assign('matricule', $matricule);
                $smarty->assign('lien', $_POST['lien']);
            }
        break;

    default:
        # code...
        break;
    }

$listeParents = $Application->listeParents($matricule);
$smarty->assign('listeParents', $listeParents);
$smarty->assign('matricule', $matricule);
$smarty->assign('corpsPage', 'parents');
