<?php
/**
 * This is the class holding the app's configuration.
 */

class AppConfig {

	/**
	 * The base URL where the app entry point resides.
	 * @var string
	 */
	const BASE_URL = 'http://ssh.local/bei-ssh/';

	/**
	 * Database host
	 * @var string
	 */
	const DB_HOST = 'your-database-host';

	/**
	 * Database name
	 * @var string
	 */
	const DB_NAME = 'your-database-name';

	/**
	 * Database user
	 * @var string
	 */
	const DB_USER = 'your-database-user';

	/**
	 * Database password
	 * @var string
	 */
	const DB_PASSWORD = 'your-database-password';

	/**
	 * Show or hide error messages on screen
	 * @var boolean
	 */
	const SHOW_ERRORS = true;

	/**
	 * Pre-configured routes that will be automatically registered on app init.
	 *
	 * Please take into consideration that routes are processed top to bottom and the first one that matches the request is used.
	 *
	 * @var array
	 */
	public static $routes = [
		'login' => [
			'controller' => 'app-users',
			'action' => 'login',
		],
		'login/forgot-password' => [
			'controller' => 'app-users',
			'action' => 'forgot-password',
		],
		'login/reset-password' => [
			'controller' => 'app-users',
			'action' => 'reset-password',
		],
		'logout' => [
			'controller' => 'app-users',
			'action' => 'logout',
		],
		'register' => [
			'controller' => 'app-users',
			'action' => 'register',
		],
		'' => [
			'controller' => 'app-persons',
			'action' => 'index',
		],
		'persons' => [
			'controller' => 'app-persons',
			'action' => 'index',
		],
		'person/add' => [
			'controller' => 'app-persons',
			'action' => 'add',
		],
		'person/{id}' => [
			'controller' => 'app-persons',
			'action' => 'view',
		],
		'person/{id}/edit' => [
			'controller' => 'app-persons',
			'action' => 'edit',
		],
		'rounds' => [
			'controller' => 'app-rounds',
			'action' => 'index',
		],
		'round/new' => [
			'controller' => 'app-rounds',
			'action' => 'new',
		],
		'round/{id}' => [
			'controller' => 'app-rounds',
			'action' => 'view',
		],
	];
}