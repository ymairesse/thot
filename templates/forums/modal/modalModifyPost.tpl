<div id="modalModify" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalModifyLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="modalModifyLabel">Modification de ma contribution</h4>
      </div>
      <div class="modal-body">
          <form id="formModalModify">
              <label for="myPost">Ma contribution</label>
              <textarea name="myPost" id="myPost" rows="5" class="form-control">{$postAncien.post}</textarea>
              <input type="hidden" name="postId" id="postId" value="{$postAncien.postId}">
              <input type="hidden" name="idSujet" id="idSujet" value="{$postAncien.idSujet}">
              <input type="hidden" name="idCategorie" id="idCategorie" value="{$postAncien.idCategorie}">

          </form>
      </div>
      <div class="modal-footer">
          <div class="btn-group pull-right">
              <button type="button" class="btn btn-default" id="resetNewPost">Annuler</button>
              <button type="button" class="btn btn-primary" id="saveEditedPost">Enregistrer</button>
          </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $('#resetNewPost').click(function() {
            $('#myPost').val('');
        })

        $('#myPost').summernote({
			lang: 'fr-FR', // default: 'en-US'
			height: null, // set editor height
			minHeight: 150, // set minimum height of editor
			focus: true, // set focus to editable area after initializing summernote
            toolbar: [
              ['style', ['style']],
              ['font', ['bold', 'underline', 'italic', 'clear']],
              ['font', ['strikethrough', 'superscript', 'subscript']],
              ['fontname', ['fontname']],
              ['color', ['color']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['table', ['table']],
              ['insert', ['link', 'picture']],
              ['view', ['fullscreen', 'codeview', 'help']],
            ],
		});

        $('#formModalModify').validate({
            rules: {
                myPost: {
                    required: true,
                    minlength: 20
                }
            }
        })
    })
</script>
