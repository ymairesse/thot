<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-07 16:21:49
         compiled from "/home/yves/www/thot/templates/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14799386355574537d7cacf3-03225928%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e85307b903d80a6ffe46aa284308085ff9e3fadb' => 
    array (
      0 => '/home/yves/www/thot/templates/footer.tpl',
      1 => 1432993206,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14799386355574537d7cacf3-03225928',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'identiteReseau' => 0,
    'executionTime' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5574537d81c1d5_10545397',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5574537d81c1d5_10545397')) {function content_5574537d81c1d5_10545397($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/yves/www/thot/smarty/plugins/modifier.date_format.php';
?><div style="padding-bottom: 60px"></div>
<div class="hidden-print navbar-xs navbar-default navbar-fixed-bottom" style="padding-top:10px">
	<span class="hidden-xs">
		Le <?php echo smarty_modifier_date_format(time(),"%A, %e %b %Y");?>
 à <?php echo smarty_modifier_date_format(time(),"%Hh%M");?>
 
		Adresse IP: <strong><?php echo $_smarty_tpl->tpl_vars['identiteReseau']->value['ip'];?>
</strong> <?php echo $_smarty_tpl->tpl_vars['identiteReseau']->value['hostname'];?>

		Votre passage est enregistré
			<span id="execTime" class="pull-right"><?php if ($_smarty_tpl->tpl_vars['executionTime']->value) {?>Temps d'exécution du script: <?php echo $_smarty_tpl->tpl_vars['executionTime']->value;?>
s<?php }?></span>
	</span>
	
	<span class="visible-xs">
		<?php echo $_smarty_tpl->tpl_vars['identiteReseau']->value['ip'];?>
 <?php echo $_smarty_tpl->tpl_vars['identiteReseau']->value['hostname'];?>
 <?php echo smarty_modifier_date_format(time(),"%A, %e %b %Y");?>
 <?php echo smarty_modifier_date_format(time(),"%Hh%M");?>
 
	</span>

</div>  <!-- navbar -->

<?php }} ?>
