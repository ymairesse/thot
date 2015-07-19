<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-05-31 12:32:31
         compiled from "./templates/admin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1203949297556adecc8c84d3-91072952%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '08588ccadf0cc24f4da8d2ce69b825703c3f1405' => 
    array (
      0 => './templates/admin.tpl',
      1 => 1433067472,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1203949297556adecc8c84d3-91072952',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_556adecc8e4130_20668660',
  'variables' => 
  array (
    'TITREGENERAL' => 0,
    'message' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556adecc8e4130_20668660')) {function content_556adecc8e4130_20668660($_smarty_tpl) {?><!DOCTYPE html>
<html lang="fr">
<head>
	<title><?php echo $_smarty_tpl->tpl_vars['TITREGENERAL']->value;?>
</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<link rel="stylesheet" href="screen.css" type="text/css" media="screen">
	<link rel="stylesheet" href="print.css" type="text/css" media="print">
	<link rel="stylesheet" href="bootstrap/fa/css/font-awesome.min.css" type="text/css" media="screen, print">
		
	<?php echo '<script'; ?>
 type="text/javascript" src="js/jquery-2.1.3.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="js/jquery.validate.js"><?php echo '</script'; ?>
>
</head>
<body>
	<div class="container">
		
		<?php echo $_smarty_tpl->getSubTemplate ("entete.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	
		<?php if ((isset($_smarty_tpl->tpl_vars['message']->value)&&$_smarty_tpl->tpl_vars['message']->value=='faux')) {?>
		<div class="alert alert-dismissable alert-danger">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<p>Nom d'utilisateur ou mot de passe incorrect</p>
			<p>Votre tentative d'accès, votre adresse IP et le nom de votre fournisseur d'accès ont été enregistrés.</p>
		</div>
		<?php }?>

		<div class="row">
		<div class="col-md-offset-4 col-md-4 col-sm-12">
			<form autocomplete="off" role="form" class="form-vertical" method="POST" id="login" action="logAdmin.php" id="formLogin">
				<fieldset>
				<legend>Veuillez vous identifier</legend>							
				<div class="form-group">
					<label for="userName" class="sr-only">Utilisateur</label>
					<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-4">
						<input type="text"
							name="userName"
							id="userName"
							tabindex="1"
							placeholder="Nom d'utilisateur"
							class="pop"
							data-content="Nom d'utilisateur, y compris les <span style='color:red'>4 chiffres.</span>. "
							data-html="true"
							data-placement="top"
							>
					</div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="mdp" class="sr-only">Mot de passe</label>
					<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-4">				
						<input name="mdp"
							id="mdp"
							type="password"
							tabindex="2"
							placeholder="Mot de passe"
							class="pop"
							data-content="Mot de passe"
							data-html="true"
							data-placement="top"
							>
					</div>
					</div>
				</div>
				
				<button type="submit" class="btn btn-primary pull-right" tabindex="3">Connexion</button>
				</fieldset>
			</form>
		</div>
	</div>  <!-- row -->
	</div>  <!-- container -->

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

</div>

</body>

<?php echo '<script'; ?>
 type="text/javascript">
	
$(document).ready (function() {
	
	$("input:enabled").eq(0).focus();
	
	$("#login").validate({
		rules: {
			userName: { required:true },
			mdp: { required:true }
			},
		errorElement: "span"
		});

	$("*[title]").tooltip();
	
	$(".pop").popover({
		trigger:'hover'
		});	
		
})

<?php echo '</script'; ?>
>

</html>

<?php }} ?>