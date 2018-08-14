<?php
/**
 * The class that handles data handling for users (a model).
 */

/**
 * Class AppUsersModel
 */
class AppUsersModel {

	/**
	 * Get users according to some conditions.
	 *
	 * @param array $clauses The select conditions for getting users. If left empty all users will be returned.
	 * @return array The users selected.
	 */
	public static function getUsers( $clauses = [] ) {
		$db = App::instance()->db;

		if ( ! is_array( $clauses ) ) {
			$clauses = [];
		}

		$dbUsersData = $db->select( 'users', [
			'id(id)',
			'email(email)',
			'password(password)',
			'first_name(firstName)',
			'last_name(lastName)',
			'registered(registered)',
			'modified(modified)',
		], $clauses );

		$users = [];
		foreach ( $dbUsersData as $dbUserData ) {
			$users[] = self::standardizeUserData( $dbUserData );
		}

		return $users;
	}

	protected static function standardizeUserData( $userData ) {
		return new AppUser( $userData );
	}

	/**
	 * Get a user by it's id.
	 *
	 * @param int $id
	 *
	 * @return AppUser|false
	 */
	public static function getUserById( $id ) {
		$users = self::getUsers( ['id' => $id, 'LIMIT' => 1 ] );
		return reset( $users );
	}

	/**
	 * Get a user by it's email.
	 *
	 * @param string $email
	 *
	 * @return AppUser|false
	 */
	public static function getUserByEmail( $email ) {
		$users = self::getUsers( ['email' => $email, 'LIMIT' => 1 ] );
		return reset( $users );
	}

	public static function createUser( $userData ) {
		$db = App::instance()->db;

		// Set up the default data
		$dbData = [
			'email' => '',
			'password' => '',
			'first_name' => '',
			'last_name' => '',
			'registered' => date('Y-m-d H:i:s'),
			'modified' => date('Y-m-d H:i:s'),
		];

		$dbData = array_merge( $dbData, $userData );

		if ( ! $db->insert( 'users', $dbData ) ) {
			return false;
		}

		return $db->id();
	}

	public static function updateUser( $id, $userData ) {
		$db = App::instance()->db;

		// First test if the user exists.
		if ( false === self::getUserById( $id ) ) {
			// If the user doesn't exist, we will create it.
			return self::createUser( $userData );
		}

		// Set up the default data
		$dbData = [];

		if ( isset( $userData['email'] ) ) {
			$dbData['email'] = $userData['email'];
		}

		if ( isset( $userData['password'] ) ) {
			$dbData['password'] = $userData['password'];
		}

		if ( isset( $userData['first_name'] ) ) {
			$dbData['first_name'] = $userData['first_name'];
		}

		if ( isset( $userData['last_name'] ) ) {
			$dbData['last_name'] = $userData['last_name'];
		}

		if ( isset( $userData['registered'] ) ) {
			$dbData['registered'] = $userData['registered'];
		}

		if ( isset( $userData['modified'] ) ) {
			$dbData['modified'] = $userData['modified'];
		} else {
			$dbData['modified'] = date('Y-m-d H:i:s');
		}

		if ( ! $db->update( 'users', $dbData, ['id' => $id ] ) ) {
			return false;
		}

		return $db->id();
	}
}