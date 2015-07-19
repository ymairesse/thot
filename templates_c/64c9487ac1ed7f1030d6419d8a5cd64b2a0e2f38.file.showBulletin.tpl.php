<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-27 10:23:17
         compiled from "./templates/showBulletin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:912930110556b172b9a8d06-03300271%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '64c9487ac1ed7f1030d6419d8a5cd64b2a0e2f38' => 
    array (
      0 => './templates/showBulletin.tpl',
      1 => 1435393392,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '912930110556b172b9a8d06-03300271',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_556b172b9acf31_41365891',
  'variables' => 
  array (
    'noBulletin' => 0,
    'listeCoursGrp' => 0,
    'coursGrp' => 0,
    'unCours' => 0,
    'listeProfsCoursGrp' => 0,
    'matricule' => 0,
    'commentaires' => 0,
    'sitPrecedentes' => 0,
    'sitActuelles' => 0,
    'sitActuelle' => 0,
    'sitAct' => 0,
    'cotesPonderees' => 0,
    'listeCotes' => 0,
    'uneCote' => 0,
    'cours' => 0,
    'idComp' => 0,
    'listeCompetences' => 0,
    'attitudes' => 0,
    'uneBranche' => 0,
    'ficheEduc' => 0,
    'ANNEESCOLAIRE' => 0,
    'annee' => 0,
    'bulletin' => 0,
    'mention' => 0,
    'laMention' => 0,
    'remTitu' => 0,
    'noticeDirection' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556b172b9acf31_41365891')) {function content_556b172b9acf31_41365891($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['noBulletin']->value)) {?>
	
	<h1>Bulletin n° <?php echo $_smarty_tpl->tpl_vars['noBulletin']->value;?>
</h1>

	<?php if (isset($_smarty_tpl->tpl_vars['listeCoursGrp']->value)) {?>

	<?php  $_smarty_tpl->tpl_vars['unCours'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['unCours']->_loop = false;
 $_smarty_tpl->tpl_vars['coursGrp'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listeCoursGrp']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['unCours']->key => $_smarty_tpl->tpl_vars['unCours']->value) {
$_smarty_tpl->tpl_vars['unCours']->_loop = true;
 $_smarty_tpl->tpl_vars['coursGrp']->value = $_smarty_tpl->tpl_vars['unCours']->key;
?>

	<div class="row" style="border-bottom: 1px solid black; padding-bottom:0.5em">
		
		<div class="col-md-6 col-sm-12">
			<h2 class="titreCours" title="<?php echo $_smarty_tpl->tpl_vars['coursGrp']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['unCours']->value['libelle'];?>
 <?php echo $_smarty_tpl->tpl_vars['unCours']->value['nbheures'];?>
h</h2>
			<p><?php echo $_smarty_tpl->tpl_vars['listeProfsCoursGrp']->value[$_smarty_tpl->tpl_vars['coursGrp']->value];?>
</p>

			<?php if (isset($_smarty_tpl->tpl_vars['commentaires']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value])&&($_smarty_tpl->tpl_vars['commentaires']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value][$_smarty_tpl->tpl_vars['noBulletin']->value]!='')) {?>
			<table class="tableauBulletin">
				<tr>
					<th>Remarque</th>
				</tr>
				<tr>
					<td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['commentaires']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value][$_smarty_tpl->tpl_vars['noBulletin']->value])===null||$tmp==='' ? '&nbsp;' : $tmp);?>
</td>
				</tr>
			</table>
			<?php }?>
		</div>
		
		<div class="col-md-6 col-sm-12">&nbsp;
		</div>
		
		<div class="col-md-6 col-sm-12">
			<?php if (isset($_smarty_tpl->tpl_vars['sitPrecedentes']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value]['sit'])) {?>
				<strong>Situation précédente: <?php echo $_smarty_tpl->tpl_vars['sitPrecedentes']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value]['sit'];?>
 / <?php echo $_smarty_tpl->tpl_vars['sitPrecedentes']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value]['maxSit'];?>
</strong>
			<?php }?>
		</div>
		
		<div class="col-md-6 col-sm-12">
			<?php if (isset($_smarty_tpl->tpl_vars['sitActuelles']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value]['sit'])) {?>
				<?php $_smarty_tpl->tpl_vars['sitActuelle'] = new Smarty_variable($_smarty_tpl->tpl_vars['sitActuelles']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value], null, 0);?>
				<span style="display:block; border: 1px solid black; color: white; background-color: #555;">
				<strong>Situation actuelle: <?php echo $_smarty_tpl->tpl_vars['sitActuelle']->value['sit'];?>
 / <?php echo $_smarty_tpl->tpl_vars['sitActuelle']->value['maxSit'];?>
</strong>
				<?php if ($_smarty_tpl->tpl_vars['sitActuelle']->value['maxSit']>0) {?>
					<?php $_smarty_tpl->tpl_vars['sitAct'] = new Smarty_variable(100*$_smarty_tpl->tpl_vars['sitActuelle']->value['sit']/$_smarty_tpl->tpl_vars['sitActuelle']->value['maxSit'], null, 0);?>
					<span class="micro">soit <?php echo number_format($_smarty_tpl->tpl_vars['sitAct']->value,1);?>
%</span>
				<?php }?>
		
				</span>
			<?php }?>
		</div>

		<div class="col-md-6 col-sm-12">	

				<table class="tableauBulletin">
					<tr>
						<th style="width:15em">Compétence</th>

						<th style="width: 6em; text-align:center" colspan="2" class="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['cotesPonderees']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value]['form']['echec'])===null||$tmp==='' ? '' : $tmp);?>
">
							TJ: <strong>
								<?php if (isset($_smarty_tpl->tpl_vars['cotesPonderees']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value]['form']['cote'])) {?>
									<?php echo $_smarty_tpl->tpl_vars['cotesPonderees']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value]['form']['cote'];?>
/<?php echo $_smarty_tpl->tpl_vars['cotesPonderees']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value]['form']['max'];?>

								<?php } else { ?>
									&nbsp;
								<?php }?>
								</strong>
						</th>

						<th style="width: 6em; text-align:center" colspan="2" class="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['cotesPonderees']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value]['cert']['echec'])===null||$tmp==='' ? '' : $tmp);?>
">
							Cert: <strong>
								<?php if (isset($_smarty_tpl->tpl_vars['cotesPonderees']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value]['cert']['cote'])) {?>
									<?php echo $_smarty_tpl->tpl_vars['cotesPonderees']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value]['cert']['cote'];?>
/<?php echo $_smarty_tpl->tpl_vars['cotesPonderees']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value]['cert']['max'];?>

								<?php } else { ?>
									&nbsp;
							<?php }?>
							</strong>
						</th>

					</tr>
					
					<?php if (isset($_smarty_tpl->tpl_vars['listeCotes']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value])) {?>
					
					<?php $_smarty_tpl->tpl_vars['lesCotes'] = new Smarty_variable($_smarty_tpl->tpl_vars['listeCotes']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value], null, 0);?>
					<?php $_smarty_tpl->tpl_vars['cours'] = new Smarty_variable($_smarty_tpl->tpl_vars['unCours']->value['cours'], null, 0);?>
						<?php  $_smarty_tpl->tpl_vars['uneCote'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['uneCote']->_loop = false;
 $_smarty_tpl->tpl_vars['idComp'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listeCotes']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['coursGrp']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['uneCote']->key => $_smarty_tpl->tpl_vars['uneCote']->value) {
$_smarty_tpl->tpl_vars['uneCote']->_loop = true;
 $_smarty_tpl->tpl_vars['idComp']->value = $_smarty_tpl->tpl_vars['uneCote']->key;
?>
						<?php if (($_smarty_tpl->tpl_vars['uneCote']->value['form']['cote']!='')||($_smarty_tpl->tpl_vars['uneCote']->value['cert']['cote']!='')) {?>
						<tr>
							<td><?php echo $_smarty_tpl->tpl_vars['listeCompetences']->value[$_smarty_tpl->tpl_vars['cours']->value][$_smarty_tpl->tpl_vars['idComp']->value]['libelle'];?>
</td>
							<td style="width: 3em; text-align:center" class="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['uneCote']->value['form']['echec'])===null||$tmp==='' ? '' : $tmp);?>
">
								<?php echo (($tmp = @$_smarty_tpl->tpl_vars['uneCote']->value['form']['cote'])===null||$tmp==='' ? '&nbsp;' : $tmp);?>
</td>
							<td style="width: 3em; text-align:center" class="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['uneCote']->value['form']['echec'])===null||$tmp==='' ? '' : $tmp);?>
">
								<?php echo (($tmp = @$_smarty_tpl->tpl_vars['uneCote']->value['form']['maxForm'])===null||$tmp==='' ? '&nbsp;' : $tmp);?>
</td>
							<td style="width: 3em; text-align:center" class="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['uneCote']->value['cert']['echec'])===null||$tmp==='' ? '' : $tmp);?>
">
								<?php echo (($tmp = @$_smarty_tpl->tpl_vars['uneCote']->value['cert']['cote'])===null||$tmp==='' ? '&nbsp;' : $tmp);?>
</td>
							<td style="width: 3em; text-align:center" class="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['uneCote']->value['cert']['echec'])===null||$tmp==='' ? '' : $tmp);?>
">
								<?php echo (($tmp = @$_smarty_tpl->tpl_vars['uneCote']->value['cert']['maxCert'])===null||$tmp==='' ? '&nbsp;' : $tmp);?>
</td>
						</tr>
						<?php }?>
						<?php } ?>
						
					<?php }?>
				</table>
				
		</div>  <!-- col-md-... -->
		


	</div>  <!-- row -->
		
	<?php } ?>
	
	<?php }?>  

		<div class="row">
			
		<!-- attitudes -->
		<?php if ($_smarty_tpl->tpl_vars['attitudes']->value) {?>
		<div class="col-md-6 col-sm-12">
			<div class="table-responsive">
				<table class="table table-condensed">
					<tr>
						<th style="vertical-align:bottom; text-align:center;">Attitudes</th>
						<?php  $_smarty_tpl->tpl_vars['uneBranche'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['uneBranche']->_loop = false;
 $_smarty_tpl->tpl_vars['coursGrp'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['attitudes']->value[$_smarty_tpl->tpl_vars['noBulletin']->value][$_smarty_tpl->tpl_vars['matricule']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['uneBranche']->key => $_smarty_tpl->tpl_vars['uneBranche']->value) {
$_smarty_tpl->tpl_vars['uneBranche']->_loop = true;
 $_smarty_tpl->tpl_vars['coursGrp']->value = $_smarty_tpl->tpl_vars['uneBranche']->key;
?>
							<th><img src="imagesCours/<?php echo $_smarty_tpl->tpl_vars['uneBranche']->value['cours'];?>
.png" alt="<?php echo $_smarty_tpl->tpl_vars['cours']->value;?>
"></th>
						<?php } ?>
					</tr>
					<tr>
						<td>Respect des autres</td>
						<?php  $_smarty_tpl->tpl_vars['uneBranche'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['uneBranche']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['attitudes']->value[$_smarty_tpl->tpl_vars['noBulletin']->value][$_smarty_tpl->tpl_vars['matricule']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['uneBranche']->key => $_smarty_tpl->tpl_vars['uneBranche']->value) {
$_smarty_tpl->tpl_vars['uneBranche']->_loop = true;
?>
							<td <?php if ($_smarty_tpl->tpl_vars['uneBranche']->value['att1']=='N') {?>class="echec"<?php }?>><?php echo $_smarty_tpl->tpl_vars['uneBranche']->value['att1'];?>
</td>
						<?php } ?>
					</tr>
						<tr>
						<td>Respect des consignes</td>
						<?php  $_smarty_tpl->tpl_vars['uneBranche'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['uneBranche']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['attitudes']->value[$_smarty_tpl->tpl_vars['noBulletin']->value][$_smarty_tpl->tpl_vars['matricule']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['uneBranche']->key => $_smarty_tpl->tpl_vars['uneBranche']->value) {
$_smarty_tpl->tpl_vars['uneBranche']->_loop = true;
?>
							<td <?php if ($_smarty_tpl->tpl_vars['uneBranche']->value['att2']=='N') {?>class="echec"<?php }?>><?php echo $_smarty_tpl->tpl_vars['uneBranche']->value['att2'];?>
</td>
						<?php } ?>
					</tr>
						<tr>
						<td>Volonté de progresser</td>
						<?php  $_smarty_tpl->tpl_vars['uneBranche'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['uneBranche']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['attitudes']->value[$_smarty_tpl->tpl_vars['noBulletin']->value][$_smarty_tpl->tpl_vars['matricule']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['uneBranche']->key => $_smarty_tpl->tpl_vars['uneBranche']->value) {
$_smarty_tpl->tpl_vars['uneBranche']->_loop = true;
?>
							<td <?php if ($_smarty_tpl->tpl_vars['uneBranche']->value['att3']=='N') {?>class="echec"<?php }?>><?php echo $_smarty_tpl->tpl_vars['uneBranche']->value['att3'];?>
</td>
						<?php } ?>
					</tr>
						<tr>
						<td>Ordre et soin</td>
						<?php  $_smarty_tpl->tpl_vars['uneBranche'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['uneBranche']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['attitudes']->value[$_smarty_tpl->tpl_vars['noBulletin']->value][$_smarty_tpl->tpl_vars['matricule']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['uneBranche']->key => $_smarty_tpl->tpl_vars['uneBranche']->value) {
$_smarty_tpl->tpl_vars['uneBranche']->_loop = true;
?>
							<td <?php if ($_smarty_tpl->tpl_vars['uneBranche']->value['att4']=='N') {?>class="echec"<?php }?>><?php echo $_smarty_tpl->tpl_vars['uneBranche']->value['att4'];?>
</td>
						<?php } ?>
					</tr>
				</table>
			</div>  <!-- table-responsive -->

		</div>  <!-- col-md-... -->
		<?php }?>
		
		<!-- Éducateurs -->
		<?php if ($_smarty_tpl->tpl_vars['ficheEduc']->value==1) {?>
		<div class="col-md-6 col-sm-12">
			<table class="tableauBulletin remarque">
				<tr>
					<th>Note des éducateurs</th>
				</tr>
				<tr>
					<td>Feuille de comportements jointe au bulletin; à signer par les parents.</td>
				</tr>
			</table>
		</div>
		<?php }?>
		
		<?php $_smarty_tpl->tpl_vars['laMention'] = new Smarty_variable((($tmp = @$_smarty_tpl->tpl_vars['mention']->value[$_smarty_tpl->tpl_vars['matricule']->value][$_smarty_tpl->tpl_vars['ANNEESCOLAIRE']->value][$_smarty_tpl->tpl_vars['annee']->value][$_smarty_tpl->tpl_vars['bulletin']->value])===null||$tmp==='' ? null : $tmp), null, 0);?>
		<div class="col-md-6 col-sm-12">
			<?php if (isset($_smarty_tpl->tpl_vars['laMention']->value)) {?>
			<div class="alert alert-info">
				<strong><i class="fa fa-graduation-cap fa-lg"></i> Mention: <?php echo (($tmp = @$_smarty_tpl->tpl_vars['laMention']->value)===null||$tmp==='' ? '' : $tmp);?>
</strong>
			</div>
			<?php }?>
			
			<table class="table table-condensed">
				<thead>
					<tr>
						<th>
							<h3>Avis du titulaire ou du Conseil de Classe</h3>
						</th>
					</tr>
				</thead>
				<tr>
					<td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['remTitu']->value)===null||$tmp==='' ? '&nbsp;' : $tmp);?>
</td>
				</tr>
			</table>
		
			<?php if (isset($_smarty_tpl->tpl_vars['noticeDirection']->value)) {?>
			<table class="table">
				<tr>
					<thead>
					<th><h3>Informations de la direction et des coordinateurs</h3></th>
					</thead>
				</tr>
				<tr>
					<td><?php echo $_smarty_tpl->tpl_vars['noticeDirection']->value;?>
</td>
				</tr>
			</table>
			<?php }?>
		</div>

	</div>  <!-- row -->


<?php }?>  


<?php echo '<script'; ?>
 type="text/javascript">

	$(document).ready(function(){
		
		$().UItoTop({ easingType: 'easeOutQuart' });
		
	})

<?php echo '</script'; ?>
>


<?php }} ?>
