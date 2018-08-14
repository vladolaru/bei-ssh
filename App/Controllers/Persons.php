<?php
/**
 * The Persons controller.
 */

/**
 * Class AppPersonsController
 */
class AppPersonsController extends CoreController {

	public function listAction() {
		// First check that a user is logged in.
		if ( false === App::instance()->auth->getCurrentUser() ) {
			// Redirect to login.
			CoreView::redirect( AppConfig::BASE_URL . 'login?login-first=true' );
		}

		$errors = [];
		$messages = [];

		if ( ! empty( $this->request->query['saved'] ) ) {
			$messages[] = 'We have successfully saved the person\'s details.';
		}

		if ( ! empty( $this->request->query['added'] ) ) {
			$messages[] = 'We have successfully added the person to your list.';
		}

		// If we've reached thus far, we should display the persons list view.
		CoreView::render( 'persons/list.php', [
			'persons'     => AppPersonsModel::getPersons( App::instance()->auth->getCurrentUserId() ),
			'messages'    => $messages,
			'errors'      => $errors,
			'user'        => App::instance()->auth->getCurrentUser(),
			'routeConfig' => $this->routeConfig,
		] );
	}

	public function addAction() {
		// First check that a user is logged in.
		if ( false === App::instance()->auth->getCurrentUser() ) {
			// Redirect to login.
			CoreView::redirect( AppConfig::BASE_URL . 'login?login-first=true' );
		}

		$errors = [];

		// We handle a submit from the user.
		if ( 'POST' === $this->request->method ) {
			// Gather the data.
			$personData = [];

			if ( isset( $this->request->data['first_name'] ) ) {
				$personData['first_name'] = trim( strip_tags( $this->request->data['first_name'] ) );
			}

			if ( isset( $this->request->data['last_name'] ) ) {
				$personData['last_name'] = trim( strip_tags( $this->request->data['last_name'] ) );
			}

			if ( ! empty( $this->request->data['email'] ) ) {
				$personData['email'] = filter_var( trim( $this->request->data['email'], FILTER_VALIDATE_EMAIL ) );
			}

			if ( isset( $this->request->data['preferences'] ) ) {
				$personData['preferences'] = trim( $this->request->data['preferences'] );
			}

			if ( isset( $this->request->data['private_notes'] ) ) {
				$personData['private_notes'] = trim( $this->request->data['private_notes'] );
			}

			// Now check if we have all the data that we need.
			if ( empty( $personData['email'] ) ) {
				$errors[] = 'You need to fill in all the required fields.';
			}

			// Check if another user with the same email doesn't exist.
			if ( false !== AppPersonsModel::getPersonByEmail( $personData['email'], App::instance()->auth->getCurrentUserId() ) ) {
				$errors[] = 'A person with the same email address already exists.';
			}

			if ( empty( $errors ) ) {
				// Bind the created person to the current logged in user.
				$personData['user_id'] =  App::instance()->auth->getCurrentUserId();

				// We can create the person.
				if ( false !== AppPersonsModel::createPerson( $personData ) ) {
					// Success. Redirect to main page.
					CoreView::redirect( AppConfig::BASE_URL . '?added=true' );
				} else {
					$errors[] = 'There was an error and we couldn\'t create the person at this time.';
				}
			}
		}

		// If we've reached thus far, we should display the add person (edit empty person) form view.
		CoreView::render( 'persons/edit.php', [
			'person'      => new AppPerson( [] ),
			'errors'      => $errors,
			'user'        => App::instance()->auth->getCurrentUser(),
			'routeConfig' => $this->routeConfig,
		] );
	}

	public function editAction() {
		// First check that a user is logged in.
		if ( false === App::instance()->auth->getCurrentUser() ) {
			// Redirect to login.
			CoreView::redirect( AppConfig::BASE_URL . 'login?login-first=true' );
		}

		$errors = [];

		$person = null;
		if ( isset( $this->routeConfig['id'] ) ) {
			$person = AppPersonsModel::getPersonById( $this->routeConfig['id'] );
		}

		if ( empty( $person ) ) {
			throw new \Error( ' Invalid id' );
		}

		// We handle a submit from the user.
		if ( 'POST' === $this->request->method ) {
			// Gather the data.
			$personData = [];

			if ( isset( $this->request->data['first_name'] ) ) {
				$personData['first_name'] = trim( strip_tags( $this->request->data['first_name'] ) );
			}

			if ( isset( $this->request->data['last_name'] ) ) {
				$personData['last_name'] = trim( strip_tags( $this->request->data['last_name'] ) );
			}

			if ( ! empty( $this->request->data['email'] ) ) {
				$personData['email'] = filter_var( trim( $this->request->data['email'], FILTER_VALIDATE_EMAIL ) );
			}

			if ( isset( $this->request->data['preferences'] ) ) {
				$personData['preferences'] = trim( $this->request->data['preferences'] );
			}

			if ( isset( $this->request->data['private_notes'] ) ) {
				$personData['private_notes'] = trim( $this->request->data['private_notes'] );
			}

			// Now check if we have all the data that we need.
			if ( empty( $personData['email'] ) ) {
				$errors[] = 'You need to fill in all the required fields.';
			}

			// Check if another user with the same email doesn't exist.
			if ( $person->email !== $personData['email'] && false !== AppPersonsModel::getPersonByEmail( $personData['email'], App::instance()->auth->getCurrentUserId() ) ) {
				$errors[] = 'A person with the same email address already exists.';
			}

			if ( empty( $errors ) ) {
				// We don't want to change the associated user.
				if ( isset( $personData['user_id'] ) ) {
					unset( $personData['user_id'] );
				}

				// We can update the person.
				if ( false !== AppPersonsModel::updatePerson( $person->id, $personData ) ) {
					// Success. Redirect to main page.
					CoreView::redirect( AppConfig::BASE_URL . '?saved=true' );
				} else {
					$errors[] = 'There was an error and we couldn\'t save the person\'s details at this time.';
				}
			}
		}

		// If we've reached thus far, we should display the person edit form view.
		CoreView::render( 'persons/edit.php', [
			'person'      => $person,
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

		$person = null;
		if ( isset( $this->routeConfig['id'] ) ) {
			$person = AppPersonsModel::getPersonById( $this->routeConfig['id'] );
		}

		if ( empty( $person ) ) {
			throw new \Error( 'Invalid id' );
		}

		if ( $person->userId !== App::instance()->auth->getCurrentUserId() ) {
			throw new \Error( 'This person doesn\'t belong to you. You are being sneaky!' );
		}

		if ( empty( $errors ) ) {

			// We can remove the person.
			if ( false !== AppPersonsModel::deletePerson( $person->id ) ) {
				// Success.
				$messages[] = 'The person was successfully removed from your list.';
			} else {
				$errors[] = 'There was an error and we couldn\'t remove the person at this time.';
			}
		}

		// If we've reached thus far, we should display the persons list view.
		CoreView::render( 'persons/list.php', [
			'persons'     => AppPersonsModel::getPersons( App::instance()->auth->getCurrentUserId() ),
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
		// We attempt to login the user before every action.
		App::instance()->auth->maybeLogIn( $this->request );
	}
}