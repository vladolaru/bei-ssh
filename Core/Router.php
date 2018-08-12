<?php
/**
 * Class to route requests to the appropriate controllers.
 */

class CoreRouter {

	/**
	 * The available routes to match the current URL against.
	 *
	 * @var array
	 */
	protected $routes = [];

	/**
	 * Config from the matched route
	 * @var array
	 */
	protected $matchedConfig = [];

	/**
	 * Add a route to the routing table
	 *
	 * @param string $route  The route URL.
	 * @param array  $config Configuration like controller, action, and so on.
	 *
	 * @return void
	 */
	public function add( $route, $config = [] ) {
		// Convert the route to a regular expression: escape forward slashes.
		$route = preg_replace( '/\//', '\\/', $route );
		// Convert variables e.g. {controller}
		$route = preg_replace( '/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route );
		// Convert variables with custom regular expressions e.g. {id:\d+}
		$route = preg_replace( '/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route );
		// Add start and end delimiters, and case insensitive flag
		$route = '/^' . $route . '$/i';

		$this->routes[ $route ] = $config;
	}

	/**
	 * Get all the routes currently available.
	 *
	 * @return array
	 */
	public function getRoutes() {
		return $this->routes;
	}

	/**
	 * Match the URL to the routes in the routing table, setting the $params property if a route is found.
	 *
	 * @param string $url The request URL.
	 *
	 * @return boolean  true if a match found, false otherwise.
	 */
	public function match( $url ) {
		foreach ( $this->routes as $route => $config ) {
			if ( preg_match( $route, $url, $matches ) ) {
				// Get named capture group values from the URL.
				foreach ( $matches as $key => $match ) {
					if ( is_string( $key ) ) {
						$config[ $key ] = $match;
					}
				}
				$this->matchedConfig = $config;

				return true;
			}
		}

		return false;
	}

	/**
	 * Get the currently matched route config.
	 *
	 * @return array
	 */
	public function getMatchedConfig() {
		return $this->matchedConfig;
	}

	/**
	 * Match the request to a route, create the controller object and run the action method.
	 *
	 * @throws Exception;
	 *
	 * @param CoreRequest $request The request.
	 *
	 * @return void
	 */
	public function dispatch( $request ) {
		if ( $this->match( $request->url ) ) {
			$controller = $this->matchedConfig['controller'];
			$controller = $this->convertToStudlyCaps( $controller );
			if ( class_exists( $controller ) ) {
				// Create the controller object, passing the config just in case.
				$controller_object = new $controller( $this->matchedConfig, $request );

				// Determine the action (controller method) name we should call.
				$action            = $this->matchedConfig['action'];
				$action            = $this->convertToCamelCase( $action );

				// Call the action.
				$controller_object->$action();
			} else {
				throw new Exception( "Controller class $controller not found" );
			}
		} else {
			throw new Exception( 'No route matched.', 404 );
		}
	}

	/**
	 * Convert the string with hyphens to StudlyCaps,
	 * e.g. post-authors => PostAuthors
	 *
	 * @param string $string The string to convert
	 *
	 * @return string
	 */
	protected function convertToStudlyCaps( $string ) {
		return str_replace( ' ', '', ucwords( str_replace( '-', ' ', $string ) ) );
	}

	/**
	 * Convert the string with hyphens to camelCase,
	 * e.g. add-new => addNew
	 *
	 * @param string $string The string to convert
	 *
	 * @return string
	 */
	protected function convertToCamelCase( $string ) {
		return lcfirst( $this->convertToStudlyCaps( $string ) );
	}
}