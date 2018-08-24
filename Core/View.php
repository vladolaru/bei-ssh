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
		$file = ABSPATH . "/App/Views/$view";
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
	 * @param bool $permanent If true we will redirect with the 301 code, else we'll use the 302 HTTP response code.
	 */
	public static function redirect( $url, $permanent = false ) {
		if ( $permanent ) {
			http_response_code( 301 );
		} else {
			http_response_code( 302 );
		}

		header( 'Location: ' . $url );

		exit;
	}

	/**
	 * Show a 404 page and stop execution.
	 *
	 * Please note that nothing should be sent to the output before calling this. If output has started, this will not work as expected.
	 */
	public static function show404() {
		http_response_code( 404 );

		// Display the 404 page.
		CoreView::render( '404.php' );

		exit;
	}

	/**
	 * Show a 500 page and stop execution.
	 *
	 * Please note that nothing should be sent to the output before calling this. If output has started, this will not work as expected.
	 */
	public static function show500() {
		http_response_code( 500 );

		// Display the 500 page.
		CoreView::render( '500.php' );

		exit;
	}
}
