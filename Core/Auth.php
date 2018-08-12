<?php
/**
 * Class to handle our authentication needs.
 */

class CoreAuth {
	/**
	 * Attempt to sign in a user based on the info available in the request object.
	 *
	 * @param CoreRequest $request
	 *
	 * @return AppUser|false Returns the user object if successful. false on failure.
	 */
	function maybeSignIn( $request ) {


		return false;
	}

	/**
	 * Sign out the current user, if there is any logged in.
	 *
	 * @return bool Returns true if there was a user logged in. false if there was no user to log out in the fist place.
	 */
	function maybeSignOut() {


		return false;
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
	function authenticate( $email, $password ) {
		$email    = filter_var( $email, FILTER_VALIDATE_EMAIL );
		$password = trim( $password );


		// Find a user and validate the credentials.


		if ( $user == null ) {
			// TODO what should the error message be? (Or would these even happen?)
			// Only needed if all authentication handlers fail to return anything.
			$user = new WP_Error( 'authentication_failed', __( '<strong>ERROR</strong>: Invalid username, email address or incorrect password.' ) );
		}

		$ignore_codes = array( 'empty_username', 'empty_password' );

		if ( is_wp_error( $user ) && ! in_array( $user->get_error_code(), $ignore_codes ) ) {
			/**
			 * Fires after a user login has failed.
			 *
			 * @since 2.5.0
			 * @since 4.5.0 The value of `$username` can now be an email address.
			 *
			 * @param string $email Username or email address.
			 */
			do_action( 'wp_login_failed', $email );
		}

		return $user;
	}
}