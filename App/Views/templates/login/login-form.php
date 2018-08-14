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
				<h2 class="title is-2">Get that Santa Going..</h2>

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

				<form id="login" method="post">

					<div class="field">
						<label class="label">Your email address</label>
						<p class="control has-icons-left has-icons-right">
							<input class="input" type="email" name="email" placeholder="Email">
							<span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
						</p>
					</div>
					<div class="field">
						<label class="label">Your password</label>
						<p class="control has-icons-left">
							<input class="input" type="password" name="password" placeholder="Password">
							<span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
						</p>
					</div>
					<div class="field">
						<p class="control">
							<button type="submit" class="button is-primary is-pulled-left">
								Login
							</button>
							<div class="is-pulled-right">
								<a href="<?php echo AppConfig::BASE_URL . 'login/forgot-password'; ?>">Forgot your password?</a>
							</div>
						</p>
					</div>

				</form>
				<p class="is-clearfix"></p>
				<p>or..</p>
				<p>
					<a href="<?php echo AppConfig::BASE_URL . 'register'; ?>">Register a new account</a>
				</p>

			</div>
		</div>
	</div>
</section>