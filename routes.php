<?php
	$descartesRoutes = array(
		'Index' => [
			'home' => '/',
			'showValue' => [
				'/show-value/{firstValue}/',
				'/show-value/{firstValue}/{secondValue}/',
			],
		],
		'DescartesAdministratorLogin' => [
			'login' => '/admin/',
			'logout' => '/admin/logout/',
		],
		'DescartesAdministratorAdmin' => [
			'index' => '/admin/index',
			'add' => [
				'/admin/add/{table}/',
				'/admin/add/{table}/{nbLine}/',
			],
			'create' => [
				'/admin/create/{table}/{csrf}/',
			],
			'edit' => [
				'/admin/edit/{table}/for/{primary}/',
				'/admin/edit/{table}/for/{primary}/{nbLine}/',
			],
			'modify' => [
				'/admin/modify/{table}/for/{primary}/{csrf}/',
			],
			'showTable' => [
				'/admin/show-table/{table}/',
				'/admin/show-table/{table}/order-by-field/{orderByField}/{orderDesc}/page/{page}/',
			],
		],
	);
