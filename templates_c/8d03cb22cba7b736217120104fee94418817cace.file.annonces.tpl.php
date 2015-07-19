<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-27 16:52:22
         compiled from "./templates/annonces.tpl" */ ?>
<?php /*%%SmartyHeaderCode:652237581556b1e8f472637-13350764%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8d03cb22cba7b736217120104fee94418817cace' => 
    array (
      0 => './templates/annonces.tpl',
      1 => 1435416701,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '652237581556b1e8f472637-13350764',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_556b1e8f479ad9_60765462',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556b1e8f479ad9_60765462')) {function content_556b1e8f479ad9_60765462($_smarty_tpl) {?><div class="row">
	
	<div class="col-md-3 col-sm-12">
		
		<?php echo $_smarty_tpl->getSubTemplate ("titresAnnonces.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		
	</div>
	
	<div class="col-md-9 col-sm-12">
		
		<?php echo $_smarty_tpl->getSubTemplate ("listeAnnonces.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		
	</div>


</div>  <!-- row -->

<?php echo '<script'; ?>
 type="text/javascript">
	
$(document).ready(function(){
	
	$(".lesAnnonces").hide();
	
	$(".lesAnnonces").first().show();
	
	$(".listeAnnonces li a").click(function(){
		var link = $(this).attr('href');
		$(".lesAnnonces").hide();
		$(link).fadeIn();
		});
	
	})
	
<?php echo '</script'; ?>
><?php }} ?>
