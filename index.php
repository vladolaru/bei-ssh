<?php
/**
 * This is the (only) entry point into our app.
 */

/*
 * Make sure our absolute path constant is defined. We will use this to construct paths throughout the app.
 */
defined( 'ABSPATH' ) || define( 'ABSPATH', dirname( __FILE__ ) );

/*
 * Load the app's configuration.
 */
require_once ABSPATH . '/App/Config.php';

/*
 * Load the front controller.
 */
require_once ABSPATH . '/App/App.php';

/*
 * Load the core classes.
 */
App::autoloadDir( ABSPATH . '/Core' );

/**
 * Error and exception handling.
 */
error_reporting(E_ALL);
set_error_handler('CoreError::errorHandler');
set_exception_handler('CoreError::exceptionHandler');

/*
 * Initialize and run our app (the front controller).
 */
App::instance()->run();