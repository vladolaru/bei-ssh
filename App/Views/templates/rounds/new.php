<?php
/**
 * The new round template.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/** @var AppRound $round */
?>

<section class="section">
	<div class="container content">
		<div class="columns is-centered">
			<div class="column is-half">
				<h2 class="title is-2">Let's get this sleigh going..</h2>

				<?php if ( ! empty( $errors ) ) {
					foreach ( $errors as $error ) { ?>
						<div class="notification is-danger">
							<?php echo $error; ?>
						</div>
					<?php }
				} ?>

				<form id="new-round" method="post">

					<div class="field">
						<label class="label">Choose your participants</label>
						<p class="control">
							<textarea class="textarea" name="participants" placeholder="Participants" rows="3"><?php echo $round->participants;?></textarea>
						</p>
					</div>

					<div class="field">
						<label class="label">Recommended Budget</label>
						<p class="control has-icons-left has-icons-right">
							<input class="input" type="number" name="budget" placeholder="Budget" required value="<?php echo $round->budget;?>">
							<span class="icon is-small is-left"><i class="fas fa-dollar-sign"></i></span>
						</p>
					</div>

					<div class="field">
						<label class="label">Email Title (Template)</label>
						<p class="control has-icons-left has-icons-right">
							<input class="input" type="text" name="email_title" placeholder="Title" required value="<?php echo $round->emailTitle;?>">
							<span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
						</p>
					</div>

					<div class="field">
						<label class="label">Email From</label>
						<p class="control has-icons-left has-icons-right">
							<input class="input" type="text" name="email_from" placeholder="From" required value="<?php echo $round->emailFrom;?>">
							<span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
						</p>
					</div>

					<div class="field">
						<label class="label">Email Content (Template)</label>
						<p class="control">
							<textarea class="textarea" name="email_template" placeholder="Your email content" rows="10"><?php echo $round->emailTemplate ;?></textarea>
						</p>
					</div>

					<div class="field">
						<p class="control">
							<button type="submit" class="button is-primary">Send emails</button>
						</p>
					</div>
				</form>
				<p></p>
				<p>or..</p>
				<p>
					<a href="<?php echo AppConfig::BASE_URL . 'rounds'; ?>">Cancel</a>
				</p>
			</div>
		</div>
	</div>
</section>