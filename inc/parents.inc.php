<?php

switch ($mode) {
    case 'addParent':
        $nb = $Application->saveParent($_POST);
        $message = array(
            'title'=>SAVE,
            'texte'=>"$nb enregistrement(s)",
            'urgence'=>SUCCES);
        $smarty->assign('message',$message);
        break;

    default:
        # code...
        break;
    }

$listeParents = $Application->listeParents($matricule);
$smarty->assign('listeParents',$listeParents);
$smarty->assign('matricule',$matricule);
$smarty->assign('corpsPage','parents');

 ?>
