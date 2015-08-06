<?php

if ($mode == 'editProfil') {
    $userName = $User->getUserName();
    $nb = $Application->saveProfilParent($_POST,$userName);
    $message = array(
        'title'=>SAVE,
        'texte'=>"$nb enregistrement(s)",
        'urgence'=>SUCCES);
    $smarty->assign('message',$message);
    $User->setIdentite('parents');
    // mettre à jour la session avec les infos de l'utilisateur
    $_SESSION[APPLICATION] = serialize($User);    
    };
$identite = $User->getIdentite();
$smarty->assign('identite',$identite);
$smarty->assign('corpsPage','profil');
?>
