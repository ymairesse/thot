<div class="modal fade" id="modalCasier" tabindex="-1" role="dialog" aria-labelledby="titreModalCasier" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="titreModalCasier"></h4>
            </div>
            <div class="modal-body">
                <form class="form-vertical">
                    <div class="form-group">
                      <label for="modalConsigne">Consignes</label>
                      <div class="form-control-static" id="modalConsigne" style="height: 6em; overflow: auto;"></div>
                      <p class="help-block">Rappel des consignes</p>
                    </div>

                    <p>Début: <strong id="modalDateDebut"></strong> Fin <strong id="modalDateFin"></strong>
                    </p>
                    <p>Document: <strong id="modalFileName">Pas de document</strong> Remis le: <strong id="modalDateRemise">Non remis</strong></p>
                    <div id="myDropZone" class="dropzone">
                    <input type="hidden" name="idTravail" id="modalIdTravail" value="">

                    </div>
                    <div class="form-group">
                      <label for="modalRemarque">Remarque pour le professeur</label>
                      <input name="remarque" class="form-control" maxlength="80" id="modalRemarque">
                      <p class="help-block">Une information à ajouter?</p>
                    </div>

                    <button type="button" class="btn btn-primary pull-right" id="saveTravail">Enregistrer</button>

                <div class="clearfix"></div>
                </form>
            </div>

        </div>
    </div>
</div>
