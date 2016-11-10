<div class="casier {$data.statut}" data-idtravail="{$idTravail}">
    <h4>{$data.titre}</h4>
    <div class="corps">
        <p><i class="fa fa-folder-open-o"></i>
            <a href="download.php?type=tr&amp;idTravail={$data.idTravail}&amp;fileName={$data.fileInfos.fileName}" class="fileName" data-idtravail="{$data.idTravail}" title="Télécharger">{$data.fileInfos.fileName}</a><br>
            <strong class="dateRemise" data-idTravail="{$data.idTravail}">{$data.fileInfos.dateRemise}</strong>
        </p>
        <p>
            {if ($data.cote == null) && ($data.fileInfos.fileName != '')}
                <button type="button"
                        class="btn btn-xs btn-danger btnDel"
                        data-idtravail="{$data.idTravail}"
                        data-filename="{$data.fileInfos.fileName}">
                        Effacer
                </button> <span>Pas encore évalué</span>
                {elseif $data.cote != null}
                <button type="button" class="btn btn-xs btn-success btnVoirEval" data-idTravail="{$data.idTravail}">Voir</button> <strong>{$data.cote}/{$data.max}</strong>
            {/if}
        </p>
        <p>Fin: le <strong>{$data.dateFin}</strong></p>

    </div>
    <div class="bottom micro">Cliquer pour plus de détails</div>
</div>
