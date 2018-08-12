<?php
/**
 * Abstract class for our views.
 */

/**
 * View
 */
class CoreView {
	/**
	 * Render a view file
	 *
	 * @throws Exception
	 *
	 * @param string $view The view file
	 * @param array  $args Associative array of data to display in the view (optional)
	 *
	 * @return void
	 */
	public static function render( $view, $args = [] ) {
		extract( $args, EXTR_SKIP );
		$file = dirname( __DIR__ ) . "/App/Views/$view";  // relative to Core directory
		if ( is_readable( $file ) ) {
			require $file;
		} else {
			throw new \Exception( "$file not found" );
		}
	}

	/**
	 * Redirect to a URL and stop execution.
	 *
	 * Please note that nothing should be sent to the output before calling this. If output has started, this will not work as expected.
	 *
	 * @param string $url
	 * @param bool $permanent If true we will redirect with the 301 code.
	 */
	public static function redirect( $url, $permanent = false ) {
		if ( $permanent ) {
			http_response_code( 301 );
		} else {
			http_response_code( 302 );
		}

		header( 'Location: ' . $url );

		exit();
	}

	/**
	 * Show a 404 view and stop execution.
	 *
	 * Please note that nothing should be sent to the output before calling this. If output has started, this will not work as expected.
	 */
	public static function show404() {
		http_response_code( 404 );

		// Grab the 404 view

		exit();
	}
}
