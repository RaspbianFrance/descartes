<ul class="pagination">
	<?php if (!empty($pagination['first'])) { ?>
			<li><a href="<?php secho($pagination['first']); ?>"><i class="fa fa-angle-double-left"></i></a></li>
	<?php }	?>

	<?php foreach ($pagination['before'] as $pagi) { ?>
			<li><a href="<?php secho($pagi['url']); ?>"><?php secho($pagi['nb']); ?></a></li>
	<?php } ?>

	<li class="active"><a href="#"><?php secho($pagination['current']); ?></a></li>

	<?php foreach ($pagination['after'] as $pagi) { ?>
			<li><a href="<?php secho($pagi['url']); ?>"><?php secho($pagi['nb']); ?></a></li>
	<?php } ?>

	<?php if (!empty($pagination['last'])) { ?>
		<li><a href="<?php secho($pagination['last']); ?>"><i class="fa fa-angle-double-right"></i></a></li>
	<?php }	?>
</ul>
