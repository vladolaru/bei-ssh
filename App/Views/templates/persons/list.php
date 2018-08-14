<?php
/**
 * The persons list template.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/** @var array $messages */
/** @var array $errors */
/** @var array $persons */
?>

<section class="section main">
	<div class="container content">
		<div class="columns is-centered">
			<div class="column is-half">
				<h2 class="title is-2">Your Secret Santa Players</h2>

				<?php if ( ! empty( $messages ) ) {
					foreach ( $messages as $message ) { ?>
						<div class="notification is-success">
							<?php echo $message; ?>
						</div>
					<?php }
				} ?>

				<?php if ( ! empty( $errors ) ) {
					foreach ( $errors as $error ) { ?>
						<div class="notification is-danger">
							<?php echo $error; ?>
						</div>
					<?php }
				} ?>

				<?php if ( empty( $persons ) ) { ?>
					<p> You don't have any players yet.</p>
				<?php } else {
					echo '<ul class="persons-list">';
				/** @var AppPerson $person */
				foreach ( $persons as $person ) { ?>
						<li class="person">
							<div class="person-id"><?php echo $person->id; ?></div>
							<div class="person-name"><?php echo $person->firstName . ' ' . $person->lastName; ?></div>
							<div class="person-email"><?php echo $person->email; ?></div>
							<div class="person-controls">
								<a class="button is-small" href="<?php echo AppConfig::BASE_URL . 'person/' . $person->id . '/edit'; ?>"><i class="fas fa-edit"></i></a>
								<a class="button is-small" href="<?php echo AppConfig::BASE_URL . 'person/' . $person->id . '/remove'; ?>"><i class="fas fa-trash"></i></a>
							</div>
						</li>
					<?php }
					echo '</ul>';
				} ?>

				<p>
					<a class="button is-primary" href="<?php echo AppConfig::BASE_URL . 'person/add'; ?>">Add a new one</a>
				</p>
			</div>
		</div>
	</div>
</section>