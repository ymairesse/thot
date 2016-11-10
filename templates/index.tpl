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
	<link rel="stylesheet" href="font-awesome-animation.css" type="text/css" media="screen">

	<script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery.validate.js"></script>

	<script type="text/javascript" src="js/moment-with-locales.js"></script>
	<script type="text/javascript" src="fc/fullcalendar.js"></script>
	<script type="text/javascript" src="fc/lang/fr.js"></script>

	<link rel="stylesheet" href="fc/fullcalendar.css" media="screen">
	<!-- Piwik -->
	<script type="text/javascript">
		var _paq = _paq || [];
		_paq.push(['trackPageView']);
		_paq.push(['enableLinkTracking']);
		(function() {
			var u = "//isnd.be/piwik/";
			_paq.push(['setTrackerUrl', u + 'piwik.php']);
			_paq.push(['setSiteId', 1]);
			var d = document,
				g = d.createElement('script'),
				s = d.getElementsByTagName('script')[0];
			g.type = 'text/javascript';
			g.async = true;
			g.defer = true;
			g.src = u + 'piwik.js';
			s.parentNode.insertBefore(g, s);
		})();
	</script>
	<noscript>
		<p>
			<img src="//isnd.be/piwik/piwik.php?idsite=1" style="border:0;" alt="" />
		</p>
	</noscript>
	<!-- End Piwik Code -->
</head>

<body>
	<div class="container">

		{include file="entete.tpl"}
		{include file="menu.tpl"}
		{if isset($selecteur)} {include file="$selecteur.tpl"} {/if}
		{if (isset($message))}
		<div class="alert alert-dismissable alert-{$message.urgence|default:'info'}
			{if (!(in_array($message.urgence,array('danger','warning'))))} auto-fadeOut{/if}">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4>{$message.title}</h4>
			<p>{$message.texte}</p>
		</div>
		{/if} {* La valeur de $corpsPage est définie dans index.php ou les sous-modules php *}
		<div id="corpsPage">
			{if isset($corpsPage)} {include file="$corpsPage.tpl"} {/if}
		</div>

	</div>
	<!-- container -->

	{include file="footer.tpl"}

	<script type="text/javascript">
		window.setTimeout(function() {
			$(".auto-fadeOut").fadeTo(500, 0).slideUp(500, function() {
				$(this).remove();
			});
		}, 3000);

		$(document).ready(function() {

			$("input:enabled").eq(0).focus();

			$("*[title]").tooltip();

			$(".pop").popover({
				trigger: 'hover'
			});

		})
	</script>

</body>

</html>
