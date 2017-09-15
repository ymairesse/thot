<script src="dropzone/dropzone.js" charset="utf-8"></script>
<link href="dropzone/dropzone.css" type="text/css" rel="stylesheet">

<div id="confirmation"></div>

    {*---------------- liste des cours ----------------*}
    {foreach from=$listeCoursAvecTravaux key=leCoursGrp item=travail name=boucle}

        <button
            type="button"
            class="btn btn-primary{if isset($coursGrp) && ($leCoursGrp == $coursGrp)} active{/if} btn-showCours"
            data-coursgrp={$leCoursGrp|default:Null}
            data-idtravail={$idTravail|default:Null}>
                {$travail.libelle}
        </button>

    {/foreach}


<div class="clearfix"></div>

{*---------------- pour chaque cours, liste des documents ------------------*}
    <div class="col-md-3 col-sm-12" id="listeTravauxCours">

        {include file='casiers/listeTravauxCours.tpl'}

    </div>

    <div class="col-md-9 col-sm-12" id='detailsTravail'>

        {include file='casiers/detailsTravail.tpl'}

    </div>


{include file='casiers/modal/modalCasier.tpl'}

{include file='casiers/modal/modalDelFile.tpl'}


<script type="text/javascript">

    $(document).ready(function() {

        $('#listeTravauxCours').on('click', '#btn-archives', function(){
            $('#travauxEnCours').addClass('hidden');
            $('#travauxArchives').removeClass('hidden');
            $('#btn-travauxEnCours').removeClass('hidden');
            $('#btn-archives').addClass('hidden');
        })

        $('#listeTravauxCours').on('click', '#btn-travauxEnCours', function(){
            $('#travauxEnCours').removeClass('hidden');
            $('#travauxArchives').addClass('hidden');
            $('#btn-travauxEnCours').addClass('hidden');
            $('#btn-archives').removeClass('hidden');
        })

        $('#detailsTravail').on('click', '#btn-delFile', function(){
            var idTravail = $('.btnShowTravail.active').data('idtravail');
            $.post('inc/casiers/getFileName.inc.php', {
                idTravail: idTravail
            },
            function(resultat){
                var obj = JSON.parse(resultat);
                var fileName = obj.fileName;
                var size = obj.size;
                $('#modalDelFileName').text(fileName);
                $('#modalFileSize').text(size);
                $('#modalDelFile').modal('show');
            })
        })

        $('#modalBtnDel').click(function(){
            var idTravail = $('.btnShowTravail.active').data('idtravail');
            $.post('inc/casiers/delTravailFile.inc.php', {
                idTravail: idTravail
            },
            function(resultat){
                if (parseInt(resultat) != -1) {
                    $.post('inc/casiers/detailsUpload.inc.php', {
                        idTravail: idTravail
                    },
                    function(resultat){
                        $('#fileInfos').html(resultat);
                    })
                }
                else bootbox.alert({
                        message: "Quelque chose s'est mal passé. Le fichier n'a pas été supprimé.",
                        className: 'bb-alternate-modal'
                    });
            $('#callDropZone').show().prop('disabled', false);
            $('#modalDelFile').modal('hide');
            })
        })

        $('#detailsTravail').on('click', '#saveRemarque', function(){
            var idTravail = $('.btnShowTravail.active').data('idtravail');
            var remarque = $('#remarque').val();
            $.post('inc/casiers/saveRemarque.inc.php', {
                idTravail: idTravail,
                remarque: remarque
                },
                function(resultat){
                    if (parseInt(resultat) > 0)
                        bootbox.alert('Remarque enregistrée');
                        else bootbox.alert({
                                message: "Quelque chose s'est mal passé. La remarque n'est pas enregistrée.",
                                className: 'bb-alternate-modal'
                            });
                })
        })

        $('#detailsTravail').on('click', '#callDropZone', function(){
            var idTravail = $('.btnShowTravail.active').data('idtravail');
            $('#modalIdTravail').val(idTravail);
            $('#modalCasier').modal('show');
        })

        $('.btn-showCours').click(function(){
            $('.btn-showCours').removeClass('active');
            $(this).addClass('active');
            var coursGrp = $(this).data('coursgrp');
            var presentIdTravail = $(this).data('idtravail');
            $.post('inc/casiers/getTravauxCours.inc.php', {
                coursGrp: coursGrp,
                idTravail: presentIdTravail
            },
            function(resultat){
                $('#listeTravauxCours').html(resultat);
                $('#detailsTravail').html('<p class="avertissement">Sélectionnez un travail dans la colonne de gauche</p>');
            });

            $.post('inc/casiers/refreshDetailsTravail.inc.php', {
                idTravail: presentIdTravail,
                coursGrp: coursGrp
            },
            function(resultat){
                if (resultat != '') {
                    $('#detailsTravail').html(resultat);
                    // $("#myDropZone").dropzone(Dropzone.options.myDropZone);
                    }
            })
        })

        $('#listeTravauxCours').on('click', '.btnShowTravail', function(){
            $('.btnShowTravail').removeClass('active');
            $(this).addClass('active');
            var idTravail = $(this).data('idtravail');
            $('.btn-showCours.active').data('idtravail', idTravail);
            $.post('inc/casiers/getDetailsTravail.inc.php', {
                idTravail: idTravail
                },
                function(resultat){
                    $('#detailsTravail').html(resultat);
                    // $("#myDropZone").dropzone(Dropzone.options.myDropZone);
                })
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
                    var idTravail = $('.btnShowTravail.active').data('idtravail');
                    formData.append("idTravail", idTravail);
                }),
                this.on("error", function(file, response) {
                    alert('Une erreur s\'est produite.');
                    }),
                this.on('queuecomplete', function() {
                    $('#callDropZone').hide().prop('disabled', true);
                    var idTravail = $('.btnShowTravail.active').data('idtravail');
                    $.post('inc/casiers/detailsUpload.inc.php',{
                        idTravail: idTravail
                    }, function(resultat){
                        $('#fileInfos').html(resultat);

                    })
                    var ds = this;
                    setTimeout(function() {
                        ds.removeAllFiles();
                        $('#modalCasier').modal('hide');
                    }, 3000);

                })
        }
    }


</script>