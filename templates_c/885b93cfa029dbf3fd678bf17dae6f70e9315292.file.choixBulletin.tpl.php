<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-05-31 15:19:26
         compiled from "./templates/choixBulletin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1251632467556aece5b72f87-08589419%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '885b93cfa029dbf3fd678bf17dae6f70e9315292' => 
    array (
      0 => './templates/choixBulletin.tpl',
      1 => 1433078365,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1251632467556aece5b72f87-08589419',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_556aece5b7e793_55545223',
  'variables' => 
  array (
    'listeBulletins' => 0,
    'bulletin' => 0,
    'noBulletin' => 0,
    'action' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556aece5b7e793_55545223')) {function content_556aece5b7e793_55545223($_smarty_tpl) {?><div class="selecteur">

<form name="selectBulletin" id="selectBulletin" action="index.php" method="POST" class="form-inline" role="form">
Bulletin n°
<?php  $_smarty_tpl->tpl_vars['bulletin'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['bulletin']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listeBulletins']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['bulletin']->key => $_smarty_tpl->tpl_vars['bulletin']->value) {
$_smarty_tpl->tpl_vars['bulletin']->_loop = true;
?>
	<?php echo $_smarty_tpl->tpl_vars['bulletin']->value;?>
 <input type="radio" value="<?php echo $_smarty_tpl->tpl_vars['bulletin']->value;?>
" name="noBulletin" <?php if ($_smarty_tpl->tpl_vars['bulletin']->value==$_smarty_tpl->tpl_vars['noBulletin']->value) {?> checked<?php }?>>
<?php } ?>
<button type="submit" class="btn btn-primary btn-xs">OK</button>
<input type="hidden" name="action" value="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">

</form>

</div><?php }} ?>
