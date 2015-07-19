<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-07 20:28:07
         compiled from "./templates/accueil.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19482168275569e6d98ca9f1-21793193%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3ef4ab59e73d1183976fcac192f45eb5531f23cc' => 
    array (
      0 => './templates/accueil.tpl',
      1 => 1433701238,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19482168275569e6d98ca9f1-21793193',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5569e6d98d91f0_03619817',
  'variables' => 
  array (
    'TITREGENERAL' => 0,
    'message' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5569e6d98d91f0_03619817')) {function content_5569e6d98d91f0_03619817($_smarty_tpl) {?><!DOCTYPE html>
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
			
			<div class="panel-group" id="accordion">
			
			<div class="panel panel-default" id="panel1">
				
				<div class="panel-heading">
					<h2 class="panel-title">
						<i class="fa fa-graduation-cap"></i>
						<a data-toggle="collapse" data-target="#collapseOne" href="#collapseOne">Accès aux bulletins, au journaux de classe et aux annonces</a>
					</h2>
				</div>
				
				<div id="collapseOne" class="panel-collapse collapse in">
					
					<div class="panel-body">
						
						<div class="col-md-6 col-sm-12">
					
						<form autocomplete="off" role="form" class="form-vertical" method="POST" id="login" action="login.php" id="formLogin">
							
							<fieldset>
							<legend>Veuillez vous identifier</legend>							
							<p>Cette plate-forme est strictement réservée aux élèves de l'<a href="http://isnd.be/cms" target="_blank">ISND</a> et leurs parents.</p>
							<div class="form-group">
								<p>Nom d'utilisateur: contient la première lettre du prénom, sept lettres du nom et 4 chiffres.</p>
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
						
						<div class="panel panel-warning col-md-6 col-sm-12">
							
							<div class="panel-body">
								<p>L'accès à cette plate-forme est réservé aux élèves de l'ISND et à leurs parents. Les élèves utilisent l'identifiant et le mot de passe distribués en début d'année scolaire.</p>
								<p>Accès parent distinct: bientôt...</p>
								<img src="images/logoPageVide.png" alt="isnd" class="img-responsive" style="width:50%">
							</div>  <!-- panel-body -->
							
						</div>  <!-- panel -->
						
					</div> <!-- panel-body -->
	
				</div>  <!-- panel-collapse -->
				
				
			</div>  <!-- panel 1 -->
			
			
			<div class="panel" id="panel2">
				
				<div class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-envelope"></i>
					<a data-toggle="collapse" data-parent="#accordion" data-target="#collapseTwo" href="#collapseTwo" class="collapsed">Accès à votre adresse mail scolaire</a>
				</h2>
				</div>
	
				<div id="collapseTwo" class="panel-collapse collapse">
				
					<div class="panel-body">
						
						<div class="row">
						<div class="col-md-6 col-sm-12">
							<h3>Infos</h3>
	
							<p>Cliquer ici: <a href="http://mail.isnd-edu.be">http://mail.isnd-edu.be</a></p>
		
							<p><i class="fa fa-pencil-square-o fa-3x" style="float:left; padding-right:0.5em"></i>Votre adresse de courrier électronique d'élève de l'ISND est formée</p>
							<ul>
								<li>de la première lettre de votre prénom</li>
								<li>suivie d'un maximum de 7 lettres de votre nom</li>
								<li>suivies des 4 chiffres de votre matricule (voir le journal de classe ou la carte de sortie)</li>
								<li>suivis du nom de domaine: @isnd-edu.be ou @loostic.be (selon les classes)</li>
							</ul>
							<p>Le mot de passe privé et confidentiel a été fourni en début d'année scolaire</p>
							
						</div>  <!-- col-md-... -->
						
						<div class="col-md-6 col-sm-12">
		
							<h3>Tutos</h3>
							<p><i class="fa fa-youtube fa-3x" style="float:left; padding-right:0.5em"></i> Plusieurs vidéos d'explication et de formation pour l'utilisation des adresses mail sont disponibles en suivant les liens suivants</p>
							<ol>
								<li><a href="https://youtu.be/oFLj_lZ5PXY" target="_blank">Accès aux mails dans l'interface Roundcube</a></li>
								<li><a href="https://youtu.be/HvsE9ycDAxE" target="_blank">Envoyer des mails avec le webmail Roundcube</a></li>
								<li><a href="https://youtu.be/s6m8Dk4ZpZI" target="_blank">Répondre et envoyer des pièces jointes</a></li>
							</ol>
							
						</div>  <!-- col-md-... -->
						</div>  <!-- row -->
						
					</div>  <!-- panel-body -->
	
				</div> <!-- collapse -->
	
			</div> <!-- panel2 -->
			
			<div class="panel" id="panel3">
				
				<div class="panel-heading">
				<h2 class="panel-title"><img src="images/clarolineIco.png" alt="Cl">
					<a data-toggle="collapse" data-parent="#accordion" data-target="#collapseThree" href="#collapseThree" class="collapsed">Accès à la plate-forme Claroline-Connect</a>
				</h2>
				</div>
				
				<div id="collapseThree" class="panel-collapse collapse">
					
					<div class="panel-body">
						
						<div class="col-md-6 col-sm-12">
							<h3>Infos</h3>
							<p><a href="http://isnd.be/claroline"><img src="images/logoClaroline.png" alt="Claroline" style="float:left; padding-right:0.5em"></a>L'adresse de la plate-forme Claroline-Connect de l'ISND: <a href="http://isnd.be/claroline">http://isnd.be/claroline</a></p>
						</div>
						
						<div class="col-md-6 col-sm-12">
							<h3>Tutos</h3>
							<p><i class="fa fa-youtube fa-3x" style="float:left; padding-right:0.5em"></i> Plusieurs vidéos d'explication et de formation pour l'utilisation de la plate-forme sont disponibles en suivant les liens suivants</p>
							<ol>
								<li><a href="http://youtu.be/I85M8i_zqRE" target="_blank">Se connecter à la plate-forme</a></li>
								<li><a href="http://youtu.be/p4AxIesTjow" target="_blank">Les espaces d'activités</a></li>
								<li><a href="http://youtu.be/v339nYAHfuI" target="_blank">Déposer des documents pour une évaluation</a></li>
								</ol>
						</div>
	
					</div>  <!-- body -->
					
				</div>  <!-- collapse -->
	
			</div> <!-- panel3 -->
			
	
			

			</div>  <!-- panel-group -->
			
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
