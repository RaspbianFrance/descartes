<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Descartes</title>
</head>
<body>
	<?php if ($secondValue === false) { ?>
		<h1>La page doit afficher une seule valeur : </h1>
		<ul>
			<li>Valeur : <?php $this->s($firstValue); ?></li>
		</ul>
	<?php } else { ?>
		<h1>La page doit afficher deux valeurs : </h1>
		<ul>
			<li>Première valeur : <?php $this->s($firstValue); ?></li>
			<li>Seconde valeur : <?php $this->s($secondValue); ?></li>
		</ul>
	<?php } ?>
</body>
</html>
