<?php
/**
 * The app's front controller.
 *
 * This is where everything gets loaded and fired up.
 */

/**
 * The class that functions as our app's front controller.
 *
 * Everything gets loaded and initialized here.
 */
final class App {

	/**
	 * @var CoreRouter|null
	 */
	public $router = null;

	/**
	 * The single instance of this class.
	 *
	 * @var App|null
	 */
	private static $instance = null;

	/**
	 * Minimal Required PHP Version
	 * @var string
	 * @access  private
	 * @since   1.0.0
	 */
	private $minimalRequiredPhpVersion = 5.6;

	/**
	 * App constructor.
	 *
	 * @throws Exception
	 */
	private function __construct() {
		// Only load and run the init function if we know the PHP version can parse it.
		if ( true === $this->php_version_check() ) {
			$this->init();
		} else {
			throw new Exception( 'You need to be using at least the ' . $this->minimalRequiredPhpVersion . ' version of PHP for this app to work properly.' );
		}
	}

	/**
	 * Initialize our app.
	 */
	public function init() {
		/*
		 * Load up our vendors.
		 */
		require_once ABSPATH . '/App/vendor/autoload.php';

		/**
		 * Loud up our controllers.
		 */
		self::autoloadDir( ABSPATH . '/App/Controllers' );

		/**
		 * Loud up our models.
		 */
		self::autoloadDir( ABSPATH . '/App/Models' );

		/*
		 * Initialize the router.
		 */
		$this->router = new CoreRouter();

		/*
		 * Register any pre-configured routes.
		 */
		$this->registerConfigRoutes();
	}

	public function registerConfigRoutes() {
		if ( ! empty( AppConfig::$routes ) ) {
			foreach ( AppConfig::$routes as $route => $config ) {
				$this->router->add( $route, $config );
			}
		}
	}

	/**
	 * The main method that handles the request and delivers it to the appropriate controller.
	 */
	public function run() {

		$this->router->dispatch( new CoreRequest() );
	}

	/**
	 * PHP version check.
	 *
	 * @return bool If the PHP version check passes it will return true. Else it returns false.
	 */
	private function php_version_check() {
		if ( version_compare( phpversion(), $this->minimalRequiredPhpVersion ) < 0 ) {
			return false;
		}

		return true;
	}

	public static function debug( $what ) {
		echo '<pre style="margin-left: 160px">';
		var_dump( $what );
		echo '</pre>';
	}

	/**
	 * Autoloads the files in a directory.
	 *
	 * @throws Exception
	 *
	 * @param string $path The path of the directory to autoload files from.
	 * @param int    $depth The depth to which we should go in the directory. A depth of 0 means only the files directly in that
	 *                     directory. Depth of 1 means also the first level subdirectories, and so on.
	 *                     A depth of -1 means load everything.
	 * @param string $method The method to use to load files. Supports require, require_once, include, include_once.
	 *
	 * @return false|int False on failure, otherwise the number of files loaded.
	 */
	public static function autoloadDir( $path, $depth = 0, $method = 'require_once' ) {

		if ( ! in_array( $method, array( 'require', 'require_once', 'include', 'include_once' ) ) ) {
			throw new Exception( 'We support only require, require_once, include, and include_once.', 500 );
		}

		// Start the counter
		$counter = 0;

		$iterator = new DirectoryIterator( $path );
		// First we will load the files in the directory
		foreach ( $iterator as $file_info ) {
			if ( ! $file_info->isDir() && ! $file_info->isDot() && 'php' == strtolower( $file_info->getExtension() ) ) {
				switch ( $method ) {
					case 'require':
						require $file_info->getPathname();
						break;
					case 'require_once':
						require_once $file_info->getPathname();
						break;
					case 'include':
						include $file_info->getPathname();
						break;
					case 'include_once':
						include_once $file_info->getPathname();
						break;
					default:
						break;
				}

				$counter ++;
			}
		}

		// Now we load files in subdirectories if that's the case
		if ( $depth > 0 || -1 === $depth ) {
			if ( $depth > 0 ) {
				$depth --;
			}
			$iterator->rewind();
			foreach ( $iterator as $file_info ) {
				if ( $file_info->isDir() && ! $file_info->isDot() ) {
					$counter += self::autoloadDir( $file_info->getPathname(), $depth, $method );
				}
			}
		}

		return $counter;
	}

	/**
	 * Get the single instance of this class.
	 *
	 * @return App|null
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			try {
				self::$instance = new App();
			} catch ( Exception $exception ) {
				self::debug( $exception );
			}
		}

		return self::$instance;
	}
}