<?php
/**
 * The class that holds the information about a received HTTP request.
 */

/**
 * The Request class represents a HTTP request. Data from
 * all the super globals $_GET, $_POST, $_COOKIE are stored and accessible via the Request object.
 */
class CoreRequest {
	/**
	 * @var string URL being requested
	 */
	public $url;
	/**
	 * @var string Request method (GET, POST, PUT, DELETE)
	 */
	public $method;
	/**
	 * @var array Query string parameters
	 */
	public $query;
	/**
	 * @var array Post parameters
	 */
	public $data;
	/**
	 * @var array Cookie parameters
	 */
	public $cookies;

	/**
	 * Constructor.
	 *
	 * @param array $config Request configuration
	 */
	public function __construct( $config = [] ) {
		// Default properties
		if ( empty( $config ) ) {
			$config = array(
				// app-query is the special query param key we are using for the partial URL that will match routes.
				'url'        => isset( $_GET['app-query'] ) ? $_GET['app-query'] : '',
				'method'     => self::getVar( 'REQUEST_METHOD', 'GET' ),
				'query'      => $_GET,
				'data'       => $_POST,
				'cookies'    => $_COOKIE,
			);
		}
		$this->init( $config );
	}

	/**
	 * Initialize request properties.
	 *
	 * @param array $properties Array of request properties
	 */
	public function init( $properties = array() ) {
		// Set all the defined properties
		foreach ( $properties as $name => $value ) {
			$this->$name = $value;
		}

		// Default url
		if ( empty( $this->url ) ) {
			$this->url = '';
		}

		// Make sure that app-query is not among the query entries.
		if ( isset( $this->query['app-query'] ) ) {
			unset( $this->query['app-query'] );
		}
	}

	/**
	 * Gets a variable from $_SERVER using $default if not provided.
	 *
	 * @param string $var     Variable name
	 * @param string $default Default value to substitute
	 *
	 * @return string Server variable value
	 */
	public static function getVar( $var, $default = '' ) {
		return isset( $_SERVER[ $var ] ) ? $_SERVER[ $var ] : $default;
	}
}
