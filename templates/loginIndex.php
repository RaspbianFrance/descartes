<?php
	$incs = new internalIncs();
	$incs->head('Admin');
?>
<div class="colored-background">
	<div class="container-fluid">
		<div class="row">
			<form class="col-xs-10 col-xs-offset-1 col-md-4 col-md-offset-4 connexion-form" action="" method="POST">
				<h2>Connexion - Wiko comparateur</h2>
				<div class="form-group">
					<label>Login</label>
					<div class="form-group input-group">
						<span class="input-group-addon"><span class="fa fa-user"></span></span>
						<input name="login" class="form-control" type="text" placeholder="Ex : john.doe@example.tld" autofocus required>
					</div>
				</div>	
				<div class="form-group">
					<label>Mot de passe</label>
					<div class="form-group input-group">
						<span class="input-group-addon"><span class="fa fa-lock"></span></span>
						<input name="password" class="form-control" type="password" placeholder="Your password" required>
					</div>
				</div>	

				<button class="btn btn-primary btn-lg btn-block">Connexion</button>
			</form>
		</div>
	</div>
</div>
