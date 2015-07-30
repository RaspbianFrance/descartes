<div class="navbar navbar-inverse no-margin">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-inverse-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="<?php echo $this->generateUrl(); ?>"><?php secho(WEBSITE_TITLE); ?></a>
	</div>
	<div class="navbar-collapse collapse navbar-inverse-collapse">
		<ul class="nav navbar-nav navbar-right">
		<?php 
			foreach ($pages as $name => $url)
			{
			?>
				<li <?php echo (mb_strtoupper($actual_page) == mb_strtoupper($name)) ? 'class="active"' : ''; ?> ><a href="<?php secho($url); ?>"><?php secho($name); ?></a></li>	
			<?php
			}	
		?>
		</ul>
	</div>
</div>
