<ul class="pagination">
	<?php if (!empty($pagination['first'])) { ?>
			<li><a href="<?php $this->s($pagination['first']['url']); ?>"><i class="fa fa-angle-double-left"></i></a></li>
	<?php }	?>

	<?php foreach ($pagination['before'] as $pagi) { ?>
			<li><a href="<?php $this->s($pagi['url']); ?>"><?php $this->s($pagi['number']); ?></a></li>
	<?php } ?>

	<li class="active"><a href="#"><?php $this->s($pagination['current']['number']); ?></a></li>

	<?php foreach ($pagination['after'] as $pagi) { ?>
			<li><a href="<?php $this->s($pagi['url']); ?>"><?php $this->s($pagi['number']); ?></a></li>
	<?php } ?>

	<?php if (!empty($pagination['last'])) { ?>
		<li><a href="<?php $this->s($pagination['last']['url']); ?>"><i class="fa fa-angle-double-right"></i></a></li>
	<?php }	?>
</ul>
