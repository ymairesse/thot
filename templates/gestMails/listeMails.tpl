<h3>Communiquer</h3>

<div class="row">

    <div class="col-md-4 col-sm-12" style="max-height:25em; overflow:auto;">
        <h4>Ma classe {$classe}</h4>
        <ul class="list-unstyled">
        {foreach from=$listeElevesClasse key=matricule item=dataEleve}
        <li><a href="mailto:{$dataEleve.user}@{$dataEleve.mailDomain}">{$dataEleve.nom} {$dataEleve.prenom} [{$dataEleve.user}@{$dataEleve.mailDomain}]</a></li>
        {/foreach}
        </ul>
    </div>

    <div class="col-md-4 col-sm-12">
        <h4>Mes cours</h4>
        <select class="form-control" name="listeCours" id="listeCours">
            <option value="">Sélectionner un cours</option>
            {foreach from=$listeCours key=coursGrp item=dataCours}
                <option value="{$coursGrp}">{$dataCours.libelle} {$dataCours.nbheures}h</option>
            {/foreach}
        </select>
        <div id="elevesCours" style="max-height:20em; overflow:auto;">

        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="notice">
        <h4>Quelques règles</h4>
        <p>Les adresses mail sont <strong>des informations privées</strong> qui ne doivent pas être diffusées hors du cadre de l'école.</p>
        <p>Les adresses mail <strong>scolaires</strong> devraient être réservées à l'usage <strong>scolaire</strong>.</p>
        <p style="font-weight: bold">Lorsque j'envoie un mail, je m'assure toujours qu'il ne contient aucun élément qui <u>pourrait</u> choquer (phrase, expression, image) mon correspondant.</p>

        </div>
    </div>

</div>


<script type="text/javascript">

    $(document).ready(function(){

        $('#listeCours').on('change', function(){
            var coursGrp = $('#listeCours').val();
            $.post('inc/listeElevesCours.inc.php', {
                coursGrp: coursGrp
                },
                function(resultat){
                    $('#elevesCours').html(resultat);
                })

        })
    })

</script>
