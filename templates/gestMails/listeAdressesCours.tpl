<ul class="list-unstyled">
    {foreach from=$listeElevesCours key=matricule item=dataEleve}
    <li><a href="mailto:{$dataEleve.mail}">{$dataEleve.nom} {$dataEleve.prenom} [{$dataEleve.mail}]</a></li>
    {/foreach}
</ul>
