<form name="parent" id="parent" method="POST" action="index.php" role="form" class="form-vertical">
    <h4>Ajouter un parent</h4>
    <p>Veuillez remplir tous les champs.</p>
    <div class="row">

        <div class="col-md-4 col-sm-12">

             <div class="input-group">
                <label for="Formule"></label>
                <span class="input-group-addon"><i class="fa fa-info-circle fa-lg fa-help"></i></span>
                <select name="formule" id="formule" class="inputHelp form-control">
                    <option value="">Formule d'appel</option>
                    <option value="Mme">Madame</option>
                    <option value="M.">Monsieur</option>
                </select>
            </div>

            <div class="input-group">
                <label for="nom" class="sr-only">Nom de famille</label>
                <span class="input-group-addon"><i class="fa fa-info-circle fa-lg fa-help"></i></span>
                <input type="text" name="nom" id="nom" value="" maxlength="50" placeholder="Nom de famille" class="inputHelp  form-control">
            </div>

            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info-circle fa-lg fa-help"></i></span>
                <label for="prenom" class="sr-only">Prénom</label>
                <input type="text" name="prenom" id="prenom" value="" maxlength="50" placeholder="Prénom" class="inputHelp form-control">
            </div>

            <div class="input-group">
                <label for="userName" class="sr-only">Nom d'utilisateur</label>
                <span class="input-group-addon"><i class="fa fa-info-circle fa-lg fa-help"></i></span>
                <input type="text" name="userName" id="userName" value="" maxlength="25" placeholder="Nom d'utilisateur" class="inputHelp form-control">
                <span class="input-group-addon">{$matricule}</span>
            </div>

            <div class="input-group">
                <label for="mail" class="sr-only">Adresse mail</label>
                <span class="input-group-addon"><i class="fa fa-info-circle fa-lg fa-help"></i></span>
                <input type="email" name="mail" id="mail" value="" maxlength="60" placeholder="Adresse mail" class="inputHelp form-control">
            </div>

            <div class="input-group">
                <label for="lien" class="sr-only">Lien de parenté</label>
                <span class="input-group-addon"><i class="fa fa-info-circle fa-lg fa-help"></i></span>
				<input  type="text" name="lien" id="lien" maxlength="20" value="" placeholder="Lien de parenté" class="inputHelp form-control">
				<div class="input-group-btn">
				    <button aria-expanded="false" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
					    Choisir <span class="caret"></span>
				    </button>
    				<ul class="dropdown-menu pull-right" id="choixLien">
    					<li><a href="javascript:void(0)" data-value="Mère">Mère</a></li>
                        <li><a href="javascript:void(0)" data-value="Père">Père</a></li>
    					<li><a href="javascript:void(0)" data-value="Autre (merci de préciser)">Autre</a></li>
    				</ul>
	    		</div>
    		</div>

            <div class="input-group">
                <label for="passwd" class="sr-only"></label>
                <span class="input-group-addon"><i class="fa fa-info-circle fa-lg fa-help"></i></span>
                <input type="password" name="passwd" id="passwd" value="" maxlength="20" placeholder="Mot de passe souhaité" class="inputHelp form-control required goodPwd">
            </div>

            <div class="input-group">
                <label for="password2" class="sr-only"></label>
                <span class="input-group-addon"><i class="fa fa-info-circle fa-lg fa-help"></i></span>
                <input type="password" name="passwd2" id="passwd2" value="" maxlength="20" placeholder="Veuillez répéter le mot de passe" class="inputHelp form-control required ">
            </div>

            <div class="btn-group pull-right">
                <button type="reset" class="btn btn-default">Annuler</button>
                <button type="submit" class="btn btn-primary" name="submit">Enregistrer</button>
            </div>

            <input type="hidden" name="matricule" value="{$matricule}">
            <input type="hidden" name="action" value="{$action}">
            <input type="hidden" name="mode" value="addParent">
        </div> <!-- col-md-... -->

        <div class="col-md-8 col-sm-12">

            {include file="parents/profileHelp.tpl"}

        </div>  <!-- div-md-... -->

    </div>  <!-- row -->

</form>


<script type="text/javascript">

    function countLettres(chaine) {
        return (chaine.match(/[a-zA-Z]/g) == null)?0:chaine.match(/[a-zA-Z]/g).length;
        }
    function countChiffres(chaine) {
        return (chaine.match(/[0-9]/g) == null)?0:chaine.match(/[0-9]/g).length;
    }

    jQuery.validator.addMethod('goodPwd', function(value, element) {
        // validation longueur
        var condLength = (value.length >= 9);
        // validation 2 chiffres min
        var condChiffres = (countChiffres(value) >= 2)
        // validation 2 lettres min
        var condLettres = (countLettres(value) >= 2)

        var testOK = (condLength && condChiffres && condLettres);
        return this.optional(element) || testOK;
        }, "Complexité insuffisante");


$(document).ready(function(){

    $(".help").hide();
    $(".fa-help").css('cursor','pointer');

    $(".inputHelp").focus(function(){
        var id=$(this).attr('id');
        $(".help").hide();
        $("#texte_"+id).fadeIn();
    })

    $(".inputHelp").blur(function(){
        $(".help").hide();
    })

    $(".fa-help").hover(function(){
        var id=$(this).closest('.input-group').find('.inputHelp').attr('id');
        $(".help").hide();
        $("#texte_"+id).fadeIn();
    })

    $("#choixLien li a").click(function(){
        $("#lien").val($(this).data('value'));
        $("#lien").select();
        })

    $("#parent").validate({
      rules: {
        formule: {
            required: true
            },
        nom: {
            required:true
            },
        prenom: {
            required: true
            },
        userName: {
            required: true
            },
        mail: {
            required: true,
            email: true
            },
        lien: {
            required: true
            },
        passwd: {
            required:true,
            goodPwd:true
            },
        passwd2: {
            equalTo: "#passwd"
            }
        },
        messages: {
            lien: {
                maxlength: 'Veuillez préciser s.v.p.'
            }
        }
    });

})
</script>
