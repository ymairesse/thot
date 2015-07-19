<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-27 17:34:09
         compiled from "./templates/titresAnnonces.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1755605422558ea917ca38c3-52370601%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '04d3e07cc4da12e1ed4e5dc41f666609b586a4ec' => 
    array (
      0 => './templates/titresAnnonces.tpl',
      1 => 1435419248,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1755605422558ea917ca38c3-52370601',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_558ea917ce28a1_14339510',
  'variables' => 
  array (
    'nom' => 0,
    'matricule' => 0,
    'listeAnnonces' => 0,
    'uneAnnonce' => 0,
    'classe' => 0,
    'niveau' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_558ea917ce28a1_14339510')) {function content_558ea917ce28a1_14339510($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/home/yves/www/thot/smarty/plugins/modifier.truncate.php';
?><div class="panel-group listeAnnonces" id="accordion">
  

    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1"><?php echo $_smarty_tpl->tpl_vars['nom']->value;?>
 <span class="badge pull-right"><?php echo (($tmp = @count($_smarty_tpl->tpl_vars['listeAnnonces']->value[$_smarty_tpl->tpl_vars['matricule']->value]))===null||$tmp==='' ? 0 : $tmp);?>
</span></a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse in  annonces personnel">
	  
	  
        <div class="panel-body">
			<?php if (isset($_smarty_tpl->tpl_vars['listeAnnonces']->value[$_smarty_tpl->tpl_vars['matricule']->value])) {?>
				<ul>
				<?php  $_smarty_tpl->tpl_vars['uneAnnonce'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['uneAnnonce']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listeAnnonces']->value[$_smarty_tpl->tpl_vars['matricule']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['uneAnnonce']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['uneAnnonce']->key => $_smarty_tpl->tpl_vars['uneAnnonce']->value) {
$_smarty_tpl->tpl_vars['uneAnnonce']->_loop = true;
 $_smarty_tpl->tpl_vars['uneAnnonce']->index++;
?>
					<li class="urgence<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['urgence'];?>
"><a href="#perso<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->index;?>
" title="<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['dateDebut'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['uneAnnonce']->value['objet'],30);?>
</a></li>
				<?php } ?>
			</ul>
			<?php } else { ?>
				<p>Néant</p>
			<?php }?>
		</div>
		
		
      </div>
    </div>  <!-- panel-default -->
	

    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Classe <?php echo $_smarty_tpl->tpl_vars['classe']->value;?>
 <span class="badge pull-right"><?php echo (($tmp = @count($_smarty_tpl->tpl_vars['listeAnnonces']->value[$_smarty_tpl->tpl_vars['classe']->value]))===null||$tmp==='' ? 0 : $tmp);?>
</span></a>
        </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse annonces classe">
	  
	  
        <div class="panel-body">
		<?php if (isset($_smarty_tpl->tpl_vars['listeAnnonces']->value[$_smarty_tpl->tpl_vars['classe']->value])) {?>
			<ul>
			<?php  $_smarty_tpl->tpl_vars['uneAnnonce'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['uneAnnonce']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listeAnnonces']->value[$_smarty_tpl->tpl_vars['classe']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['uneAnnonce']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['uneAnnonce']->key => $_smarty_tpl->tpl_vars['uneAnnonce']->value) {
$_smarty_tpl->tpl_vars['uneAnnonce']->_loop = true;
 $_smarty_tpl->tpl_vars['uneAnnonce']->index++;
?>
				<li class="urgence<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['urgence'];?>
"><a href="#classe<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->index;?>
" title="<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['dateDebut'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['uneAnnonce']->value['objet'],30);?>
</a></li>
			<?php } ?>
			</ul>	
		<?php } else { ?>
			<p>Néant</p>
		<?php }?>
		</div>
		
		
      </div>
    </div>  <!-- panel-default -->
	

    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Élèves de <?php echo $_smarty_tpl->tpl_vars['niveau']->value;?>
<sup>e</sup> <span class="badge pull-right"><?php echo (($tmp = @count($_smarty_tpl->tpl_vars['listeAnnonces']->value[$_smarty_tpl->tpl_vars['niveau']->value]))===null||$tmp==='' ? 0 : $tmp);?>
</span></a>
        </h4>
      </div>
      <div id="collapse3" class="panel-collapse collapse annonces niveau">
	  
	  
        <div class="panel-body">

		<?php if (isset($_smarty_tpl->tpl_vars['listeAnnonces']->value[$_smarty_tpl->tpl_vars['niveau']->value])) {?>
			<ul>
				<?php  $_smarty_tpl->tpl_vars['uneAnnonce'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['uneAnnonce']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listeAnnonces']->value[$_smarty_tpl->tpl_vars['niveau']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['uneAnnonce']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['uneAnnonce']->key => $_smarty_tpl->tpl_vars['uneAnnonce']->value) {
$_smarty_tpl->tpl_vars['uneAnnonce']->_loop = true;
 $_smarty_tpl->tpl_vars['uneAnnonce']->index++;
?>
					<li class="urgence<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['urgence'];?>
"><a href="#niveau<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->index;?>
" title="<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['dateDebut'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['uneAnnonce']->value['objet'],30);?>
</a></li>
				<?php } ?>
			</ul>
		<?php } else { ?>
			<p>Néant</p>
		<?php }?>

			
		</div>
		
		
      </div>
    </div>  <!-- panel-default -->
	
	
	<div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Tous <span class="badge pull-right"><?php echo (($tmp = @count($_smarty_tpl->tpl_vars['listeAnnonces']->value['ecole']))===null||$tmp==='' ? 0 : $tmp);?>
</span></a>
        </h4>
      </div>
      <div id="collapse4" class="panel-collapse collapse annonces ecole">
	  
	  
        <div class="panel-body">
		
		<?php if (isset($_smarty_tpl->tpl_vars['listeAnnonces']->value['ecole'])) {?>
			<ul>
				<?php  $_smarty_tpl->tpl_vars['uneAnnonce'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['uneAnnonce']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listeAnnonces']->value['ecole']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['uneAnnonce']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['uneAnnonce']->key => $_smarty_tpl->tpl_vars['uneAnnonce']->value) {
$_smarty_tpl->tpl_vars['uneAnnonce']->_loop = true;
 $_smarty_tpl->tpl_vars['uneAnnonce']->index++;
?>
					<li class="urgence<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['urgence'];?>
"><a href="#ecole<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->index;?>
" title="<?php echo $_smarty_tpl->tpl_vars['uneAnnonce']->value['dateDebut'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['uneAnnonce']->value['objet'],30);?>
</a></li>
				<?php } ?>
			</ul>
		<?php } else { ?>
			<p>Néant</p>
		<?php }?>
			
		</div>
		
		
      </div>
    </div>  <!-- panel-default -->

<h3>Légende des couleurs</h3>
<ul>
	<li class="urgence0">Peu urgent</li>
	<li class="urgence1">Moyennement urgent</li>
	<li class="urgence2">Très urgent et important</li>
</ul>

</div>  <!-- accordion -->

<?php }} ?>
