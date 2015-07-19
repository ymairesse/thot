<div id="annoncesPerso">
	
	{if isset($listeAnnonces.$matricule)}
	
		{foreach from=$listeAnnonces.$matricule item=uneAnnonce}
			<div id="perso{$uneAnnonce@index}" class="lesAnnonces">
				<h4 class="urgence{$uneAnnonce.urgence}">{$uneAnnonce.dateDebut}: {$uneAnnonce.objet}</h4>
				{$uneAnnonce.texte}
				<span class="pull-right contact">Contact: {$uneAnnonce.proprietaire}</span>
			</div>
		{/foreach}
	
	{/if}
	
</div>

<div id="annoncesClasse">
	
	{if isset($listeAnnonces.$classe)}
	
		{foreach from=$listeAnnonces.$classe item=uneAnnonce}
			<div id="classe{$uneAnnonce@index}" class="lesAnnonces">		
				<h4 class="urgence{$uneAnnonce.urgence}">{$uneAnnonce.dateDebut}: {$uneAnnonce.objet}</h4>
				{$uneAnnonce.texte}
				<span class="pull-right contact">Contact: {$uneAnnonce.proprietaire}</span>
			</div>
		{/foreach}
	
	{/if}
	
</div>

<div id="annoncesPerso">
	
	{if isset($listeAnnonces.$niveau)}
	
		{foreach from=$listeAnnonces.$niveau item=uneAnnonce}
			<div id="niveau{$uneAnnonce@index}" class="lesAnnonces">
				<h4 class="urgence{$uneAnnonce.urgence}">{$uneAnnonce.dateDebut}: {$uneAnnonce.objet}</h4>
				{$uneAnnonce.texte}
				<p><span  class="pull-right contact">Contact: {$uneAnnonce.proprietaire}</span></p>
			</div>
		{/foreach}
	
	{/if}
	
</div>

<div id="annoncesPerso">
	
	{if isset($listeAnnonces.ecole)}
	
		{foreach from=$listeAnnonces.ecole item=uneAnnonce}
			<div id="ecole{$uneAnnonce@index}" class="lesAnnonces">
				<h4 class="urgence{$uneAnnonce.urgence}">{$uneAnnonce.dateDebut}: {$uneAnnonce.objet}</h4>
				{$uneAnnonce.texte}
				<p><span  class="pull-right contact">Contact: {$uneAnnonce.proprietaire}</span></p>
			</div>
		{/foreach}
	
	{/if}
	
</div>
