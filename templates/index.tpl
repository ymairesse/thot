<!DOCTYPE html>
<html lang="fr">
<head>
	<title>{$TITREGENERAL}</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<link rel="stylesheet" href="screen.css" type="text/css" media="screen">
	<link rel="stylesheet" href="print.css" type="text/css" media="print">
	<link rel="stylesheet" href="bootstrap/fa/css/font-awesome.min.css" type="text/css" media="screen, print">
		
	<script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery.validate.js"></script>
</head>
<body>
	<div class="container">
		
		{include file="entete.tpl"}
		
		{include file="menu.tpl"}
		
		{if isset($selecteur)}
			{include file="$selecteur.tpl"}
		{/if}
		
		{* La valeur de $corpsPage est définie dans index.php ou les sous-modules php *}
		<div id="corpsPage">
		{if isset($corpsPage)}
			{include file="$corpsPage.tpl"}
		{/if}
		</div>
		
	</div>  <!-- container -->

{include file="footer.tpl"}


<script type="text/javascript">
	
$(document).ready (function() {
	
	$("input:enabled").eq(0).focus();
	
	$("*[title]").tooltip();
	
	$(".pop").popover({
		trigger:'hover'
		});	
		
})

</script>

</body>

</html>

