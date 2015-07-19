<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-18 17:09:51
         compiled from "./templates/menu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:496880514556b17eed6ec04-44449970%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8df58ed73dd677459efed4a82af554ffcbb7ec15' => 
    array (
      0 => './templates/menu.tpl',
      1 => 1434640185,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '496880514556b17eed6ec04-44449970',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_556b17eed7b270_28267259',
  'variables' => 
  array (
    'nom' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556b17eed7b270_28267259')) {function content_556b17eed7b270_28267259($_smarty_tpl) {?><nav class="navbar navbar-default" role="navigation">
	
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
			<li class="active"><a href="index.php?action=annonces"><i class="fa fa-info-circle" style="color:orange"></i> Les annonces</a></li>
			<li><a href="index.php?action=bulletin"><i class="fa fa-graduation-cap" style="color:blue"></i> Mes bulletins</a></li>
			<li><a href="index.php?action=anniversaires"><i class="fa fa-birthday-cake" style="color:red"></i> Anniversaires</a></li>
			<li><a href="index.php?action=jdc"><i class="fa fa-newspaper-o" style="color:#4AB23A"></i> Journal de classe</a></li>
			<li><a href="http://mail.isnd-edu.be" target="_blank"><i class="fa fa-paper-plane"></i> Mes mails</a></li>
			<li><a href="http://isnd.be/claroline" target="_blank"><img src="images/clarolineIco.png" alt="Cc"> Claroline</a></li>
		</ul>
	
		<ul class="nav navbar-nav pull-right">
			
			<li class="dropdown">
				<a href="#" data-toggle="dropdown"> <?php echo $_smarty_tpl->tpl_vars['nom']->value;?>
 <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="index.php?action=logoff"><span class="glyphicon glyphicon-off">&nbsp;</span>Se déconnecter</a></li>
				</ul>
			</li>
			
		</ul>
	
	</div>  <!-- #barreNavigation -->

</nav>

<?php }} ?>
