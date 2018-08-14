<?php
/**
 * The class that handles data handling for rounds (a model).
 */

/**
 * Class AppRoundsModel
 */
class AppRoundsModel {

	/**
	 * Get rounds according to some conditions.
	 *
	 * @param int|null $userId If given, we will only retrieve rounds belonging to this user.
	 * @param array $clauses The select conditions for getting rounds. If left empty all rounds will be returned.
	 * @return array The rounds selected.
	 */
	public static function getRounds( $userId = null, $clauses = [] ) {
		$db = App::instance()->db;

		if ( ! is_array( $clauses ) ) {
			$clauses = [];
		}

		if ( ! empty( $userId ) ) {
			$clauses['user_id'] = $userId;
		}

		$dbRoundsData = $db->select( 'rounds', [
			'id(id)',
			'user_id(userId)',
			'participants(participants)',
			'budget(budget)',
			'email_title(emailTitle)',
			'email_from(emailFrom)',
			'email_template(emailTemplate)',
			'created(created)',
		], $clauses );

		$rounds = [];
		foreach ( $dbRoundsData as $dbRoundData ) {
			$rounds[] = self::standardizeRoundData( $dbRoundData );
		}

		return $rounds;
	}

	protected static function standardizeRoundData( $roundData ) {
		return new AppRound( $roundData );
	}

	/**
	 * Get a round by it's id.
	 *
	 * @param int $id
	 * @param int|null $userId If given, we will only retrieve rounds belonging to this user.
	 *
	 * @return AppRound|false
	 */
	public static function getRoundById( $id, $userId = null ) {
		$constraints = ['id' => $id, 'LIMIT' => 1 ];
		$rounds = self::getRounds( $userId, $constraints );
		return reset( $rounds );
	}

	/**
	 * Get a round by it's email.
	 *
	 * @param string $email
	 * @param int|null $userId If given, we will only retrieve rounds belonging to this user.
	 *
	 * @return AppRound|false
	 */
	public static function getRoundByEmail( $email, $userId = null ) {
		$constraints = ['email' => $email, 'LIMIT' => 1 ];
		$rounds = self::getRounds( $userId, $constraints );
		return reset( $rounds );
	}

	public static function createRound( $roundData ) {
		$db = App::instance()->db;

		// Set up the default data
		$dbData = [
			'user_id' => null,
			'participants' => '',
			'budget' => 0,
			'email_title' => '',
			'email_from' => '',
			'email_template' => '',
			'created' => date('Y-m-d H:i:s'),
		];

		$currentUser = App::instance()->auth->getCurrentUser();
		if ( ! empty( $currentUser ) ) {
			$dbData['user_id'] = $currentUser->id;
		}

		$dbData = array_merge( $dbData, $roundData );

		if ( ! $db->insert( 'rounds', $dbData ) ) {
			return false;
		}

		return $db->id();
	}

	public static function updateRound( $id, $roundData ) {
		$db = App::instance()->db;

		// First test if the round exists.
		if ( false === self::getRoundById( $id ) ) {
			// If the round doesn't exist, we will create it.
			return self::createRound( $roundData );
		}

		// Set up the default data
		$dbData = [];

		if ( isset( $roundData['participants'] ) ) {
			$dbData['participants'] = $roundData['participants'];
		}

		if ( isset( $roundData['budget'] ) ) {
			$dbData['budget'] = $roundData['budget'];
		}

		if ( isset( $roundData['email_title'] ) ) {
			$dbData['email_title'] = $roundData['email_title'];
		}

		if ( isset( $roundData['email_from'] ) ) {
			$dbData['email_from'] = $roundData['email_from'];
		}

		if ( isset( $roundData['email_template'] ) ) {
			$dbData['email_template'] = $roundData['email_template'];
		}

		if ( isset( $roundData['created'] ) ) {
			$dbData['created'] = $roundData['created'];
		}

		if ( ! $db->update( 'rounds', $dbData, ['id' => $id ] ) ) {
			return false;
		}

		return $db->id();
	}

	public static function deleteRound( $roundId ) {
		$db = App::instance()->db;

		if ( ! $db->delete( 'rounds', [ 'id' => $roundId ] ) ) {
			return false;
		}

		return $db->id();
	}
}