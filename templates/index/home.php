<?php \controllers\internals\Incs::head('It Works !'); ?>
    <h1>It works !</h1>
	<p>This is a page without arguments.</p>
	<p>You can see a page with arguments, required and optionnal : </p>
	<ul>
		<li><a href="<?php $this->s(Router::url('Index', 'show_value', ['first_value' => 'Value1'])); ?>"><?php echo Router::url('Index', 'show_value', ['first_value' => 'Value1']); ?></a></li>
		<li><a href="<?php $this->s(Router::url('Index', 'show_value', ['first_value' => 'Value1', 'second_value' => '10'])); ?>"><?php echo Router::url('Index', 'show_value', ['first_value' => 'Value1', 'second_value' => '10']); ?></a></li>
    </ul>

<?php \controllers\internals\Incs::footer(); ?>
