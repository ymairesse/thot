<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-18 17:08:59
         compiled from "./templates/anniversaires.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3036537955582d3a8631203-67021734%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5a7815918ceffee507d6ccddcc607da9657f98c2' => 
    array (
      0 => './templates/anniversaires.tpl',
      1 => 1434639858,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3036537955582d3a8631203-67021734',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5582d3a86cd704_88962248',
  'variables' => 
  array (
    'anniversaires' => 0,
    'unEleve' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5582d3a86cd704_88962248')) {function content_5582d3a86cd704_88962248($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/yves/www/thot/smarty/plugins/modifier.date_format.php';
?><h3>Aujourd'hui le <?php echo smarty_modifier_date_format(time(),'%d/%m	');?>
, Joyeux Anniversaire à</h3>
<ul>
<?php  $_smarty_tpl->tpl_vars['unEleve'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['unEleve']->_loop = false;
 $_smarty_tpl->tpl_vars['matricule'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['anniversaires']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['unEleve']->key => $_smarty_tpl->tpl_vars['unEleve']->value) {
$_smarty_tpl->tpl_vars['unEleve']->_loop = true;
 $_smarty_tpl->tpl_vars['matricule']->value = $_smarty_tpl->tpl_vars['unEleve']->key;
?>
	<li class="anniversaire"><?php echo $_smarty_tpl->tpl_vars['unEleve']->value['nomPrenom'];?>
 [<?php echo $_smarty_tpl->tpl_vars['unEleve']->value['groupe'];?>
]</li>
<?php } ?>
</ul><?php }} ?>
