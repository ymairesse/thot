<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-05-30 15:40:07
         compiled from "./templates/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:55449954055698a78d8b7b8-19195008%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e85307b903d80a6ffe46aa284308085ff9e3fadb' => 
    array (
      0 => './templates/footer.tpl',
      1 => 1432993206,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '55449954055698a78d8b7b8-19195008',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_55698a78da1565_74084000',
  'variables' => 
  array (
    'identiteReseau' => 0,
    'executionTime' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55698a78da1565_74084000')) {function content_55698a78da1565_74084000($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/yves/www/thot/smarty/plugins/modifier.date_format.php';
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
