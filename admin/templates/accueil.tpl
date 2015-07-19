<!DOCTYPE html>
<html lang="fr">
<head>
	<title>{$TITREGENERAL}</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<link rel="stylesheet" href="../screen.css" type="text/css" media="screen">
	<link rel="stylesheet" href="../print.css" type="text/css" media="print">
	<link rel="stylesheet" href="../bootstrap/fa/css/font-awesome.min.css" type="text/css" media="screen, print">
		
	<script type="text/javascript" src="../js/jquery-2.1.3.min.js"></script>
	<script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../js/jquery.validate.js"></script>
</head>
<body>
	<div class="container">
		
		{include file="entete.tpl"}
	
		{if (isset($message) && $message == 'faux')}
		<div class="alert alert-dismissable alert-danger">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<p>Nom d'utilisateur ou mot de passe incorrect</p>
			<p>Votre tentative d'accès, votre adresse IP et le nom de votre fournisseur d'accès ont été enregistrés.</p>
		</div>
		{/if}

		<div class="row">
		<div class="col-md-offset-4 col-md-4 col-sm-12">
			<form autocomplete="off" role="form" class="form-vertical" method="POST" id="login" action="logAdmin.php">
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
							data-content="Nom d'utilisateur."
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

{include file="../../templates/footer.tpl"}
</div>

</body>

<script type="text/javascript">
	
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

</script>

</html>

