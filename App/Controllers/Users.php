<?php
/**
 * The Users controller.
 */

/**
 * Class AppUsersController
 */
class AppUsersController extends CoreController {

	public function loginAction() {
		// First check that a user is not logged in.
		if ( App::instance()->auth->getCurrentUser() ) {
			// If a user is already logged in then there is no point in logging in.
			CoreView::redirect( AppConfig::BASE_URL );
		}

		$errors = [];

		// We handle a submit from the user.
		if ( 'POST' === $this->request->method ) {
			// Gather the data.
			$loginData = [];

			if ( ! empty( $this->request->data['email'] ) ) {
				$loginData['email'] = filter_var( trim( $this->request->data['email'], FILTER_VALIDATE_EMAIL ) );
			}

			if ( ! empty( $this->request->data['password'] ) ) {
				$loginData['password'] = trim( $this->request->data['password'] );
			}

			// Now check if we have all the data that we need.
			if ( empty( $loginData['email'] ) || empty( $loginData['password'] ) ) {
				$errors[] = 'You need to fill in all the required fields.';
			}

			// Validate the login data.
			$user = App::instance()->auth->maybeLogIn( $this->request );
			if ( false === $user ) {
				$errors[] = 'Invalid login details. Please try again.';
			} else {
				// Redirect.
				CoreView::redirect( AppConfig::BASE_URL . '?logged-in=true' );
			}
		}

		// If we've reached thus far, we should display the login form view.
		CoreView::render('login.php', [ 'errors' => $errors, 'routeConfig' => $this->routeConfig ] );
	}

	public function logoutAction() {
		if ( App::instance()->auth->maybeLogOut() ) {
			CoreView::redirect( AppConfig::BASE_URL . 'login?logged-out=true' );
		} else {
			CoreView::redirect( AppConfig::BASE_URL . 'login?logged-out=false' );
		}
	}

	public function forgotPasswordAction() {

	}

	public function resetPasswordAction() {

	}

	public function registerAction() {
		// First check that a user is not logged in.
		if ( App::instance()->auth->getCurrentUser() ) {
			// If a user is already logged in then there is no point in registering.
			CoreView::redirect( AppConfig::BASE_URL );
		}

		$errors = [];

		// We handle a submit from the user.
		if ( 'POST' === $this->request->method ) {
			// Gather the data.
			$userData = [];

			if ( ! empty( $this->request->data['first_name'] ) ) {
				$userData['first_name'] = trim( strip_tags( $this->request->data['first_name'] ) );
			}

			if ( ! empty( $this->request->data['last_name'] ) ) {
				$userData['last_name'] = trim( strip_tags( $this->request->data['last_name'] ) );
			}

			if ( ! empty( $this->request->data['email'] ) ) {
				$userData['email'] = filter_var( trim( $this->request->data['email'], FILTER_VALIDATE_EMAIL ) );
			}

			if ( ! empty( $this->request->data['password'] ) ) {
				$userData['password'] = App::instance()->auth->getPasswordHash( trim( $this->request->data['password'] ) );
			}

			// Now check if we have all the data that we need.
			if ( empty( $userData['email'] ) || empty( $userData['password'] ) ) {
				$errors[] = 'You need to fill in all the required fields.';
			}

			// Check if another user with the same email doesn't exist.
			if ( false !== AppUsersModel::getUserByEmail( $userData['email'] ) ) {
				$errors[] = 'A user with the same email address already exists.';
			}

			if ( empty( $errors ) ) {
				// We can create the user.
				if ( false !== AppUsersModel::createUser( $userData ) ) {
					// Success. Redirect to main page.
					CoreView::redirect( AppConfig::BASE_URL . '?registered=true' );
				} else {
					$errors[] = 'There was an error and we couldn\'t register you at this time.';
				}
			}
		}

		// If we've reached thus far, we should display the registration form view.
		CoreView::render('register.php', [ 'errors' => $errors, 'routeConfig' => $this->routeConfig ] );
	}

	/**
	 * Before filter - called before an action method.
	 *
	 * @return void
	 */
	protected function before() {
		App::instance()->auth->maybeLogIn( $this->request );
	}

	/**
	 * After filter - called after an action method.
	 *
	 * @return void
	 */
	protected function after() {
	}
}