<?php
	
	$incs = new internalIncs();
	$incs->head('Accueil');
?>
<?php 
	$this->headerAdmin($table);
?>
	<div class="section admin-section">
		<div class="container">
			<div class="row">
				<?php $incs->alert(); ?>
				<div class="col-lg-12">
					<h2 class="section-title">Contenu table <?php secho($table); ?><a class="add-line-admin fa fa-plus" href="<?php echo $this->generateUrl('admin', 'add', [$table]); ?>"></a></h2>
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
											<th>
												<span class="admin-liste-order">
													<a class="fa fa-sort-alpha-asc" href="<?php echo $this->generateUrl('admin', 'liste', [$table, $nom, 0]); ?>"></a>
													<a class="fa fa-sort-alpha-desc" href="<?php echo $this->generateUrl('admin', 'liste', [$table, $nom, 1]); ?>"></a>
												</span>
												<?php secho($nom); ?>
											</th>
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
