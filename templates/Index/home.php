<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Descartes</title>
</head>
<body>
	<h1>It works !</h1>
	<p>Je suis une page par défaut</p>
	<p>Vous pouvez retrouver un exemple de page avec des valeurs dans l'url : </p>
	<ul>
		<li><a href="<?php $this->s($this->generateUrl('Index', 'showValue', ['firstValue' => 'Value1'])); ?>"><?php echo $this->generateUrl('Index', 'showValue', ['firstValue' => 'Value1']); ?></a></li>
		<li><a href="<?php $this->s($this->generateUrl('Index', 'showValue', ['firstValue' => 'Value1', 'secondValue' => 'Value2'])); ?>"><?php echo $this->generateUrl('Index', 'showValue', ['firstValue' => 'Value1', 'secondValue' => 'Value2']); ?></a></li>
	</ul>
</body>
</html>
