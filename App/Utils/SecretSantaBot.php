<?php

/**
 * Secret Santa Bot
 *
 * A class for generating a paired list of people for a secret santa.
 * The goal is to be automated to prevent one person from having to deal with knowing who has who.
 *
 * This code was inspired by the one here: https://github.com/benwatts/SecretSantaBot/blob/master/lib/SecretSantaBot.php
 * This is a heavily modified version of that.
 *
 */
class SecretSantaBot {

	public $paired;

	private $test_mode;
	private $persons;

	const ERROR_NOT_ENOUGH_PEOPLE = "Need at least 3 people for a Secret Santa";
	const ERROR_EXPECTING_ARRAY = "Expecting an array of people and emails. Woops.";
	const ERROR_EMAILING_PAIRS = "Only one pair was found. That's odd. Not emailing.";
	const ERROR_INVALID_EMAIL_ADDRESS = 'An invalid email address was found.';

	/**
	 * Constructor
	 * Ensures that the people array is usable.
	 *
	 * @throws Exception
	 *
	 * @param array $persons  Array of AppPerson.
	 * @param bool  $test_mode Boolean value to determine if debug/test information should be displayed
	 */
	public function __construct( $persons, $test_mode = true ) {

		$this->persons   = $persons;
		$this->test_mode = $test_mode;

		if ( is_array( $persons ) ) {
			if ( count( $persons ) >= 3 ) {
				if ( ! $this->anyInvalidEmails() ) {
					$this->pairPeople();
				} else {
					throw New Exception( self::ERROR_INVALID_EMAIL_ADDRESS );
				}
			} else {
				throw new Exception( self::ERROR_NOT_ENOUGH_PEOPLE );
			}
		} else {
			throw new Exception( self::ERROR_EXPECTING_ARRAY );
		}
	}

	/**
	 * Check through the emails to make sure they're formatted properly.
	 *
	 * @return bool Returns true if any emails are invalid.
	 */
	private function anyInvalidEmails() {

		/** @var AppPerson $person */
		foreach ( $this->persons as $person ) {
			$email = trim( $person->email );
			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Send emails to the Secret Santa pairs.
	 *
	 * If test_mode is set to true, it will output the content of the email to the screen.
	 * If test_mode is false, it will send out emails and provide no feedback.
	 *
	 * @throws Exception
	 *
	 * @param AppRound $round
	 *
	 * @return false|string
	 */
	public function sendEmails( $round ) {

		$output = '';
		$sent = true;
		if ( count( $this->paired ) >= 1 ) {
			foreach ( $this->paired as $key => $pair ) {

				/** @var AppPerson $giver */
				$giver    = $pair[0];
				/** @var AppPerson $receiver */
				$receiver = $pair[1];

				// Process content tags
				$message = $this->parseContentTags( $round->emailTemplate, $giver, $receiver, $round );
				$title = $this->parseContentTags( $round->emailTitle, $giver, $receiver, $round );

				$headers = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
				$headers .= 'From: ' . $round->emailFrom;

				$giver_gravatar    = '<img class="gravatar" src="https://www.gravatar.com/avatar/' . md5( strtolower( $giver->email ) ) . '?s=30" alt="gravatar" />';
				$receiver_gravatar = '<img class="gravatar" src="https://www.gravatar.com/avatar/' . md5( strtolower( $receiver->email ) ) . '?s=30" alt="gravatar" />';
				if ( $this->test_mode ) {
					$output .= "<li>$giver_gravatar <strong>{$giver->firstName}</strong> is giving to <strong>{$receiver->firstName}</strong> $receiver_gravatar</li>";
				} else {
					$sent = mail( $giver->email, $title, $message, $headers );
					$output .= "<li>$giver_gravatar Sent email to: <strong>{$giver->email}</strong>.</li>";
				}
			}

			if ( ! $sent ) {
				return false;
			}

			if ( $this->test_mode ) {
				return '<p>What you see below is how we <em>might</em> pair people together. <br /><em>No emails have been sent.</em></p>' . PHP_EOL .
					'<ul id="test-output">' . $output . '</ul>';
			} else {
				return '<h2>Successfully Sent</h2>' . PHP_EOL .
					'<p>Emails should now be in everyone\'s inbox. Cheers!</p>' . PHP_EOL .
					'<ul id="test-output">' . $output . '</ul>';
			}
		} else {
			throw new Exception( self::ERROR_EMAILING_PAIRS );
		}
	}

	/**
	 * The meat of SecretSantaBot.
	 * The idea here is to mimic 'pulling a name out of a hat'.
	 * As cumbersome as this function may be, it is an improvement over the original: there's no getting caught in potentially-infinite while loops.
	 */
	private function pairPeople() {

		$num              = count( $this->persons );
		$people_giving    = $this->persons;
		$people_receiving = $this->persons;
		$paired           = array();

		/*
		 This was an interesting issue: if $people_giving[n] == $people_recieving[n] then you run into a situation
		 where the first two people can get paired up and you're screwed because it means the last person gets their own name.
		 To get around that, the receiver array is shuffled until the names at the end of the two arrays do not match.
		 */
		do {
			shuffle( $people_receiving );
		} while ( $people_giving[ $num - 1 ]->email == $people_receiving[ $num - 1 ]->email );

		/*
		 Loop through all people, if the giver == receiver, increase the index of the receiver (isn't that just magical?).
		 Remove the giver from the giver array, receiver from the receiver array, when done.
		 */
		while ( count( $people_receiving ) > 0 ) {

			$receiver_index = 0;
			if ( $people_giving[0]->email == $people_receiving[ $receiver_index ]->email ) {
				$receiver_index = 1;
			}

			$paired[] = array( $people_giving[0], $people_receiving[ $receiver_index ] );

			array_splice( $people_receiving, $receiver_index, 1 );
			array_splice( $people_giving, 0, 1 );
		}

		$this->paired = $paired;

		return $paired;
	}

	/**
	 * Replace content tags with their value depending on the given context params.
	 *
	 * @param string $content
	 * @param AppPerson $giver
	 * @param AppPerson $receiver
	 * @param AppRound $round
	 *
	 * @return string
	 */
	protected function parseContentTags( $content, $giver, $receiver, $round ) {
		$giver_gravatar    = '<img class="gravatar" src="https://www.gravatar.com/avatar/' . md5( strtolower( $giver->email ) ) . '?s=30" alt="gravatar" />';
		$receiver_gravatar = '<img class="gravatar" src="https://www.gravatar.com/avatar/' . md5( strtolower( $receiver->email ) ) . '?s=30" alt="gravatar" />';

		if ( false !== strpos( $content, '%giver_first_name%' ) ) {
			$content = str_replace( '%giver_first_name%', $giver->firstName, $content );
		}
		if ( false !== strpos( $content, '%giver_last_name%' ) ) {
			$content = str_replace( '%giver_last_name%', $giver->lastName, $content );
		}
		if ( false !== strpos( $content, '%giver_email%' ) ) {
			$content = str_replace( '%giver_email%', $giver->email, $content );
		}
		if ( false !== strpos( $content, '%giver_gravatar%' ) ) {
			$content = str_replace( '%giver_gravatar%', $giver_gravatar, $content );
		}

		if ( false !== strpos( $content, '%receiver_first_name%' ) ) {
			$content = str_replace( '%receiver_first_name%', $receiver->firstName, $content );
		}
		if ( false !== strpos( $content, '%receiver_last_name%' ) ) {
			$content = str_replace( '%receiver_last_name%', $receiver->lastName, $content );
		}
		if ( false !== strpos( $content, '%receiver_email%' ) ) {
			$content = str_replace( '%receiver_email%', $receiver->email, $content );
		}
		if ( false !== strpos( $content, '%receiver_gravatar%' ) ) {
			$content = str_replace( '%receiver_gravatar%', $receiver_gravatar, $content );
		}

		if ( false !== strpos( $content, '%budget%' ) ) {
			$content = str_replace( '%budget%', $round->budget, $content );
		}

		return $content;
	}
}