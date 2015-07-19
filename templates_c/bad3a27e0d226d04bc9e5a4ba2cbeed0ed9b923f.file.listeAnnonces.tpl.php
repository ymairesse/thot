<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-27 16:53:20
         compiled from "./templates/listeAnnonces.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1775542631558eac956e1f61-25847686%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bad3a27e0d226d04bc9e5a4ba2cbeed0ed9b923f' => 
    array (
      0 => './templates/listeAnnonces.tpl',
      1 => 1435416797,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1775542631558eac956e1f61-25847686',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_558eac956f53e4_21060367',
  'variables' => 
  array (
    'matricule' => 0,
    'listeAnnonces' => 0,
    'uneAnnonce' => 0,
    'classe' => 0,
    'niveau' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_558eac956f53e4_21060367')) {function content_558eac956f53e4_21060367($_smarty_tpl) {?><div id="annoncesPerso">
	
	<?php if (isset($_smarty_tpl->tpl_vars['listeAnnonces']->value[$_smarty_tpl->tpl_vars['matricule']->value])) {?>
	
		<?php  $_smarty_tpl->tpl_vars['uneAnnonce'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['uneAnnonce']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listeAnnonces']->value[$_smarty_tpl->tpl_vars['matricule']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['uneAnnonce']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['uneAnnonce']->key => $_smarty_tpl->tpl_vars['uneAnnonce']->value) {
$_smarty_tpl->tpl_vars['uneAnnonce']->_loop = true;
 $_smarty_tpl->tpl_vars['uneAnnonce']->index++;
?>
			<div id="perso<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->index;?>
" class="lesAnnonces">
				<h4><?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['dateDebut'];?>
: <?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['objet'];?>
</h4>
				<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['texte'];?>

				<span class="pull-right contact">Contact: <?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['proprietaire'];?>
</span>
			</div>
		<?php } ?>
	
	<?php }?>
	
</div>

<div id="annoncesClasse">
	
	<?php if (isset($_smarty_tpl->tpl_vars['listeAnnonces']->value[$_smarty_tpl->tpl_vars['classe']->value])) {?>
	
		<?php  $_smarty_tpl->tpl_vars['uneAnnonce'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['uneAnnonce']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listeAnnonces']->value[$_smarty_tpl->tpl_vars['classe']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['uneAnnonce']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['uneAnnonce']->key => $_smarty_tpl->tpl_vars['uneAnnonce']->value) {
$_smarty_tpl->tpl_vars['uneAnnonce']->_loop = true;
 $_smarty_tpl->tpl_vars['uneAnnonce']->index++;
?>
			<div id="classe<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->index;?>
" class="lesAnnonces">		
				<h4><?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['dateDebut'];?>
: <?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['objet'];?>
</h4>
				<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['texte'];?>

				<span class="pull-right contact">Contact: <?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['proprietaire'];?>
</span>
			</div>
		<?php } ?>
	
	<?php }?>
	
</div>

<div id="annoncesPerso">
	
	<?php if (isset($_smarty_tpl->tpl_vars['listeAnnonces']->value[$_smarty_tpl->tpl_vars['niveau']->value])) {?>
	
		<?php  $_smarty_tpl->tpl_vars['uneAnnonce'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['uneAnnonce']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listeAnnonces']->value[$_smarty_tpl->tpl_vars['niveau']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['uneAnnonce']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['uneAnnonce']->key => $_smarty_tpl->tpl_vars['uneAnnonce']->value) {
$_smarty_tpl->tpl_vars['uneAnnonce']->_loop = true;
 $_smarty_tpl->tpl_vars['uneAnnonce']->index++;
?>
			<div id="niveau<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->index;?>
" class="lesAnnonces">
				<h4><?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['dateDebut'];?>
: <?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['objet'];?>
</h4>
				<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['texte'];?>

				<p><span  class="pull-right contact">Contact: <?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['proprietaire'];?>
</span></p>
			</div>
		<?php } ?>
	
	<?php }?>
	
</div>

<div id="annoncesPerso">
	
	<?php if (isset($_smarty_tpl->tpl_vars['listeAnnonces']->value['ecole'])) {?>
	
		<?php  $_smarty_tpl->tpl_vars['uneAnnonce'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['uneAnnonce']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listeAnnonces']->value['ecole']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['uneAnnonce']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['uneAnnonce']->key => $_smarty_tpl->tpl_vars['uneAnnonce']->value) {
$_smarty_tpl->tpl_vars['uneAnnonce']->_loop = true;
 $_smarty_tpl->tpl_vars['uneAnnonce']->index++;
?>
			<div id="ecole<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->index;?>
" class="lesAnnonces">
				<h4><?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['dateDebut'];?>
: <?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['objet'];?>
</h4>
				<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['texte'];?>

				<p><span  class="pull-right contact">Contact: <?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['proprietaire'];?>
</span></p>
			</div>
		<?php } ?>
	
	<?php }?>
	
</div><?php }} ?>
