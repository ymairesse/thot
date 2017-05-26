<script src="dropzone/dropzone.js" charset="utf-8"></script>
<link href="dropzone/dropzone.css" type="text/css" rel="stylesheet">

<div id="confirmation"></div>

{if $listeDocs|@count > 0 }

{*---------------- liste des cours ----------------*}
<ul class="nav nav-pills">
    {foreach from=$listeDocs key=coursGrp item=travail name=boucle}
    {assign var=noTab value=$smarty.foreach.boucle.iteration}
    <li class="{if $smarty.foreach.boucle.iteration == 1} active{/if}">
        <a data-toggle="tab" href="#CASIER_{$noTab}" {if $noTab==1}class="active" {/if}>
            {$travail.libelle}
        </a>
    </li>
    {/foreach}
</ul>


<div class="tab-content" id="lesCasiers">
{*---------------- pour chaque cours, liste des documents ------------------*}
    {foreach from=$listeDocs key=coursGrp item=travaux name=boucle}
    {assign var=noTab value=$smarty.foreach.boucle.iteration}
    <div id="CASIER_{$noTab}" class="row tab-pane fade{if $noTab == 1} in active{/if}">
        {foreach from=$travaux.travaux key=idTravail item=data}
        <div class="col-md-3 col-xs-2 unCasier" data-idtravail="{$idTravail}">

            {include file='files/unCasier.tpl'}

        </div>
     {/foreach}
    </div>
    {/foreach}
</div>

{/if}

{include file='files/modalCasier.tpl'}

{include file='files/modalDelFile.tpl'}

{include file="files/modalResultat.tpl"}

<script type="text/javascript">
    $(document).ready(function() {

        $("#lesCasiers").on('click', '.btnDel', function(event){
            var idTravail = $(this).data('idtravail');
            var fileName = $(this).data('filename');
            $("#modalDelFileName").text(fileName);
            $("#modalBtnDel").data('idTravail', idTravail);
            $("#modalDelFile").modal('show');
            event.stopPropagation();
        })

        $("#lesCasiers").on('click', '.btnVoirEval', function(event){
            var idTravail = $(this).data('idtravail');
            $.post('inc/files/voirResultat.inc.php', {
                idTravail: idTravail
            },
            function(resultat){
                var obj = JSON.parse(resultat);
                $("#titleModalResultat").html(obj.titre);
                $("#modalRemarque").html(obj.remarque);
                $("#modalEvaluation").html(obj.evaluation);
                $("#modalCote").html(obj.cote);
                $("#modalMax").html(obj.max)
                $("#modalResultat").modal('show');
            })
            event.stopPropagation();
        })


        $('#modalBtnDel').click(function(){
            var idTravail = $(this).data('idTravail');
            $.post('inc/delTravailFile.inc.php', {
                idTravail: idTravail
            },
            function(resultat){
                $(".unCasier[data-idtravail='"+idTravail+"']").html(resultat);
                $("#modalDelFile").modal('hide');
            })
        })

        $("#lesCasiers").on('click', '.casier', function(event) {
            // si c'est un lien (vers le fichier déposé) ou un click sur un bouton, on arrête là
            if ((event.target.nodeName == 'A') || (event.target.nodeName == 'BUTTON'))
                return;
            // sinon, on traite le click
            var idTravail = $(this).data('idtravail');
            $.post('inc/getDetailsTravail.inc.php', {
                idTravail: idTravail
            }, function(resultat) {
                var obj = JSON.parse(resultat);
                $("#titreModalCasier").html(obj.titre);
                $("#modalConsigne").html(obj.consigne);
                $("#modalDateDebut").text(obj.dateDebut);
                $("#modalDateFin").text(obj.dateFin);
                $("#modalDateRemise").text(obj.dateRemise);
                if (obj.fileInfos.fileName == null) {
                    $("#myDropZone").show();
                    $("#modalFileName").text(obj.fileInfos.fileName).parent('p').addClass('hidden');
                    $('#modalDateRemise').text(obj.fileInfos.dateRemise).parent('p').addClass('hidden');
                }
                else {
                    $("#myDropZone").hide();
                    $("#modalFileName").text(obj.fileInfos.fileName).parent('p').removeClass('hidden');
                    $('#modalDateRemise').text(obj.fileInfos.dateRemise).parent('p').removeClass('hidden');
                }

                $("#modalRemarque").val(obj.remarque);
                $("#modalIdTravail").val(idTravail);

                $("#modalCasier").modal('show');
            })
        })
    })

    $("#saveTravail").click(function(){
        var remarque = $("#modalRemarque").val();
        var idTravail = $("#modalIdTravail").val();
        $.post('inc/saveRemarque.inc.php', {
            remarque: remarque,
            idTravail: idTravail
        },
        function(resultat){
            $(".unCasier[data-idtravail='"+idTravail+"']").html(resultat);
            $("#modalCasier").modal('hide');
        })
    })

    var nbFichiersMax = 1;
    var maxFileSize = 20;

    Dropzone.options.myDropZone = {
        maxFilesize: maxFileSize,
        maxFiles: nbFichiersMax,
        acceptedFiles: "image/jpeg,image/png,image/gif,application/pdf,.psd,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.odt,.ods,.odp,.odg,.csv,.txt,.pdf,.zip,.7z,.ggb,.mm,.xcf",
        url: "inc/upload.inc.php",
        accept: function(file, done) {
            done();
        },
        init: function() {
            this.on("maxfilesexceeded", function(file) {
                    alert("Pas plus de " + nbFichiersMax + " fichier(s) svp!");
                }),
                this.on("sending", function(file, xhr, formData) {
                    formData.append("idTravail", $("#modalIdTravail").val());
                }),
                this.on("error", function(file, response) {
					alert('Une erreur s\'est produite.');
					}),
                this.on('queuecomplete', function() {
                    var idTravail = $('#modalIdTravail').val();
                    $.post('inc/files/restoreUnCasier.inc.php', {
                        idTravail: idTravail
                    },
                    function(resultat){
                        $(".unCasier[data-idtravail='"+idTravail+"']").html(resultat);
                    });
                    var ds = this;
                    setTimeout(function() {
                        ds.removeAllFiles();
                        $.post('inc/getDetailsTravail.inc.php',{
                            idTravail: idTravail
                        }, function(resultat){
                            var obj = JSON.parse(resultat);
                            var idTravail = obj.idTravail;
                            $("#modalDateRemise").text(obj.fileInfos.dateRemise).parent('p').removeClass('hidden');
                            $("#modalFileName").text(obj.fileInfos.fileName).parent('p').removeClass('hidden');
                            // $(".fileName[data-idtravail='"+idTravail+"']").text(obj.fileInfos.fileName);
                            // $(".dateRemise[data-idtravail='"+idTravail+"']").text(obj.fileInfos.dateRemise);
                        })
                        $("#myDropZone").hide('slow');
                    }, 5000);

                })
        }
    };


</script>
