<?php
/**
 * Abstract class for our controllers.
 */

/**
 * Base controller.
 */
abstract class CoreController {
	/**
	 * Config from the matched route.
	 *
	 * @var array
	 */
	protected $routeConfig = [];

	/**
	 * The request being handled.
	 *
	 * @var CoreRequest|null
	 */
	protected $request = [];

	/**
	 * Class constructor
	 *
	 * @param array $routeConfig Parameters from the route.
	 * @param CoreRequest $request Optional. The request being handled.
	 *
	 * @return void
	 */
	public function __construct( $routeConfig, $request = null ) {
		$this->routeConfig = $routeConfig;
		$this->request = $request;
	}

	/**
	 * Magic method called when a non-existent or inaccessible method is
	 * called on an object of this class. Used to execute before and after
	 * filter methods on action methods. Action methods need to be named
	 * with an "Action" suffix, e.g. indexAction, showAction etc.
	 *
	 * @throws Exception
	 *
	 * @param string $name Method name
	 * @param array  $args Arguments passed to the method
	 *
	 * @return void
	 */
	public function __call( $name, $args ) {
		// Append "Action" to the action name.
		$method = $name . 'Action';

		if ( method_exists( $this, $method ) ) {
			if ( $this->before() !== false ) {
				call_user_func_array( [ $this, $method ], $args );
				$this->after();
			}
		} else {
			throw new \Exception( "Method $method not found in controller " . get_class( $this ) );
		}
	}

	/**
	 * Before filter - called before an action method.
	 *
	 * @return void
	 */
	protected function before() {
	}

	/**
	 * After filter - called after an action method.
	 *
	 * @return void
	 */
	protected function after() {
	}
}
