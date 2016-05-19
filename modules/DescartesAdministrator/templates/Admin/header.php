<nav class="navbar navbar-fixed-top navbar-inverse">
	<div class="container">
		<div class="navbar-header">
			<button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button"><span class="sr-only">Changer de navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button> <a class="navbar-brand" href="<?php echo $this->generateUrl('admin'); ?>"><?php secho(WEBSITE_TITLE); ?></a>
		</div>

		<div class="collapse navbar-collapse navbar-ex1-collapse">
			<ul class="nav navbar-nav navbar-right">
				<?php foreach ($tables as $table) { ?>
					<li class="dropdown <?php echo ($currentPage == $table) ? 'active' : ''; ?>">
						<a class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown" href="#"><?php secho($table) ?> <i class="fa fa-angle-down"></i></a>

						<ul class="dropdown-menu">
							<li>
								<a href="<?php echo $this->generateUrl('admin', 'liste', array($table)); ?>">Liste</a>
							</li>
							<li>
								<a href="<?php echo $this->generateUrl('admin', 'add', array($table)); ?>">Ajouter</a>
							</li>
						</ul>
					</li>
				<?php } ?>
				<li class="<?php echo ($currentPage == 'deconnexion') ? 'active' : ''; ?>">
					<a href="<?php echo $this->generateUrl('login', 'logout'); ?>">Déconnexion</a>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container -->
</nav>
