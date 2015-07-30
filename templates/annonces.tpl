<div class="row">

	<div class="col-md-3 col-sm-12">
		{include file="annonces/titresAnnonces.tpl"}
	</div>

	<div class="col-md-9 col-sm-12">
		{include file="annonces/listeAnnonces.tpl"}
	</div>

</div>  <!-- row -->


<div id="accuseLecture" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Accusé de lecture</h4>
            </div>

            <div class="modal-body">
                <p>Je confirme que j'ai lu l'annonce</p>
				{foreach from=$listeAnnonces key=wtf item=sousListe}
					{foreach from=$sousListe key=id item=data}
						<div class="resumes" id="annonce_{$id}">
							<p><strong>Objet:</strong> {$data.objet}</p>
							<p><strong>Texte:</strong> {$data.texte}</p>
						</div>
					{/foreach}
				{/foreach}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler: je vais la relire</button>
                <button type="button" class="btn btn-primary" id="confirmation" data-id="" data-type="">J'ai bien lu et j'ai compris</button>
            </div>

        </div>  <!-- modal-content -->
    </div>  <!-- modal-dialog -->
</div>  <!-- modal -->

<div id="avertissement" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content alert-warning">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Accusé de lecture</h4>
            </div>

            <div class="modal-body">
                <p id="nbAccuses" data-nbaccuses="{$nbAccuses}">Vous devez confirmer la lecture de {$nbAccuses} message(s).</p>
				<p>Voir les catégories marquées d'un signal <i class="fa fa-warning fa-lg"></i></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK, je regarde ça.</button>
            </div>

        </div>  <!-- modal-content -->
    </div>  <!-- modal-dialog -->
</div>  <!-- modal -->

<h4>Légende des couleurs</h4>
<ul class="list-inline">
	<li class="urgence0">Peu urgent</li>
	<li class="urgence1">Moyennement urgent</li>
	<li class="urgence2">Très urgent et important</li>
</ul>

<script type="text/javascript">

$(document).ready(function(){

	if ($("#nbAccuses").data('nbaccuses') > 0)
		$("#avertissement").modal('show');

	$(".lesAnnonces").hide();

	$(".lesAnnonces").first().show();

	$(".listeAnnonces li a").click(function(){
		var link = $(this).attr('href');
		$(".lesAnnonces").hide();
		$(link).fadeIn();
		});

	$(".lecture").click(function(){
		var id = $(this).data("id");
		var type = $(this).data("type");
		$(".resumes").hide();
		$("#annonce_"+id).show();
		$("#confirmation").data("id",id);
		$("#confirmation").data("type",type)
		$("#accuseLecture").modal('show');
	})

	$("#confirmation").click(function(){
		var id = $(this).data('id');
		var type = $(this).data('type');
		$.post( "inc/accuseReception.inc.php", {
			id: id
			},
			function (resultat){
				$("#span"+id).text(resultat).addClass('dateLecture');
				});
		$("#accuseLecture").modal('hide');
		$("#warning"+id).removeClass();
		if ($("#panel-"+type).find('.danger').length == 0)
			$("#panel-"+type+" .panel-title").find(".fa-warning").hide();
		});

	})

</script>
