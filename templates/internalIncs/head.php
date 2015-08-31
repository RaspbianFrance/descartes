<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="<?php echo $description; ?>" />
		<meta name="keywords" content="<?php echo $keywords; ?>" />
		<meta name="author" content="<?php echo $author; ?>" />
		<link rel="icon" type="image/png" href="<?php echo HTTP_PWD_IMG; ?>favicon.ico" />

		<!-- CSS  -->
		<?php if (!ENV_PRODUCTION) { ?>
			<link rel="stylesheet" type="text/css" href="<?php echo HTTP_PWD_CSS; ?>bootstrap.min.css" />
			<link rel="stylesheet" type="text/css" href="<?php echo HTTP_PWD_CSS; ?>bootstrap-theme.min.css" />
			<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700" />
			<link rel="stylesheet" type="text/css" href="<?php echo HTTP_PWD_CSS; ?>font-awesome.min.css" />
			<link rel="stylesheet" type="text/css" href="<?php echo HTTP_PWD_CSS; ?>bootstrap-slider.css" />
			<link rel="stylesheet" type="text/css" href="<?php echo HTTP_PWD_CSS; ?>responsive-font.min.css" />
			<link rel="stylesheet" type="text/css" href="<?php echo HTTP_PWD_CSS; ?>style.css" />
		<?php } else { ?>
			<link rel="stylesheet" type="text/css" href="<?php echo $this->generateUrl('minify', 'css'); ?>" />
		<?php } ?>

		<!-- JS  -->
		<?php if (!ENV_PRODUCTION) { ?>
			<script type="text/javascript" src="<?php echo HTTP_PWD_JS; ?>jquery-2.1.1.min.js"></script>
			<script type="text/javascript" src="<?php echo HTTP_PWD_JS; ?>bootstrap.min.js"></script>
		<?php } else { ?>
			<script type="text/javascript" src="<?php echo $this->generateUrl('minify', 'js'); ?>"></script>
		<?php } ?>
		
	</head>
	<body>
