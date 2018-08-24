<?php
/**
 * This is the class holding the app's configuration.
 */

class AppConfig {

	/**
	 * The app current version.
	 * @var string
	 */
	const VERSION = '0.5.0';

	/**
	 * The base URL where the app entry point resides.
	 * @var string
	 */
	const BASE_URL = 'http://ssh.local/bei-ssh/';

	/**
	 * The email address that will be used for administrative purposes.
	 * @var string
	 */
	const ADMIN_EMAIL = 'dev-email@flywheel.local';

	/**
	 * Database host
	 * @var string
	 */
	const DB_HOST = 'localhost';

	/**
	 * Database port
	 * @var int
	 */
	const DB_PORT = 4002;

	/**
	 * Database name
	 * @var string
	 */
	const DB_NAME = 'ssh_main';

	/**
	 * Database user
	 * @var string
	 */
	const DB_USER = 'ssh_main';

	/**
	 * Database password
	 * @var string
	 */
	const DB_PASSWORD = 'pass';

	/**
	 * Show or hide error messages on screen
	 * @var boolean
	 */
	const SHOW_ERRORS = true;

	/**
	 * The salt to be used for authentication.
	 *
	 * @var string
	 */
	const AUTH_SALT = '9WMCoQ5A4iqYM!w8549EPfl278KYTGLJQc0d2Ekyqvqo2zykQ98sIW1WBEvUXl7L';

	/**
	 * The name of the logged in cookie.
	 *
	 * @var string
	 */
	const LOGGED_IN_COOKIE = 'ssh_logged_in';

	/**
	 * Pre-configured routes that will be automatically registered on app init.
	 *
	 * Please take into consideration that routes are processed top to bottom and the first one that matches the request is used.
	 *
	 * @var array
	 */
	public static $routes = [
		'login' => [
			'controller' => 'app-users-controller',
			'action' => 'login',
		],
		'login/forgot-password' => [
			'controller' => 'app-users-controller',
			'action' => 'forgot-password',
		],
		'login/reset-password' => [
			'controller' => 'app-users-controller',
			'action' => 'reset-password',
		],
		'logout' => [
			'controller' => 'app-users-controller',
			'action' => 'logout',
		],
		'register' => [
			'controller' => 'app-users-controller',
			'action' => 'register',
		],
		'' => [
			'controller' => 'app-persons-controller',
			'action' => 'list',
		],
		'persons' => [
			'controller' => 'app-persons-controller',
			'action' => 'list',
		],
		'person/add' => [
			'controller' => 'app-persons-controller',
			'action' => 'add',
		],
		'person/{id:\d+}' => [
			'controller' => 'app-persons-controller',
			'action' => 'view',
		],
		'person/{id:\d+}/edit' => [
			'controller' => 'app-persons-controller',
			'action' => 'edit',
		],
		'person/{id:\d+}/remove' => [
			'controller' => 'app-persons-controller',
			'action' => 'remove',
		],
		'rounds' => [
			'controller' => 'app-rounds-controller',
			'action' => 'list',
		],
		'round/new' => [
			'controller' => 'app-rounds-controller',
			'action' => 'new',
		],
		'round/{id:\d+}' => [
			'controller' => 'app-rounds-controller',
			'action' => 'view',
		],
		'round/{id:\d+}/view' => [
			'controller' => 'app-rounds-controller',
			'action' => 'view',
		],
		'privacy' => [
			'controller' => 'app-users-controller',
			'action' => 'privacy',
		],
	];
}