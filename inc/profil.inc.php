<?php

if ($mode == 'editProfil') {
    $userName = $User->getUserName();
    // adresse mail enregistrée pour cet utilisateur
    $mailActuel = $User->getMail();
    // adresse mail demandée dans le formulaire
    $mail = $_POST['mail'];
    // problème de mail existe si l'adresse figure déjà dans la BD et pour un autre utilisateur
    $problemeMail = $User->mailExists($mail) && ($mail != $mailActuel);
    if ($problemeMail) {
        $motifRefus = "L'adresse mail <strong>{$mail}</strong> est déjà utilisée pour une autre personne.";
        $smarty->assign('motifRefus',$motifRefus);
        }
        else {
            $nb = $Application->saveProfilParent($_POST,$userName);
            $message = array(
                'title'=>SAVE,
                'texte'=>sprintf('%d enregistrement(s)',$nb),
                'urgence'=>SUCCES);
            $smarty->assign('message',$message);
            $User->setIdentite('parents');
            // mettre à jour la session avec les infos de l'utilisateur
            $_SESSION[APPLICATION] = serialize($User);
            }
    };
$identite = $User->getIdentite();
$smarty->assign('identite',$identite);
$smarty->assign('corpsPage','parents/profilParents');
?>
