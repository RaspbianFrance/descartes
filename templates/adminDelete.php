<?php
	
	$incs = new internalIncs();
	$incs->head('Accueil');
?>
<?php 
	$incs->headerAdmin('dashboard');
?>
	<div class="section">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<h2 class="section-title">Confirmer suppression</h2>
				</div>
				<div class="col-lg-12">
					Voulez vous vraiment supprimer cette ligne ?
					<div class="clearfix"></div>
					<a class="btn btn-danger col-xs-5" href="<?php secho($this->generateUrl('admin', 'liste', array($table))); ?>" >Non</a>
					<a class="btn btn-success col-xs-5 col-xs-offset-2" href="<?php secho($this->generateUrl('admin', 'confirmDelete', array($table, $id, $_SESSION['csrf']))); ?>" >Oui</a>
				</div>
			</div> <!-- /.row -->
		</div> <!-- /.container -->
	</div> <!-- /.section -->
<?php
	$incs->footer();
