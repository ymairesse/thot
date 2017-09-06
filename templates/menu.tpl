<nav class="navbar navbar-default{if $userType =='parents'} parents{/if}" role="navigation">

	<div class="navbar-header">

		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#barreNavigation">
			<span class="sr-only">Navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>

		<a class="navbar-brand" href="index.php"><i class="fa fa-home"></i></a>

	</div>
	<!-- navbar-header -->

	<div class="collapse navbar-collapse" id="barreNavigation">

		<ul class="nav navbar-nav">
			<li><a href="index.php?action=annonces"><i class="fa fa-info-circle" style="color:orange"></i> Annonces</a></li>
			<li><a href="index.php?action=documents"><i class="fa fa-folder-open-o" style="color:red"></i> ISND <br>Docs</a></li>
			<li><a href="index.php?action=bulletin"><i class="fa fa-graduation-cap" style="color:blue"></i> Bulletins</a></li>
			{if $userType == 'eleve'}
			<li><a href="index.php?action=anniversaires"><i class="fa fa-birthday-cake" style="color:red"></i> Anniversaires</a></li>
			{/if}
			<li><a href="index.php?action=casiers"><i class="fa fa-inbox"></i> Casiers<br>Virtuels</a></li>
			<li><a href="index.php?action=jdc"><i class="fa fa-newspaper-o" style="color:#4AB23A"></i> Jdc</a></li>
			{if $userType == 'eleve'}
			<li><a href="index.php?action=parents"><i class="fa fa-users" style="color:#EAA6B1"></i> Parents</a></li>
			{/if}
			<li><a href="index.php?action=form"><i class="fa fa-pencil" style="color:#55aaaa"></i> Formulaires</a></li>
			{* <li><a href="index.php?action=mails"><i class="fa fa-send-o"></i> Communiquer</a></li> *}
			{if $userType == 'parent'}
			<li><a href="index.php?action=profil"><i class="fa fa-user" style="color:#EAA6B1"></i> Profil</a></li>
			<li><a href="index.php?action=contact"><i class="fa fa-envelope-o" style="color:#ff0000"></i> Contact</a></li>
			<li><a href="index.php?action=reunionParents"><i class="fa fa-calendar" style="color:#16931b"></i> Réunion de parents</a></li>
			{/if}
			<li><a href="index.php?action=info" title="Informations"><i class="fa fa-info-circle" style="color:blue"></i></a></li>
		</ul>

		<ul class="nav navbar-nav pull-right">

			<li class="dropdown">
				<a href="#" data-toggle="dropdown">
					<span id="leNom">{$identite.prenom} {$identite.nom}</span> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li>
						<a href="index.php?action=logoff">
							<span class="glyphicon glyphicon-off">&nbsp;</span>Se déconnecter</a>
					</li>
				</ul>
			</li>

		</ul>

	</div>
	<!-- #barreNavigation -->

</nav>
