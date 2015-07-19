<div class="panel-group listeAnnonces" id="accordion">
  

    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">{$nom} <span class="badge pull-right">{$listeAnnonces.$matricule|@count|default:0}</span></a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse in  annonces personnel">
	  
	  
        <div class="panel-body">
			{if isset($listeAnnonces.$matricule)}
				<ul>
				{foreach from=$listeAnnonces.$matricule item=uneAnnonce}
					<li class="urgence{$uneAnnonce.urgence}"><a href="#perso{$uneAnnonce@index}" title="{$uneAnnonce.dateDebut}">{$uneAnnonce.objet|truncate:30}</a></li>
				{/foreach}
			</ul>
			{else}
				<p>Néant</p>
			{/if}
		</div>
		
		
      </div>
    </div>  <!-- panel-default -->
	

    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Classe {$classe} <span class="badge pull-right">{$listeAnnonces.$classe|@count|default:0}</span></a>
        </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse annonces classe">
	  
	  
        <div class="panel-body">
		{if isset($listeAnnonces.$classe)}
			<ul>
			{foreach from=$listeAnnonces.$classe item=uneAnnonce}
				<li class="urgence{$uneAnnonce.urgence}"><a href="#classe{$uneAnnonce@index}" title="{$uneAnnonce.dateDebut}">{$uneAnnonce.objet|truncate:30}</a></li>
			{/foreach}
			</ul>	
		{else}
			<p>Néant</p>
		{/if}
		</div>
		
		
      </div>
    </div>  <!-- panel-default -->
	

    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Élèves de {$niveau}<sup>e</sup> <span class="badge pull-right">{$listeAnnonces.$niveau|@count|default:0}</span></a>
        </h4>
      </div>
      <div id="collapse3" class="panel-collapse collapse annonces niveau">
	  
	  
        <div class="panel-body">

		{if isset($listeAnnonces.$niveau)}
			<ul>
				{foreach from=$listeAnnonces.$niveau item=uneAnnonce}
					<li class="urgence{$uneAnnonce.urgence}"><a href="#niveau{$uneAnnonce@index}" title="{$uneAnnonce.dateDebut}">{$uneAnnonce.objet|truncate:30}</a></li>
				{/foreach}
			</ul>
		{else}
			<p>Néant</p>
		{/if}

			
		</div>
		
		
      </div>
    </div>  <!-- panel-default -->
	
	
	<div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Tous <span class="badge pull-right">{$listeAnnonces.ecole|@count|default:0}</span></a>
        </h4>
      </div>
      <div id="collapse4" class="panel-collapse collapse annonces ecole">
	  
	  
        <div class="panel-body">
		
		{if isset($listeAnnonces.ecole)}
			<ul>
				{foreach from=$listeAnnonces.ecole item=uneAnnonce}
					<li class="urgence{$uneAnnonce.urgence}"><a href="#ecole{$uneAnnonce@index}" title="{$uneAnnonce.dateDebut}">{$uneAnnonce.objet|truncate:30}</a></li>
				{/foreach}
			</ul>
		{else}
			<p>Néant</p>
		{/if}
			
		</div>
		
		
      </div>
    </div>  <!-- panel-default -->

</div>  <!-- accordion -->

