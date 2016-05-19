<?php
	$incs = new \modules\DescartesAdministrator\internals\Incs();
	$incs->htmlHead();
	$incs->htmlNav($table);
?>
	<div class="section admin-section">
		<div class="container">
			<div class="row">
				<?php if ($nbLine !== false && $nbLine == 0) { ?>
					<div class="col-lg-12 error">
						Impossible d'insérer la ligne !
					</div>
				<?php } elseif ($nbLine) { ?>	
					<div class="col-lg-12 success">
						<?php $this->s($nbLine); ?> ligne(s) insérée(s) avec succès !
					</div>
				<?php } ?>
				<div class="col-lg-12">
					<h2 class="section-title">Ajouter nouvelle entrée dans <?php $this->s($table); ?></h2>
				</div>
				<form class="col-lg-12" role="form" method="POST" action="<?php $this->s($this->generateUrl('DescartesAdministratorAdmin', 'create', ['table' => $table, 'csrf' => $_SESSION['csrf']])); ?>">
					<div class="row">
						<?php foreach ($fieldsToShow as $fieldToShow) { ?>
							<div class="form-group col-md-12">
								<label for="<?php $this->s($fieldToShow['name']); ?>"><?php $this->s($fieldToShow['name']); ?> <?php echo ($fieldToShow['pattern'] ? '(' . $fieldToShow['pattern'] . ')' : ''); ?></label>
								<?php if ($fieldToShow['foreign']) { ?>
									<select name="<?php $this->s($fieldToShow['name']); ?>" class="form-control" id="<?php $this->s($fieldToShow['name']); ?>" <?php echo ($fieldToShow['required'] ? 'required' : ''); ?>>
										<?php foreach ($fieldToShow['possibleValues'] as $possibleValue) { ?>
											<option value="<?php $this->s($possibleValue); ?>"><?php $this->s($possibleValue); ?></option>
										<?php } ?>
									</select>
								<?php } elseif ($fieldToShow['textarea']) { ?>
										<textarea name="<?php $this->s($fieldToShow['name']); ?>" class="form-control textarea-admin" id="<?php $this->s($fieldToShow['name']); ?>" <?php echo ($fieldToShow['required'] ? 'required' : ''); ?>></textarea>
								<?php } else { ?>
										<input type="<?php echo $fieldToShow['type']; ?>" name="<?php $this->s($fieldToShow['name']); ?>" class="form-control" id="<?php $this->s($fieldToShow['name']); ?>" <?php echo ($fieldToShow['required'] ? 'required' : ''); ?> <?php echo ($fieldToShow['pattern'] ? 'pattern="' . $fieldToShow['pattern'] . '"' : ''); ?> />
								<?php } ?>
							</div>
						<?php } ?>
						<div class="form-group col-lg-12">
							<button type="submit" class="btn btn-primary pull-right col-xs-12">Ajouter</button>
							<div class="clearfix"></div>
						</div>
					</div>
				</form>
			</div> <!-- /.row -->
		</div> <!-- /.container -->
	</div> <!-- /.section -->
<?php $incs->htmlFooter(); ?>
