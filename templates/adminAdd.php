<?php
	global $db;
	$incs = new internalIncs();
	$incs->head('Ajout ligne');
?>
<?php 
	$incs->headerAdmin($table);
?>
	<div class="section admin-section">
		<div class="container">
			<div class="row">
				<?php $incs->alert(); ?>
				<div class="col-lg-12">
					<h2 class="section-title">Ajouter nouvelle entrée dans <?php secho($table); ?></h2>
				</div>
				<form role="form" method="POST" action="<?php secho($this->generateUrl('admin', 'create', array($table))); ?>">
					<div class="row">
					<?php
						foreach ($fields as $nom => $field)
						{
							//On passe les champs avec un autoincrement
							if ($field['AUTO_INCREMENT'])
							{
								continue;
							}

							//On gère les clefs etrangères
							$select = $field['FOREIGN'];

							switch ($field['TYPE'])
							{
								case 'INT' : 
									$type = 'number';
									break;
								default :
									$type = 'text';
							}

							switch ($field['TYPE'])
							{
								case 'DATE' : 
									$pattern = '[0-9]{4}-[0-9]{2}-[0-9]{2}';
									break;
								case 'DATETIME' : 
									$pattern = '[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}';
									break;
								default :
									$pattern = false;
							}

							?>
								<div class="form-group col-md-12">
									<label for="<?php secho($nom); ?>"><?php secho($nom); ?> <?php echo ($pattern ? '(' . $pattern . ')' : ''); ?></label>
									<?php if ($select) { ?>
										<select name="<?php secho($nom); ?>" class="form-control" id="<?php secho($nom); ?>" <?php echo (!$field['NULL'] && !$field['HAS_DEFAULT'] ? 'required' : ''); ?>>
											<?php foreach ($db->getPossibleValuesForForeign($table, $field['NAME']) as $possibleValue) { ?>
												<option value="<?php secho($possibleValue['possible_value']); ?>"><?php secho($possibleValue['possible_value']); ?></option>
											<?php } ?>
										</select>
									<?php } else { ?>
										<?php if ($field['TYPE'] == 'VARCHAR' && $field['SIZE'] > 255) { ?>
												<textarea name="<?php secho($nom); ?>" class="form-control textarea-admin" id="<?php secho($nom); ?>" <?php echo (!$field['NULL'] && !$field['HAS_DEFAULT'] ? 'required' : ''); ?>></textarea>
										<?php } else { ?>
												<input type="<?php echo $type; ?>" name="<?php secho($nom); ?>" class="form-control" id="<?php secho($nom); ?>" <?php echo (!$field['NULL'] && !$field['HAS_DEFAULT'] ? 'required' : ''); ?> <?php echo ($pattern ? 'pattern="' . $pattern . '"' : ''); ?> />
										<?php } ?>
									<?php } ?>
								</div>
							<?php
						}
					?>
						<div class="form-group col-lg-12">
							<button type="submit" class="btn btn-primary pull-right col-xs-12">Ajouter</button>
							<div class="clearfix"></div>
						</div>
					</div>
				</form>
			</div> <!-- /.row -->
		</div> <!-- /.container -->
	</div> <!-- /.section -->
<?php
	$incs->footer();
