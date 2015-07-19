<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-05-31 16:52:33
         compiled from "./templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:196715560555698a78d74f61-57473195%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b52ca9476f204212b535d425093143084e133109' => 
    array (
      0 => './templates/index.tpl',
      1 => 1433083939,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '196715560555698a78d74f61-57473195',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_55698a78d898a3_31453027',
  'variables' => 
  array (
    'TITREGENERAL' => 0,
    'selecteur' => 0,
    'corpsPage' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55698a78d898a3_31453027')) {function content_55698a78d898a3_31453027($_smarty_tpl) {?><!DOCTYPE html>
<html lang="fr">
<head>
	<title><?php echo $_smarty_tpl->tpl_vars['TITREGENERAL']->value;?>
</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<link rel="stylesheet" href="screen.css" type="text/css" media="screen">
	<link rel="stylesheet" href="print.css" type="text/css" media="print">
	<link rel="stylesheet" href="bootstrap/fa/css/font-awesome.min.css" type="text/css" media="screen, print">
		
	<?php echo '<script'; ?>
 type="text/javascript" src="js/jquery-2.1.3.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="js/jquery.validate.js"><?php echo '</script'; ?>
>
</head>
<body>
	<div class="container">
		
		<?php echo $_smarty_tpl->getSubTemplate ("entete.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		
		<?php echo $_smarty_tpl->getSubTemplate ("menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		
		<?php if (isset($_smarty_tpl->tpl_vars['selecteur']->value)) {?>
			<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['selecteur']->value).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		<?php }?>
		
		
		<div id="corpsPage">
		<?php if (isset($_smarty_tpl->tpl_vars['corpsPage']->value)) {?>
			<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['corpsPage']->value).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		<?php }?>
		</div>
		
	</div>  <!-- container -->

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>



<?php echo '<script'; ?>
 type="text/javascript">
	
$(document).ready (function() {
	
	$("input:enabled").eq(0).focus();
	
	$("*[title]").tooltip();
	
	$(".pop").popover({
		trigger:'hover'
		});	
		
})

<?php echo '</script'; ?>
>

</body>

</html>

<?php }} ?>
