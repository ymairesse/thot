<div class="panel panel-default" id="panel1">

    <div class="panel-heading">
        <h2 class="panel-title">
            <i class="fa fa-graduation-cap"></i>
            <a data-toggle="collapse" data-target="#collapseOne" href="#collapseOne">Accès aux bulletins, au journaux de classe et aux annonces</a>
        </h2>
    </div>

    <div id="collapseOne" class="panel-collapse collapse in">

        <div class="panel-body">

            <div class="col-md-5 col-xs-12">

                <form autocomplete="off" role="form" class="form-vertical" method="POST" id="login" action="login.php" id="formLogin">

                    <fieldset>
                        <legend>Veuillez vous identifier</legend>
                        <p>Cette plate-forme est strictement réservée aux élèves de l'<a href="http://secondaire.isnd.be" target="_blank">ISND</a> et leurs parents.</p>

                        <div class="form-group">
                            <p>Nom d'utilisateur: contient la première lettre du prénom, sept lettres du nom et 4 chiffres.</p>
                            <label for="userName" class="sr-only">Utilisateur</label>
                            <input type="text"
                                name="userName"
                                id="userName"
                                tabindex="1"
                                placeholder="Nom d'utilisateur"
                                class="pop"
                                data-content="Nom d'utilisateur, y compris les <span style='color:red'>4 chiffres.</span>. "
                                data-html="true"
                                data-placement="top">
                        </div>  <!-- form-group -->

                        <div class="form-group">
                            <label for="mdp" class="sr-only">Mot de passe</label>
                            <input name="mdp"
                                id="mdp"
                                type="password"
                                tabindex="2"
                                placeholder="Mot de passe"
                                class="pop"
                                data-content="Mot de passe"
                                data-html="true"
                                data-placement="top">
                        </div>  <!-- form-group -->

                        <button type="submit" class="btn btn-primary pull-right" tabindex="3">Connexion</button>
                    </fieldset>
                </form>

            </div>

            <div class="col-md-4 col-xs-8">

                <div class="panel-body">
                    <p>L'accès à cette plate-forme est réservé aux élèves de l'ISND et à leurs parents. Les élèves utilisent l'identifiant et le mot de passe distribués en début d'année scolaire.</p>
                    <p>Accès parent distinct: bientôt...</p>
                    <img src="images/logoPageVide.png" alt="isnd" class="img-responsive" style="width:50%">
                </div>  <!-- panel-body -->

            </div>  <!-- col-md-... -->

            <div class="col-md-3 col-xs-4">

                    <img src="images/thot.png" alt="thot">

            </div>  <!-- col-md-... -->

        </div> <!-- panel-body -->

    </div>  <!-- panel-collapse -->

</div>  <!-- panel 1 -->
