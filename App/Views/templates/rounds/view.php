<?php
/**
 * The view round template.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/** @var AppRound $round */
?>

<section class="section main">
	<div class="container content">
		<div class="columns is-centered">
			<div class="column is-half">
				<h2 class="title is-2">Details about round..</h2>

				<?php if ( ! empty( $errors ) ) {
					foreach ( $errors as $error ) { ?>
						<div class="notification is-danger">
							<?php echo $error; ?>
						</div>
					<?php }
				} ?>

				<div class="round-details">
					<p><span class="detail-label">Round ID:</span><span class="detail"><?php echo $round->id; ?></span></p>
					<p><span class="detail-label">Round date:</span><span class="detail"><?php echo $round->created->format( 'jS F Y H:s'); ?></span></p>
					<p><span class="detail-label">Number of participants:</span><span class="detail"><?php echo count( $round->participants ); ?></span></p>
					<p><span class="detail-label">Participants:</span></p>
					<p><span class="detail-label">Email title:</span><code class="detail"><?php echo $round->emailTitle; ?></code></p>
					<p><span class="detail-label">Email from:</span><code class="detail"><?php echo $round->emailFrom; ?></code></p>
					<p><span class="detail-label">Email template:</span><code class="detail"><?php echo $round->emailFrom; ?></code></p>
				</div>

				<p>&nbsp;</p>
				<p>
					<a href="<?php echo AppConfig::BASE_URL . 'rounds'; ?>">Back to rounds</a>
				</p>
			</div>
		</div>
	</div>
</section>