<div class="modal fade" id="modalResultat" tabindex="-1" role="dialog" aria-labelledby="titleModalResultat" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="titleModalResultat"></h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <label for="modalCommentaire">Commentaire de l'élève</label>
             <div class="form-control-static" id="modalCommentaire"></div>
          </div>
          <div class="form-group">
              <label for="modalEvaluation">Évaluation du professeur</label>
              <div class="form-control-static" id="modalEvaluation" style="max-height: 10em; overflow:auto"></div>
          </div>
          <div class="clearfix"></div>
          <div class="form-group pull-right">
              <label>Cotation</label>
              <strong class="cote" id="modalCote"></strong> / <strong class="cote" id="modalMax"></strong>
          </div>
          <div class="clearfix"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
