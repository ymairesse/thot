<nav class="navbar navbar-default{if $userType =='parents'} parents{/if}" role="navigation">

	<div class="navbar-header">

		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#barreNavigation">
			<span class="sr-only">Navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>

		<a class="navbar-brand" href="index.php"><i class="fa fa-home"></i></a>

	</div>  <!-- navbar-header -->

	<div class="collapse navbar-collapse" id="barreNavigation">

		<ul class="nav navbar-nav">
			<li><a href="index.php?action=annonces"><i class="fa fa-info-circle" style="color:orange"></i> Annonces</a></li>
			<li><a href="index.php?action=bulletin"><i class="fa fa-graduation-cap" style="color:blue"></i> Bulletins</a></li>
			{if $userType == 'eleves'}
			<li><a href="index.php?action=anniversaires"><i class="fa fa-birthday-cake" style="color:red"></i> Anniversaires</a></li>
			{/if}
			<li><a href="index.php?action=jdc"><i class="fa fa-newspaper-o" style="color:#4AB23A"></i> J. de classe</a></li>
			{if $userType == 'eleves'}
			<li><a href="index.php?action=parents"><i class="fa fa-users" style="color:#EAA6B1"></i> Parents</a></li>
			{/if}
			{if $userType == 'parents'}
			<li><a href="index.php?action=profil"><i class="fa fa-user" style="color:#EAA6B1"></i> Profil</a></li>
			{/if}
			{if $userType == 'eleves'}
			<li><a href="http://mail.isnd-edu.be" target="_blank"><i class="fa fa-paper-plane"></i> Mails</a></li>
			<li><a href="http://isnd.be/claroline" target="_blank"><img src="images/clarolineIco.png" alt="Cc"> Claroline</a></li>
			{/If}
		</ul>

		<ul class="nav navbar-nav pull-right">

			<li class="dropdown">
				<a href="#" data-toggle="dropdown"> <span id="leNom">{$identite.prenom} {$identite.nom}</span> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="index.php?action=logoff"><span class="glyphicon glyphicon-off">&nbsp;</span>Se déconnecter</a></li>
				</ul>
			</li>

		</ul>

	</div>  <!-- #barreNavigation -->

</nav>
