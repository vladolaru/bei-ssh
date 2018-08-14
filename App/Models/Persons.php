<?php
/**
 * The class that handles data handling for persons (a model).
 */

/**
 * Class AppPersonsModel
 */
class AppPersonsModel {

	/**
	 * Get persons according to some conditions.
	 *
	 * @param int|null $userId If given, we will only retrieve persons belonging to this user.
	 * @param array $clauses The select conditions for getting persons. If left empty all persons will be returned.
	 * @return array The persons selected.
	 */
	public static function getPersons( $userId = null, $clauses = [] ) {
		$db = App::instance()->db;

		if ( ! is_array( $clauses ) ) {
			$clauses = [];
		}

		if ( ! empty( $userId ) ) {
			$clauses['user_id'] = $userId;
		}

		$dbPersonsData = $db->select( 'persons', [
			'id(id)',
			'user_id(userId)',
			'first_name(firstName)',
			'last_name(lastName)',
			'email(email)',
			'preferences(preferences)',
			'private_notes(privateNotes)',
			'created(created)',
			'modified(modified)',
		], $clauses );

		$persons = [];
		foreach ( $dbPersonsData as $dbPersonData ) {
			$persons[] = self::standardizePersonData( $dbPersonData );
		}

		return $persons;
	}

	protected static function standardizePersonData( $personData ) {
		return new AppPerson( $personData );
	}

	/**
	 * Get a person by it's id.
	 *
	 * @param int $id
	 * @param int|null $userId If given, we will only retrieve persons belonging to this user.
	 *
	 * @return AppPerson|false
	 */
	public static function getPersonById( $id, $userId = null ) {
		$constraints = ['id' => $id, 'LIMIT' => 1 ];
		$persons = self::getPersons( $userId, $constraints );
		return reset( $persons );
	}

	/**
	 * Get a person by it's email.
	 *
	 * @param string $email
	 * @param int|null $userId If given, we will only retrieve persons belonging to this user.
	 *
	 * @return AppPerson|false
	 */
	public static function getPersonByEmail( $email, $userId = null ) {
		$constraints = ['email' => $email, 'LIMIT' => 1 ];
		$persons = self::getPersons( $userId, $constraints );
		return reset( $persons );
	}

	public static function createPerson( $personData ) {
		$db = App::instance()->db;

		// Set up the default data
		$dbData = [
			'user_id' => null,
			'first_name' => '',
			'last_name' => '',
			'email' => '',
			'preferences' => '',
			'private_notes' => '',
			'created' => date('Y-m-d H:i:s'),
			'modified' => date('Y-m-d H:i:s'),
		];

		$currentUser = App::instance()->auth->getCurrentUser();
		if ( ! empty( $currentUser ) ) {
			$dbData['user_id'] = $currentUser->id;
		}

		$dbData = array_merge( $dbData, $personData );

		if ( ! $db->insert( 'persons', $dbData ) ) {
			return false;
		}

		return $db->id();
	}

	public static function updatePerson( $id, $personData ) {
		$db = App::instance()->db;

		// First test if the person exists.
		if ( false === self::getPersonById( $id ) ) {
			// If the person doesn't exist, we will create it.
			return self::createPerson( $personData );
		}

		// Set up the default data
		$dbData = [];

		if ( isset( $personData['email'] ) ) {
			$dbData['email'] = $personData['email'];
		}

		if ( isset( $personData['first_name'] ) ) {
			$dbData['first_name'] = $personData['first_name'];
		}

		if ( isset( $personData['last_name'] ) ) {
			$dbData['last_name'] = $personData['last_name'];
		}

		if ( isset( $personData['preferences'] ) ) {
			$dbData['preferences'] = $personData['preferences'];
		}

		if ( isset( $personData['private_notes'] ) ) {
			$dbData['private_notes'] = $personData['private_notes'];
		}

		if ( isset( $personData['created'] ) ) {
			$dbData['created'] = $personData['created'];
		}

		if ( isset( $personData['modified'] ) ) {
			$dbData['modified'] = $personData['modified'];
		} else {
			$dbData['modified'] = date('Y-m-d H:i:s');
		}

		if ( ! $db->update( 'persons', $dbData, ['id' => $id ] ) ) {
			return false;
		}

		return $db->id();
	}

	public static function deletePerson( $personId ) {
		$db = App::instance()->db;

		if ( ! $db->delete( 'persons', [ 'id' => $personId ] ) ) {
			return false;
		}

		return $db->id();
	}
}