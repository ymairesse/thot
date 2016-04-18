<h3>Inviter mes parents</h3>

<p>Tu peux inviter un maximum de deux parents.</p>

<div class="panel-group" id="accordion">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse1">
           Parent(s) invité(s)
        </a><i class="indicator glyphicon glyphicon-chevron-down pull-right"></i>
            </h4>
        </div>
        <div id="collapse1" class="panel-collapse collapse in">
            <div class="panel-body">

                {include file="parents/listeParents.tpl"}

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse2">
          Inviter un parent
        </a><i class="indicator glyphicon glyphicon-chevron-up  pull-right"></i>
            </h4>
        </div>
        <div id="collapse2" class="panel-collapse collapse">
            <div class="panel-body">

                {if $listeParents|count < 2}
                    {include file="parents/formulaireParents.tpl" } {else} <p>Tu as déjà invité deux parents.</p>
                {/if}

            </div>
        </div>
    </div>

</div>



{if isset($motifRefus) && ($motifRefus != '')}
<div id="motifRefus" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Problème</h4>
            </div>

            <div class="modal-body">
                <p>{$motifRefus}</p>
                <p>Veuillez corriger</p>
                <p class="text-danger"><i class="fa fa-warning fa-lg"></i> Les données ne sont pas enregistrées</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer cette fenêtre</button>
            </div>

        </div>
        <!-- modal-content  -->
    </div>
    <!-- modal-dialog -->
</div>
<!-- motifRefus -->

<script type="text/javascript">
    $(document).ready(function() {
        $("#collapse1").collapse('hide');
        $("#collapse2").collapse('show');
        $("#motifRefus").modal('show');
    })
</script>
{/if}


<script type="text/javascript">
    $(document).ready(function() {

        function toggleChevron(e) {
            $(e.target)
                .prev('.panel-heading')
                .find("i.indicator")
                .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
        }

        $('#accordion').on('hidden.bs.collapse', toggleChevron);
        $('#accordion').on('shown.bs.collapse', toggleChevron);

    })
</script>
