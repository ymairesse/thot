<h3>J'ai oublié mon mot de passe (CETTE FONCTION EST EXPÉRIMENTALE)</h3>

<div class="col-md-9 col-sm-6">

    <form name="mdp" role="form" style="margin-bottom: 2em">
        <p>Choisissez l'option qui convient</p>
        <input id="nonInscrit" class="mdp" type="checkbox" name="radio1">
        <label for="nonInscrit">Je n'ai pas encore été inscrit-e sur la plate-forme</label>
        <br>
        <input id="oubli" class="mdp" type="checkbox" name="radio1">
        <label for="oubli">Je suis inscrit-e, mais j'ai un trou de mémoire</label>
    </form>

    <p class="pull-right">Pour retourner à la page d'accueil, <a href="../index.php">Cliquez ici</a>.</p>

</div>
<!--col-md-... -->

<div class="col-md-3 col-sm-6">
    <img src="../images/hautThot.png" alt="Thot">
</div>

<div class="row blabla" id="row_oubli" style="display: none">

    <div class="col-md-6 col-sm-12">

        <div class="alert alert-info">

            <h4><i class="fa fa-info-circle"></i> Vous êtes inscrit-e sur la plate-forme, mais vous avez un trou de mémoire</h4>
            <p>Nous n'avons aucun moyen de connaître votre mot de passe. Mais nous allons vous envoyer un mail qui vous permettra de changer ce mot de passe.</p>
            <p>Pour vous identifier, veuillez indiquer </p>
            <ul>
                <li>l'adresse mail OU</li>
                <li>le nom d'utilisateur</li>
            </ul>
            <p>donnés lors de votres inscription.</p>
            <p>Si vous avez aussi oublié ces informations, votre enfant peut les trouver dans la section "élèves" de la plate-forme. Mais elle/il ne peut pas changer ou connaître votre mot de passe.</p>

        </div>
        <!-- alert-info -->

    </div>
    <!-- col-md-... -->

    <div class="col-md-6 col-sm-12">
        <h4>Obtenir le lien pour changer mon mot de passe</h4>
        <p>Il nous faut votre adresse mail ou le nom d'utilisateur que vous avez choisi.</p>

        <form name="userOrMail" id="userOrMail" method="POST" action="index.php" autocomplete="off" role="form" class="form-vertical">
            <div class="input-group">
                <label for="mail" class="sr-only">Adresse mail</label>
                <input type="text" name="mail" id="mail" value="" placeholder="Adresse mail" class="form-control">
            </div>

            <div class="input-group">
                <label for="userName" class="sr-only">Nom d'utilisateur</label>
                OU
                <input type="text" name="userName" id="userName" value="" placeholder="Nom d'utilisateur" class="form-control">
                <div class="help-block">N'oubliez pas les 4 chiffres pour terminer</div>
            </div>

            <button type="submit" class="btn btn-primary">Envoyer la demande</button>
            <input type="hidden" name="action" value="{$action}">
            <input type="hidden" name="mode" value="sendMail">

        </form>

    </div>
    <!-- col-md-... -->

</div>
<!-- row -->

<div class="row blabla" id="row_nonInscrit" style="display: none">

    <div class="col-md-6 col-sm-12">

        <div class="alert alert-danger fade in" id="alert_nonInscrit">
            <h4><i class="fa fa-warning fa-lg"></i> Vous n'avez jamais été inscrit sur la plate-forme Thot?</h4>
            <p>Si vous n'avez jamais été inscrit-e
                <strong>sur la plate-forme Thot</strong>, demandez à votre enfant de réaliser l'inscription dans la section "élèves" (menu "Parents").</p>
            <p>Pour y entrer, elle/il utilisera son propre nom d'utilisateur et son propre mot de passe fournis au début de l'année scolaire.</p>
        </div>

    </div>
    <!-- col-md-... -->

    <div class="col-md-6 col-sm-12">
        <!-- vide -->
    </div>

</div>
<!-- row -->

<script type="text/javascript">
    $(document).ready(function() {

        $('.mdp').click(function() {
            $(".mdp").not(this).attr('checked', false);
            var id = $(this).attr('id');
            $(".blabla").hide();
            $("#row_" + id).show();
        })


        $("#mail").keydown(function(e) {
            var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
            if (key >= 32)
                $("#userName").val('');
        })

        $("#userName").keydown(function(e) {
            var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
            if (key >= 32)
                $("#mail").val('');
        })

        // $("#userOrMail").validate({
        //     rules: {
        //         mail: {
        //             email: true
        //         }
        //     }
        // });

        $("#userOrMail").validate({
            rules: {
                mail: {
                    required: function(element) {
                        return ($("#userName").val() == '');
                    },
                    email: true
                },
                userName: {
                    required: function(element) {
                        return ($("#mail").val() == '');
                    }
                }
            }

        })

    })
</script>
