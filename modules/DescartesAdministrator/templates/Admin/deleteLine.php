<?php
	$incs = new \modules\DescartesAdministrator\internals\Incs();
	$incs->htmlHead();
	$incs->htmlNav($table);
?>
	<div class="section admin-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<h2 class="section-title text-center">Confirmer suppression</h2>
				</div>
				<div class="col-lg-12">
					<div class="confirm-delete-text">
						Voulez vous vraiment supprimer la ligne de la table <?php $this->s($table); ?> correspondant à "<?php $this->s($primary); ?>" ?
					</div>
					<div class="clearfix"></div>
					<a class="btn btn-danger col-xs-5" href="javascript:history.back()" >Non</a>
					<a class="btn btn-success col-xs-5 col-xs-offset-2" href="<?php $this->s($this->generateUrl($this, 'destroyLine', ['table' => $table, 'primary' => $primary, 'csrf' => $_SESSION['csrf']])); ?>" >Oui</a>
				</div>
			</div> <!-- /.row -->
		</div> <!-- /.container -->
	</div> <!-- /.section -->
<?php $incs->htmlFooter(); ?>
