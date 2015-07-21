<?php
	
	$incs = new internalIncs();
	$incs->head('Accueil');
?>
<?php 
	$incs->headerAdmin($table);
?>
	<div class="section admin-section">
		<div class="container">
			<div class="row">
				<?php
				if ($nbDelete !== null)
				{
					if (!$nbDelete)
					{
					?>
						<div class="alert alert-danger" role="alert">
							<span class="fa fa-exclamation-triangle" aria-hidden="true"></span>
							<span class="sr-only">Échec : </span>
							Impossible d'ajouter la ligne !
						</div>
					<?php
					}
					else
					{
					?>
						<div class="alert alert-success" role="alert">
							<span class="fa fa-check-circle" aria-hidden="true"></span>
							<span class="sr-only">Succès : </span>
							La ligne a été correctement insérée !
						</div>
					<?php
					}
				}
				?>

				<div class="col-lg-12">
					<h2 class="section-title">Contenu table <?php secho($table); ?></h2>
				</div>
				<div class="col-lg-12">
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<?php
										foreach ($fields as $nom => $field)
										{
										?>
											<th><?php secho($nom); ?></th>
										<?php
										}
									?>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($lignes as $ligne)
									{
									?>
										<tr>
											<?php
												foreach ($ligne as $nom => $value)
												{
												?>
													<td><?php secho($value); ?></td>
												<?php
												}
											?>
											<td>
												<div class="btn-group action-dropdown" target="#table-commands">
													<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action pour la sélection <span class="caret"></span></button>
													<ul class="dropdown-menu pull-right" role="menu">
														<li><a href="<?php echo $this->generateUrl('admin', 'edit', array($table, $ligne['id'])); ?>"><span class="fa fa-edit"></span> Modifier</a></li>
														<li><a href="<?php echo $this->generateUrl('admin', 'deleteLigne', array($table, $ligne['id'])); ?>"><span class="fa fa-trash-o"></span> Supprimer</a></li>
													</ul>
												</div>
											</td>
										</tr>
									<?php
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="text-center">
					<?php $incs->paginate($pagination); ?>
				</div>
			</div> <!-- /.row -->
		</div> <!-- /.container -->
	</div> <!-- /.section -->
<?php
	$incs->footer();
