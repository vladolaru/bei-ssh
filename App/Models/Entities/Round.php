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
					$this->$key = new DateTime( $value );
					break;
				case 'id':
				case 'userId':
				case 'participants':
				case 'budget':
				case 'emailTitle':
				case 'emailFrom':
				case 'emailTemplate':
					$this->$key = $value;
					break;
				default:
					break;
			}
		}
	}
}