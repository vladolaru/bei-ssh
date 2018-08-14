<?php
/**
 * The class that handles a person info (an entity).
 */

/**
 * Class AppPerson
 */
class AppPerson {

	/**
	 * @var int|null
	 */
	public $id = null;

	/**
	 * @var int|null
	 */
	public $userId = null;

	/**
	 * @var string|null
	 */
	public $email = null;

	/**
	 * @var string|null
	 */
	public $firstName = null;

	/**
	 * @var string|null
	 */
	public $lastName = null;

	/**
	 * @var string|null
	 */
	public $preferences = null;

	/**
	 * @var string|null
	 */
	public $privateNotes = null;

	/**
	 * @var DateTime|null
	 */
	public $created = null;

	/**
	 * @var DateTime|null
	 */
	public $modified = null;

	public function __construct( $personData ) {
		foreach ( $personData as $key => $value ) {
			switch ( $key ) {
				case 'modified':
				case 'created':
					$this->$key = new DateTime( $value );
					break;
				case 'id':
				case 'userId':
				case 'email':
				case 'firstName':
				case 'lastName':
				case 'preferences':
				case 'privateNotes':
					$this->$key = $value;
					break;
				default:
					break;
			}
		}
	}
}