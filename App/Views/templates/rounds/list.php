<?php
/**
 * The rounds list template.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

?>

<section class="section">
	<div class="container content">
		<div class="columns is-centered">
			<div class="column is-half">
				<h2 class="title is-2">Your Secret Santa past rounds</h2>

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

				<?php if ( empty( $rounds ) ) { ?>
					<p> You don't have any rounds yet.</p>
				<?php } else {
					echo '<ul class="rounds-list">';
				/** @var AppRound $round */
				foreach ( $rounds as $round ) { ?>
						<li class="round">
							<div class="round-id"><?php echo $round->id; ?></div>
							<div class="round-name"><?php echo $round->created->format( 'jS F Y H:s'); ?></div>
							<div class="round-email"><?php echo count( $round->participants ); ?> participants</div>
							<div class="round-controls">
								<a class="button is-small" href="<?php echo AppConfig::BASE_URL . 'round/' . $round->id . '/view'; ?>"><i class="fas fa-edit"></i></a>
								<a class="button is-small" href="<?php echo AppConfig::BASE_URL . 'round/' . $round->id . '/remove'; ?>"><i class="fas fa-trash"></i></a>
							</div>
						</li>
					<?php }
					echo '</ul>';
				} ?>

				<p>
					<a class="button is-primary" href="<?php echo AppConfig::BASE_URL . 'round/new'; ?>">Start a new round</a>
				</p>
			</div>
		</div>
	</div>
</section>