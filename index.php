<?php
/**
 * This is the (only) entry point into our app.
 */

/*
 * Make sure our absolute path constant is defined. We will use this to construct paths throughout the app.
 */
defined( 'ABSPATH' ) || define( 'ABSPATH', dirname( __FILE__ ) );

/*
 * Define some helper constants for time values.
 */
define( 'MINUTE_IN_SECONDS', 60 );
define( 'HOUR_IN_SECONDS',   60 * MINUTE_IN_SECONDS );
define( 'DAY_IN_SECONDS',    24 * HOUR_IN_SECONDS   );
define( 'WEEK_IN_SECONDS',    7 * DAY_IN_SECONDS    );
define( 'MONTH_IN_SECONDS',  30 * DAY_IN_SECONDS    );
define( 'YEAR_IN_SECONDS',  365 * DAY_IN_SECONDS    );

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