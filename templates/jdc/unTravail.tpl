{if $travail == Null}
    Cette note au JDC a été supprimée.
{else}
<div class="panel day-highlight dh-{$travail.class}">

    <span id="delClass"></span>
    <div class="panel-heading">
        <h3 class="panel-title cat_{$travail.idCategorie}">{$travail.categorie} <span class="pull-right">
            {if $travail.type == 'cours'}
                <i class="fa fa-graduation-cap" title="Un cours"></i>
                {elseif $travail.type == 'classe'}
                <i class="fa fa-users" title="Une classe"></i>
            {/if}
        </span></h3>
    </div>

    <div class="panel-body">
        <p><strong>Le {$travail.startDate} à {$travail.heure} ({$travail.duree}) </strong></p>
        {if $travail.libelle != ''}
            <p>{$travail.libelle} {$travail.nbheures}h [{$travail.destinataire}]</p>
            {elseif $travail.type == 'classe'}
            <p>Classe {$travail.destinataire}</p>
        {/if}
        <p>Professeur <strong>{$titus}</strong> {if ($travail.redacteur!='') && ($travail.proprietaire != '')}{/if}</p>
        <h4>{$travail.title}</h4>
        <div id="unEnonce">{$travail.enonce}</div>

        {if ($travail.redacteur != '')}
            {if ($travail.proprietaire == '')}
                <i class="fa fa-warning fa-lg faa-flash animated text-danger"></i> <span class='discret'>Attention, pas encore approuvé par le professeur</span><br>

                {if ($travail.redacteur != $matricule)}
                {* on présente les boutons pour l'évaluation si l'élève courant n'est pas le rédacteur *}
                <div class="btn-group pull-right">
                    <button type="button" data-id="{$travail.id}" class="btn btn-success" id="like">
                        <span class="badge">{$likes.like|default:0}</span> <i class="fa fa-thumbs-up"></i>
                    </button>
                    <button type="button" data-id="{$travail.id}" class="btn btn-danger" id="dislike">
                        <span class="badge">{$likes.dislike|default:0}</span> <i class="fa fa-thumbs-down"></i>
                    </button>
                    <button type="button" data-id="{$travail.id}" class="btn btn-info" name="button" id="info">
                        <i class="fa fa-info"></i>
                    </button>
                </div>
                {/if}
            {else}
                <i class="fa fa-thumbs-up fa-lg text-success"></i> <span class="discret">Approuvé par le professeur</span>
            {/if}
        {/if}
    </div>

    {if ($matricule == $travail.redacteur)}
        {* <div class="discret">
            Rédigé par {$redacteur}
        </div> *}

        {if $editable == true}
            <div class="panel-footer">
                <button type="button" class="btn btn-danger pull-left" data-id="{$travail.id}" id="delete"><i class="fa fa-eraser fa-lg"></i> Supprimer</button>
                <button type="button"
                    class="btn btn-primary pull-right"
                    data-id="{$travail.id}"
                    data-destinataire="{$travail.destinataire}"
                    id="modifier">
                    <i class="fa fa-edit fa-lg"></i> Modifier
                </button>

                <div class="clearfix"></div>

            </div>
        {/if}
    {/if}

</div>

{/if}
