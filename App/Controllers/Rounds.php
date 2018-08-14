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

		// If we've reached thus far, we should display the rounds list view.
		CoreView::render( 'rounds/list.php', [
			'rounds'     => AppRoundsModel::getRounds( App::instance()->auth->getCurrentUserId() ),
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

		// We handle a submit from the user.
		if ( 'POST' === $this->request->method ) {
			// Gather the data.
			$roundData = [];

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
					$errors[] = 'There was an error and we couldn\'t create the round at this time.';
				}
			}
		}

		// If we've reached thus far, we should display the add round (edit empty round) form view.
		CoreView::render( 'rounds/new.php', [
			'round'      => new AppRound( [] ),
			'errors'      => $errors,
			'user'        => App::instance()->auth->getCurrentUser(),
			'routeConfig' => $this->routeConfig,
		] );
	}

	public function removeAction() {
		// First check that a user is logged in.
		if ( false === App::instance()->auth->getCurrentUser() ) {
			// Redirect to login.
			CoreView::redirect( AppConfig::BASE_URL . 'login?login-first=true' );
		}

		$errors = [];
		$messages = [];

		$round = null;
		if ( isset( $this->routeConfig['id'] ) ) {
			$round = AppRoundsModel::getRoundById( $this->routeConfig['id'] );
		}

		if ( empty( $round ) ) {
			throw new \Error( 'Invalid id' );
		}

		if ( $round->userId !== App::instance()->auth->getCurrentUserId() ) {
			throw new \Error( 'This round doesn\'t belong to you. You are being sneaky!' );
		}

		if ( empty( $errors ) ) {

			// We can remove the round.
			if ( false !== AppRoundsModel::deleteRound( $round->id ) ) {
				// Success.
				$messages[] = 'The round was successfully removed from your list.';
			} else {
				$errors[] = 'There was an error and we couldn\'t remove the round at this time.';
			}
		}

		// If we've reached thus far, we should display the rounds list view.
		CoreView::render( 'rounds/list.php', [
			'rounds'     => AppRoundsModel::getRounds( App::instance()->auth->getCurrentUserId() ),
			'errors'      => $errors,
			'messages'    => $messages,
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
		App::instance()->auth->maybeLogIn( $this->request );
	}

	/**
	 * After filter - called after an action method.
	 *
	 * @return void
	 */
	protected function after() {
	}
}