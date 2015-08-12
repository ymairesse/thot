<?php
require_once("../config.inc.php");
include (INSTALL_DIR."/inc/entetesMin.inc.php");
// ----------------------------------------------------------------------------
//
// les paramètres peuvent éventuellement servir; autant les passer à Smarty
$smarty->assign('action',$action);
$smarty->assign('mode',$mode);
// Application::afficher($_REQUEST);
// le $userName est passé en POST dans le formulaire de reset du passwd; $_REQUEST englobe $_POST et $_GET
$token = isset($_REQUEST['token'])?$_REQUEST['token']:Null;
// le $userName est passé en POST dans le formulaire de reset du passwd; $_REQUEST englobe $_POST et $_GET
$userName = isset($_REQUEST['userName'])?$_REQUEST['userName']:Null;

switch ($mode) {
    case 'getPasswd':
        // on vérifie dans la BD si le userName correspond à un token non périmé;
        // $userName revient avec '' si token périmé ou introuvable dans la BD
        $userName = $Application->chercheToken($token, $userName);
        $identite = $Application->identiteParent($userName);
        if ($identite != ''){
            // parfait: on a un $userName et le $token correspondant, on peut proposer le reset du mdp pour ce user
            $smarty->assign('userName',$userName);
            $smarty->assign('token',$token);
            $smarty->assign('identite',$identite);
            $smarty->assign('mode','savePasswd');  // on force le mode à "savePasswd" dans le formulaire
            $smarty->assign('corpsPage','formPasswd');
            }
            else {
                // il y  a un souci sur le userName ou sur l'identité
                $smarty->assign('ADRESSETHOT',ADRESSETHOT);
                $smarty->assign('corpsPage','tokenIncorrect');
                }
        break;
    case 'savePasswd':
        $userName = $Application->chercheToken($token, $userName);
        // le $userName est garanti car provenant de la BD
        $nb = $Application->savePasswd($_POST,$userName);
        if ($nb == 0)
            $texte = "Pas de modification enregistrée";
        $smarty->assign('texte',$texte);
        $smarty->assign('corpsPage','passwdChange');
        break;
    case 'sendMail':
        // on va essayer de trouver l'identité sur base de l'adresse mail fournie
        $mail = isset($_POST['mail'])?$_POST['mail']:Null;
        $problemeMail = !($User->mailExists($mail));
        // si on n'a pas trouvé l'adresse mail, on tente de trouver le userName éventuellement fourni
        if ($problemeMail)
            $identite = $Application->verifUser($userName,'userName');
            else $identite = $Application->verifUser($mail,'mail');

        // cela peut toujours servir
        $smarty->assign('MAILADMIN',MAILADMIN);
        $smarty->assign('identite',$identite);

        // maintenant, l'identité doit être connue (si un mail existant ou un userName existant ont été envoyés)
        if ($identite != Null) {
            // retourne un lien aléatoire (md5(microtime())) enregistré dans la table lostPasswd
            $link = $Application->createPasswdLink($identite['userName']);
            // envoi effectif du mail
            $Application->mailPasswd($link, $identite, $User->identiteReseau());
            }
            else {
                // génération du message adéquat selon la cause de l'erreur
                if ($problemeMail)
                    $motifRefus = "L'adresse mail $mail est inconnue.";
                    else $motifRefus = "Le nom d'utilisateur $userName est inconnu.";
                $smarty->assign('motifRefus',$motifRefus);
                }
        $smarty->assign('corpsPage','sendMail');
        break;
    case 'lostPasswd':
        $smarty->assign('corpsPage','userOrMail');
        break;

    default:
        // des paramètres ont été passés, mais il manque, au moins, la valeur de $mode
        // il s'agit d'un lien incorrect
        $numArgs = count($_GET);
        if ($numArgs > 0) {
            $smarty->assign('ADRESSETHOT',ADRESSETHOT);
            $smarty->assign('corpsPage','tokenIncorrect');
            }
            // sinon, on présent la page "normale" pour l'indication du mail ou du userName
            else {

                $smarty->assign('corpsPage','userOrMail');
                }
        break;
}

//
// ----------------------------------------------------------------------------
$smarty->assign('executionTime', round($chrono->stop(),6));
$smarty->display ('index.tpl');
?>
