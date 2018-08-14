<?php
/**
 * The class that handles a round info (an entity).
 */

/**
 * Class AppRound
 */
class AppRound {

	/**
	 * @var int|null
	 */
	public $id = null;

	/**
	 * @var int|null
	 */
	public $userId = null;

	/**
	 * @var array|null
	 */
	public $participants = null;

	/**
	 * @var float|null
	 */
	public $budget = null;

	/**
	 * @var string|null
	 */
	public $emailTitle = null;

	/**
	 * @var string|null
	 */
	public $emailFrom = null;

	/**
	 * @var string|null
	 */
	public $emailTemplate = null;

	/**
	 * @var DateTime|null
	 */
	public $created = null;

	public function __construct( $roundData ) {
		foreach ( $roundData as $key => $value ) {
			switch ( $key ) {
				case 'created':
					$this->created = new DateTime( $value );
					break;
				case 'emailTitle':
				case 'email_title':
					$this->emailTitle = $value;
					break;
				case 'emailFrom':
				case 'email_from':
					$this->emailFrom = $value;
					break;
				case 'emailTemplate':
				case 'email_template':
					$this->emailTemplate = $value;
					break;
				case 'userId':
				case 'user_id':
					$this->userId = $value;
					break;
				case 'id':
				case 'participants':
				case 'budget':
					$this->$key = $value;
					break;
				default:
					break;
			}
		}
	}
}