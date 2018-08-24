<?php
/**
 * The Users controller.
 */

/**
 * Class AppUsersController
 */
class AppUsersController extends CoreController {

	public function privacyAction() {
		CoreView::render( 'privacy.php', [
			'routeConfig' => $this->routeConfig,
		] );
	}

	public function loginAction() {
		// First check that a user is not logged in.
		if ( App::instance()->auth->getCurrentUser() ) {
			// If a user is already logged in then there is no point in logging in.
			CoreView::redirect( AppConfig::BASE_URL );
		}

		$errors = [];
		$messages = [];

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
				// We will "remember" in the URL that the login was successful.
				CoreView::redirect( AppConfig::BASE_URL . '?logged-in=true' );
			}
		}

		if ( ! empty( $this->request->query['login-first'] ) ) {
			$messages[] = 'You need to login first.';
		}


		if ( ! empty( $this->request->query['logged-out'] ) ) {
			$messages[] = 'Come back 😢';
		}


		// If we've reached thus far, we should display the login form view.
		CoreView::render( 'login/login.php', [
			'messages'    => $messages,
			'errors'      => $errors,
			'routeConfig' => $this->routeConfig,
		] );
	}

	public function logoutAction() {
		if ( App::instance()->auth->maybeLogOut() ) {
			// We will "remember" in the URL that the logout was successful.
			CoreView::redirect( AppConfig::BASE_URL . 'login?logged-out=true' );
		} else {
			// We will "remember" in the URL that the logout failed.
			CoreView::redirect( AppConfig::BASE_URL . 'login?logged-out=false' );
		}
	}

	public function forgotPasswordAction() {
		// First check that a user is not logged in.
		if ( App::instance()->auth->getCurrentUser() ) {
			// If a user is already logged in then there is no point in resetting the password.
			CoreView::redirect( AppConfig::BASE_URL );
		}

		$errors = [];
		$messages = [];

		// We handle a submit from the user.
		if ( 'POST' === $this->request->method ) {
			// Gather the data.
			$forgotPasswordData = [];

			if ( ! empty( $this->request->data['email'] ) ) {
				$forgotPasswordData['email'] = filter_var( trim( $this->request->data['email'], FILTER_VALIDATE_EMAIL ) );
			}

			// Now check if we have all the data that we need.
			if ( empty( $forgotPasswordData['email'] ) ) {
				$errors[] = 'You need to fill in all the required fields.';
			}

			if ( empty( $errors ) ) {
				$user = AppUsersModel::getUserByEmail( $forgotPasswordData['email'] );
				if ( ! empty( $user ) ) {
					$sent = $this->sendPasswordResetEmail( $user );
					if ( ! $sent ) {
						$errors[] = 'Something went wrong and we couldn\'t send the email at this time.';
					}
				}

				if ( empty( $errors ) ) {
					$messages[] = 'We have sent an email with instructions to the address provided.';
				}
			}
		}

		// If we've reached thus far, we should display the forgot password form view.
		CoreView::render('login/forgot-password.php', [ 'messages' => $messages, 'errors' => $errors, 'routeConfig' => $this->routeConfig ] );
	}

	/**
	 * @param AppUser $user
	 *
	 * @return bool
	 */
	protected function sendPasswordResetEmail( $user ) {
		if ( empty( $user ) ) {
			return false;
		}

		// We will generate an "encrypted" key with the needed information.
		$key = base64_encode( $user->email . '|' . $user->id );

		$message = '';
		$message = "Someone has requested a password reset for the following account:\r\n\r\n";
		$message .= 'Site Name: ' . AppConfig::BASE_URL . "\r\n\r\n";
		$message .= 'If this was a mistake, just ignore this email and nothing will happen.' . "\r\n\r\n";
		$message .= 'To reset your password, visit the following address:' . "\r\n\r\n";
		$message .= '<' . AppConfig::BASE_URL . "login/reset-password?key=$key>\r\n";

		$headers = 'From: ' . AppConfig::ADMIN_EMAIL . "\r\n" .
		           'Reply-To: ' . AppConfig::ADMIN_EMAIL . "\r\n" .
		           'X-Mailer: PHP/' . phpversion();

		return mail( $user->email, 'Reset your password', $message, $headers );
	}

	public function resetPasswordAction() {
		// First check that a user is not logged in.
		if ( App::instance()->auth->getCurrentUser() ) {
			// If a user is already logged in then there is no point in resetting the password.
			CoreView::redirect( AppConfig::BASE_URL );
		}

		$errors = [];
		$messages = [];
		$keyInfo = [];

		// We handle a submit from the user.
		if ( 'POST' === $this->request->method ) {
			// Gather the data.
			$resetPasswordData = [];

			if ( ! empty( $this->request->data['user_id'] ) ) {
				$resetPasswordData['user_id'] = intval( trim( $this->request->data['user_id'] ) );
			}

			if ( ! empty( $this->request->data['password'] ) ) {
				$resetPasswordData['password'] = trim( $this->request->data['password'] );
			}

			if ( ! empty( $this->request->data['password_confirm'] ) ) {
				$resetPasswordData['password_confirm'] = trim( $this->request->data['password_confirm'] );
			}

			// Now check if we have all the data that we need.
			if ( ! isset( $resetPasswordData['user_id'] ) || empty( $resetPasswordData['password'] ) || empty( $resetPasswordData['password_confirm'] ) ) {
				$errors[] = 'You need to fill in all the required fields.';
			}

			// Check if the passwords match.
			if ( $resetPasswordData['password'] !== $resetPasswordData['password_confirm'] ) {
				$errors[] = 'The two passwords don\'t match.';
			}

			if ( empty( $errors ) ) {
				$user = AppUsersModel::getUserById( $resetPasswordData['user_id'] );
				if ( ! empty( $user ) ) {
					$userData['password'] = App::instance()->auth->getPasswordHash( $resetPasswordData['password'] );

					if ( false !== AppUsersModel::updateUser( $user->id, $userData ) ) {
						$messages[] = 'You account password has been changed. You can now login.';
					} else {
						$errors[] = 'There was an error and we couldn\'t change your password at this time.';
					}
				}
			}
		} else {
			// We need to read the URL parameter and extract the info.
			if ( ! empty( $this->request->query['key'] ) ) {
				$keyElements = explode( '|', base64_decode( $this->request->query['key'] ) );

				if ( count( $keyElements ) === 2 ) {
					list ( $userEmail, $userId ) = $keyElements;

					$userId = intval( $userId );
					$user   = AppUsersModel::getUserById( $userId );
					if ( ! empty( $user ) && $user->email === $userEmail ) {
						$keyInfo['user_email'] = $userEmail;
						$keyInfo['user_id']    = $userId;
					}
				}
			}

			if ( empty( $keyInfo ) ) {
				CoreView::redirect( AppConfig::BASE_URL . 'login/forgot-password?try-again=true' );
			}
		}

		// If we've reached thus far, we should display the forgot password form view.
		CoreView::render('login/reset-password.php', [ 'keyInfo' => $keyInfo, 'messages' => $messages, 'errors' => $errors, 'routeConfig' => $this->routeConfig ] );
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
		CoreView::render('login/register.php', [ 'errors' => $errors, 'routeConfig' => $this->routeConfig ] );
	}

	/**
	 * Before filter - called before an action method.
	 *
	 * @return void
	 */
	protected function before() {
		// We attempt to login the user before every action.
		App::instance()->auth->maybeLogIn( $this->request );
	}
}