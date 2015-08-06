<h3>Inviter mes parents</h3>

<p>Tu peux inviter un maximum de deux parents.</p>

<div class="panel-group" id="accordion">

  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse2">
           Parent(s) invité(s)
        </a><i class="indicator glyphicon glyphicon-chevron-down pull-right"></i>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse in">
      <div class="panel-body">

        {include file="parents/listeParents.tpl"}

      </div>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse1">
          Inviter un parent
        </a><i class="indicator glyphicon glyphicon-chevron-up  pull-right"></i>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse">
      <div class="panel-body">

        {if $listeParents|count < 2}
            {include file="parents/formulaireParents.tpl"}
            {else}
            <p>Tu as déjà invité deux parents.</p>
        {/if}

      </div>
    </div>
  </div>

</div>



<script type="text/javascript">

$(document).ready(function(){

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
