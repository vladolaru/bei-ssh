<?php
/**
 * The template that shows the registration form.
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
				<h2 class="title is-2">Password reset email</h2>

				<?php if ( ! empty( $errors ) ) {
					foreach ( $errors as $error ) { ?>
						<div class="notification is-danger">
							<?php echo $error; ?>
						</div>
					<?php }
				} ?>

				<p>We will send you an email to the address below with the information needed for you to change your password.</p>

				<form id="forgot-password" method="post">

					<div class="field">
						<label class="label">Your email address</label>
						<p class="control has-icons-left has-icons-right">
							<input class="input" type="email" name="email" placeholder="Email">
							<span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
						</p>
					</div>
					<div class="field">
						<p class="control">
							<button type="submit" class="button is-primary">
								Send email
							</button>
						</p>
					</div>

				</form>
				<p></p>
				<p>or..</p>
				<p>
					<a href="<?php echo AppConfig::BASE_URL . 'login'; ?>">Log into your account</a>
				</p>

			</div>
		</div>
	</div>
</section>