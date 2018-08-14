<?php
/**
 * The class that handles a user info (an entity).
 */

/**
 * Class AppUser
 */
class AppUser {

	/**
	 * @var int|null
	 */
	public $id = null;

	/**
	 * @var string|null
	 */
	public $email = null;

	/**
	 * @var string|null
	 */
	public $password = null;

	/**
	 * @var string|null
	 */
	public $firstName = null;

	/**
	 * @var string|null
	 */
	public $lastName = null;

	/**
	 * @var DateTime|null
	 */
	public $registered = null;

	/**
	 * @var DateTime|null
	 */
	public $modified = null;

	public function __construct( $userData ) {
		foreach ( $userData as $key => $value ) {
			switch ( $key ) {
				case 'modified':
				case 'registered':
					$this->$key = new DateTime( $value );
					break;
				case 'id':
				case 'email':
				case 'password':
				case 'firstName':
				case 'lastName':
					$this->$key = $value;
					break;
				default:
					break;
			}
		}
	}
}