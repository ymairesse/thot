<?php

$identite = $User->getIdentite();

if ($mode == 'editProfil') {

    $userName = $identite['userName'];

    $nb = $Application->saveProfilParent($_POST, $userName);

    $message = array(
            'title' => SAVE,
            'texte' => sprintf('%d enregistrement(s)', $nb),
            'urgence' => SUCCES, );
    $smarty->assign('message', $message);
    $User->setIdentite('parent');

        // mettre à jour la session avec les infos de l'utilisateur
    $_SESSION[APPLICATION] = serialize($User);
};

$smarty->assign('identite', $identite);
$smarty->assign('corpsPage', 'parents/profilParents');
