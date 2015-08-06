{foreach from=$listeParents key=userName item=data}

    <div class="col-md-6 col-sm-12">
        <h4>{$userName}</h4>
        <p>Nom: <strong>{$data.formule} {$data.nom}</strong></p>
        <p>Prénom: <strong>{$data.prenom}</strong></p>
        <p>Adresse <strong>mail: {$data.mail}</strong></p>
        <p>Parenté: <strong>{$data.lien}</strong></p>
    </div>

{/foreach}
