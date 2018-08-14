<?php
/**
 * The template that shows the reset password form.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/** @var array $keyInfo */
/** @var array $messages */
/** @var array $errors */
?>

<section class="section main">
	<div class="container content">
		<div class="columns is-centered">
			<div class="column is-half">
				<h2 class="title is-2">Reset your password</h2>

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

				<?php if ( ! empty( $keyInfo['user_id'] ) ) { ?>
					<form id="reset-password" method="post">

						<input type="hidden" name="user_id" value="<?php echo $keyInfo['user_id']; ?>" />

						<div class="field">
							<label class="label">New password</label>
							<p class="control has-icons-left">
								<input class="input" type="password" name="password" placeholder="Password">
								<span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
							</p>
						</div>
						<div class="field">
							<label class="label">New password confirmation</label>
							<p class="control has-icons-left">
								<input class="input" type="password" name="password_confirm" placeholder="The same password">
								<span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
							</p>
						</div>
						<div class="field">
							<p class="control">
								<button type="submit" class="button is-primary">
									Set password
								</button>
							</p>
						</div>

					</form>
					<p></p>
					<p>or..</p>
				<?php } ?>
				<p>
					<a href="<?php echo AppConfig::BASE_URL . 'login'; ?>">Log into your account</a>
				</p>

			</div>
		</div>
	</div>
</section>