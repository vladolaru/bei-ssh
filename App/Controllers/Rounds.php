<?php
/**
 * The Rounds controller.
 */

/**
 * Class AppRoundsController
 */
class AppRoundsController extends CoreController {

	public function listAction() {
		// First check that a user is logged in.
		if ( false === App::instance()->auth->getCurrentUser() ) {
			// Redirect to login.
			CoreView::redirect( AppConfig::BASE_URL . 'login?login-first=true' );
		}

		$errors = [];
		$messages = [];

		if ( ! empty( $this->request->query['added'] ) ) {
			$messages[] = 'We have successfully sent your Secret Santa round.';
		}

		// If we've reached thus far, we should display the rounds list view.
		CoreView::render( 'rounds/list.php', [
			'rounds'      => AppRoundsModel::getRounds( App::instance()->auth->getCurrentUserId() ),
			'messages'    => $messages,
			'errors'      => $errors,
			'user'        => App::instance()->auth->getCurrentUser(),
			'routeConfig' => $this->routeConfig,
		] );
	}

	public function viewAction() {
		// First check that a user is logged in.
		if ( false === App::instance()->auth->getCurrentUser() ) {
			// Redirect to login.
			CoreView::redirect( AppConfig::BASE_URL . 'login?login-first=true' );
		}

		$errors = [];

		$round = null;
		if ( isset( $this->routeConfig['id'] ) ) {
			$round = AppRoundsModel::getRoundById( $this->routeConfig['id'], App::instance()->auth->getCurrentUserId() );
		}

		if ( empty( $round ) ) {
			throw new \Error( ' Invalid id.' );
		}

		// If we've reached thus far, we should display the single round view.
		CoreView::render( 'rounds/view.php', [
			'round'       => $round,
			'errors'      => $errors,
			'user'        => App::instance()->auth->getCurrentUser(),
			'routeConfig' => $this->routeConfig,
		] );
	}

	public function newAction() {
		// First check that a user is logged in.
		if ( false === App::instance()->auth->getCurrentUser() ) {
			// Redirect to login.
			CoreView::redirect( AppConfig::BASE_URL . 'login?login-first=true' );
		}

		$errors = [];

		// Start with no data. We will store here any posted data so we can reshow it in case of error.
		$roundData = [];

		// We handle a submit from the user.
		if ( 'POST' === $this->request->method ) {

			if ( ! empty( $this->request->data['email_title'] ) ) {
				$roundData['email_title'] = trim( $this->request->data['email_title'] );
			}

			if ( ! empty( $this->request->data['email_from'] ) ) {
				$roundData['email_from'] = trim( $this->request->data['email_from'] );
			}

			if ( ! empty( $this->request->data['email_template'] ) ) {
				$roundData['email_template'] = trim( $this->request->data['email_template'] );
			}

			if ( isset( $this->request->data['participants'] ) ) {
				$roundData['participants'] = trim( $this->request->data['participants'] );
			}

			if ( isset( $this->request->data['budget'] ) ) {
				$roundData['budget'] = floatval( $this->request->data['budget'] );
			}

			// Now check if we have all the data that we need.
			if ( empty( $roundData['participants'] ) ) {
				$errors[] = 'You need to fill in all the required fields.';
			}

			if ( empty( $errors ) ) {
				// Bind the created round to the current logged in user.
				$roundData['user_id'] =  App::instance()->auth->getCurrentUserId();

				// We can create the round.
				if ( false !== AppRoundsModel::createRound( $roundData ) ) {
					// Success. Redirect to main page.
					CoreView::redirect( AppConfig::BASE_URL . 'rounds?added=true' );
				} else {
					$errors[] = 'There was an error and we couldn\'t send the Secret Santa round at this time. Please try again.';
				}
			}
		}

		// If we've reached thus far, we should display the add round (edit empty round) form view.
		CoreView::render( 'rounds/new.php', [
			'round'      => new AppRound( $roundData ),
			'errors'      => $errors,
			'user'        => App::instance()->auth->getCurrentUser(),
			'routeConfig' => $this->routeConfig,
		] );
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