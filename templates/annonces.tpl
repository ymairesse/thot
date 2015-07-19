<div class="row">
	
	<div class="col-md-3 col-sm-12">
		
		{include file="titresAnnonces.tpl"}
		
	</div>
	
	<div class="col-md-9 col-sm-12">
		
		{include file="listeAnnonces.tpl"}
		
	</div>


</div>  <!-- row -->

<h4>Légende des couleurs</h4>
<ul class="list-inline">
	<li class="urgence0">Peu urgent</li>
	<li class="urgence1">Moyennement urgent</li>
	<li class="urgence2">Très urgent et important</li>
</ul>

<script type="text/javascript">
	
$(document).ready(function(){
	
	$(".lesAnnonces").hide();
	
	$(".lesAnnonces").first().show();
	
	$(".listeAnnonces li a").click(function(){
		var link = $(this).attr('href');
		$(".lesAnnonces").hide();
		$(link).fadeIn();
		});
	
	})
	
</script>