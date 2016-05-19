<?php
	$incs = new \modules\DescartesAdministrator\internals\Incs();
	$incs->htmlHead();
	$incs->htmlNav($table);
?>
	<div class="section admin-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<h2 class="section-title">Contenu table <?php $this->s($table); ?><a class="add-line-admin fa fa-plus" href="<?php echo $this->generateUrl($this, 'add', ['table' => $table]); ?>"></a></h2>
				</div>
				<div class="col-lg-12">
						<table class="table table-striped show-table-table">
							<thead>
								<tr>
									<?php
										foreach ($fields as $nom => $field)
										{
										?>
											<th>
												<?php $this->s($nom); ?>
												<span class="admin-liste-order">
													<a class="fa fa-sort-alpha-asc" href="<?php echo $this->generateUrl($this, 'showTable', ['table' => $table, 'orderByField' => $nom, 'orderDesc' => 0, 'page' => $page]); ?>"></a>
													<a class="fa fa-sort-alpha-desc" href="<?php echo $this->generateUrl($this, 'showTable', ['table' => $table, 'orderByField' => $nom, 'orderDesc' => 1, 'page' => $page]); ?>"></a>
												</span>
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
													<td><?php $this->s($value); ?></td>
												<?php
												}
											?>
											<td>
												<div class="btn-group action-dropdown" target="#table-commands">
													<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action pour la sélection <span class="caret"></span></button>
													<ul class="dropdown-menu pull-right" role="menu">
														<li><a href="<?php echo $this->generateUrl($this, 'edit', ['table' => $table, 'primary' => $ligne[$primary]]); ?>"><span class="fa fa-edit"></span> Modifier</a></li>
														<li><a href="<?php echo $this->generateUrl($this, 'delete', ['table' => $table, 'primary' => $ligne[$primary]]); ?>"><span class="fa fa-trash-o"></span> Supprimer</a></li>
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
				<div class="text-center">
					<?php $incs->htmlPaginate($pagination); ?>
				</div>
			</div> <!-- /.row -->
		</div> <!-- /.container -->
	</div> <!-- /.section -->
<?php $incs->htmlFooter(); ?>
