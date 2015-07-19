<nav class="navbar navbar-default" role="navigation">

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
			<li class="active"><a href="index.php?action=annonces"><i class="fa fa-info-circle" style="color:orange"></i> Annonces</a></li>
			<li><a href="index.php?action=bulletin"><i class="fa fa-graduation-cap" style="color:blue"></i> Bulletins</a></li>
			<li><a href="index.php?action=anniversaires"><i class="fa fa-birthday-cake" style="color:red"></i> Anniversaires</a></li>
			<li><a href="index.php?action=jdc"><i class="fa fa-newspaper-o" style="color:#4AB23A"></i> J. de classe</a></li>
			<li><a href="index.php?action=parents"><i class="fa fa-users" style="color:#EAA6B1"></i> Parents</a></li>
			<li><a href="http://mail.isnd-edu.be" target="_blank"><i class="fa fa-paper-plane"></i> Mails</a></li>
			<li><a href="http://isnd.be/claroline" target="_blank"><img src="images/clarolineIco.png" alt="Cc"> Claroline</a></li>
		</ul>

		<ul class="nav navbar-nav pull-right">

			<li class="dropdown">
				<a href="#" data-toggle="dropdown"> {$nom} <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="index.php?action=logoff"><span class="glyphicon glyphicon-off">&nbsp;</span>Se déconnecter</a></li>
				</ul>
			</li>

		</ul>

	</div>  <!-- #barreNavigation -->

</nav>
