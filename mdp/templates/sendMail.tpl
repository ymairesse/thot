<h3>Récupération d'un mot de passe</h3>

<div class="col-md-9 col-sm-6">

{if isset($motifRefus)}

    <p>Malgré tous nos efforts, il n'a pas été possible de vous identifier.</p>
    <p>La raison est la suivante:</p>
    <p><strong>{$motifRefus}</strong></p>

    <p>Conseils:</p>
    <ul>
        <li>demandez à votre enfant de vérifier sous quel nom d'utilisateur et avec quelle adresse mail vous êtes inscrit-e sur la plate-forme.</li>
        <li>Si vous avez tenté de récupérer votre mot de passe en donnant une adresse mail, essayez d'indiquer votre nom d'utilisateur.</li>
    </ul>
    <p>Peut-être avez-vous simplement fait une faute de frappe en indiquant cet indice d'identification? Dans ce cas, tentez encore une fois votre chance en cliquant sur le lien <a href="index.php">Changer mon mot de passe</a>.</p>
    <p>En cas de problème persistant, contactez <a href="mailto:{$MAILADMIN}">{$MAILADMIN}</a></p>

    <p class="pull-right">Pour retourner à la page d'accueil, <a href="../index.php">Cliquez ici</a>.</p>
    
    {else}

    <p>Un mail vient d'être envoyé à votre adresse <a href="mailot:{$identite.mail}">{$identite.mail}</a> (cette adresse est-elle correcte?). Si vous ne recevez pas ce mail, vérifiez dans les "Indésirables".</p>
    <p>Dans ce mail, vous trouverez un lien vers une adresse qui vous permettra de changer votre mot de passe.</p>
    <p>Attention, le lien ne fonctionnera que pendant 48h. Passé ce délai, il vous faudra faire une nouvelle demande.</p>
    <p>En cas de problème persistant, contactez <a href="mailto:{$MAILADMIN}">{$MAILADMIN}</a></p>

    <p class="pull-right">Pour retourner à la page d'accueil, <a href="../index.php">Cliquez ici</a>.</p>
{/if}

</div>  <!-- col-md-...-->

<div class="col-md-3 col-sm-6">
    <img src="../images/hautThot.png" alt="Thot">
</div>
