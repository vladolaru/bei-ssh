<?php
/**
 * Class to handle our authentication needs.
 */

class CoreAuth {

	/**
	 * The current logged in user.
	 *
	 * @var AppUser|null
	 */
	protected $currentUser = null;

	/**
	 * Attempt to log in a user based on the info available in the request object.
	 *
	 * @param CoreRequest $request
	 *
	 * @return AppUser|false Returns the user object if successful. false on failure.
	 */
	public function maybeLogIn( $request ) {
		// Attempt to log in based on the request info.
		if ( ! empty( $request->data['email'] ) && ! empty( $request->data['password'] ) ) {
			$this->currentUser = $this->authenticate( $request->data['email'], $request->data['password'] );

			if ( ! empty( $this->currentUser ) ) {
				// Set the cookies.
				$this->setCookie( $this->currentUser );
			}
		}

		if ( empty( $this->currentUser ) ) {
			// Attempt to log in based on the cookie info
			$this->currentUser = $this->validateCookie( $request );
		}

		return $this->currentUser;
	}

	/**
	 * Log out the current user, if there is any logged in.
	 *
	 * @return bool Returns true if there was a user logged in. false if there was no user to log out in the fist place.
	 */
	public function maybeLogOut() {
		$wasLoggedIn = false;
		if ( ! empty( $this->currentUser ) ) {
			$wasLoggedIn = true;
		}

		// Clear the data.
		$this->currentUser = null;
		$this->clearCookie();

		return $wasLoggedIn;
	}

	public function setCookie( $user ) {
		// Remember for 10 days.
		$expiration = time() + 10 * DAY_IN_SECONDS ;
		$expire = $expiration;

		// We will embed the expiration in the cookie content also.
		$cookie = $this->generateAuthCookie( $user, $expiration );

		setcookie( AppConfig::LOGGED_IN_COOKIE, $cookie, $expire, '/' );
	}

	/**
	 * Removes all of the authentication cookies.
	 */
	function clearCookie() {
		setcookie( AppConfig::LOGGED_IN_COOKIE,   ' ', time() - YEAR_IN_SECONDS, '/' );
	}

	/**
	 * Authenticate a user, confirming the login credentials are valid.
	 *
	 * @param string $email    User's email address.
	 * @param string $password User's password.
	 *
	 * @return AppUser|false AppUser object if the credentials are valid,
	 *                          otherwise false.
	 */
	public function authenticate( $email, $password ) {
		$email    = filter_var( $email, FILTER_VALIDATE_EMAIL );
		$password = trim( $password );

		// Find a user with this email.
		$user = AppUsersModel::getUserByEmail( $email );
		if ( empty( $user ) ) {
			return false;
		}

		// Now check if the password matches the one stored with the user.
		if ( ! $this->verifyPassword( $password, $user->password ) ) {
			return false;
		}

		return $user;
	}

	/**
	 * Generate authentication cookie contents.
	 *
	 * @param AppUser $user
	 * @param int $expiration The time the cookie expires as a UNIX timestamp.
	 *
	 * @return string Authentication cookie contents. Empty string if user does not exist.
	 */
	public function generateAuthCookie( $user, $expiration ) {
		if ( ! $user ) {
			return '';
		}

		// We will use a fragment of the hashed password in the cookie hash key, for further uniqueness.
		$pass_frag = substr($user->password, 8, 4);
		$key = $this->hash( $user->email . '|' . $pass_frag . '|' . $expiration );
		$hash = hash_hmac( 'sha256', $user->email . '|' . $expiration , $key );

		return $user->email . '|' . $expiration . '|' . $hash;
	}

	/**
	 * Validates authentication cookie.
	 *
	 * The checks include making sure that the authentication cookie is set and
	 * pulling in the contents (if $cookie is not used).
	 *
	 * Makes sure the cookie is not expired. Verifies the hash in cookie is what is
	 * should be and compares the two.
	 *
	 * @param CoreRequest $request
	 *
	 * @return false|int False if invalid cookie, User ID if valid.
	 */
	public function validateCookie( $request ) {
		if ( ! $cookieData = $this->parseCookie( $request ) ) {
			return false;
		}

		$email = $cookieData['email'];
		$hmac = $cookieData['hmac'];
		$expired = $expiration = $cookieData['expiration'];

		// Check if the cookie has expired.
		if ( $expired < time() ) {
			return false;
		}

		$user = AppUsersModel::getUserByEmail( $email );
		if ( ! $user ) {
			return false;
		}

		// Now compare the hashes.
		$pass_frag = substr($user->password, 8, 4);
		$key = $this->hash( $email . '|' . $pass_frag . '|' . $expiration );
		$hash = hash_hmac( 'sha256', $email . '|' . $expiration , $key );

		if ( ! hash_equals( $hash, $hmac ) ) {
			return false;
		}

		return $user;
	}

	/**
	 * Parse a cookie into its components
	 *
	 * @param CoreRequest $request
	 *
	 * @return array|false Authentication cookie components
	 */
	function parseCookie( $request ) {
		if ( empty( $request->cookies[ AppConfig::LOGGED_IN_COOKIE ] ) ) {
			return false;
		}

		$cookie_elements = explode( '|', $request->cookies[ AppConfig::LOGGED_IN_COOKIE ] );
		if ( count( $cookie_elements ) !== 3 ) {
			return false;
		}

		list( $email, $expiration, $hmac ) = $cookie_elements;

		return compact( 'email', 'expiration', 'hmac' );
	}

	/**
	 * Get hash of given string.
	 *
	 * @param string $data   Plain text to hash.
	 * @return string Hash of $data
	 */
	protected function hash( $data ) {
		return hash_hmac( 'md5', $data, AppConfig::AUTH_SALT );
	}

	/**
	 * Return the current logged in user.
	 *
	 * @return AppUser|null
	 */
	public function getCurrentUser() {
		return $this->currentUser;
	}

	/**
	 * Return the current logged in user ID.
	 *
	 * @return int|null
	 */
	public function getCurrentUserId() {
		if ( null !== $this->currentUser ) {
			return $this->currentUser->id;
		}

		return null;
	}

	/**
	 * Set the current logged in user.
	 *
	 * @param AppUser $user
	 *
	 * @return void
	 */
	public function setCurrentUser( $user ) {
		if ( $user instanceof AppUser ) {
			$this->currentUser = $user;
		}
	}

	public function getPasswordHash( $plainPassword ) {
		return password_hash( $plainPassword, PASSWORD_BCRYPT );
	}

	public function verifyPassword( $plainPassword, $hash ) {
		return password_verify( $plainPassword, $hash );
	}
}