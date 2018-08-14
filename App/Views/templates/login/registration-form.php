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
				<h2 class="title is-2">You are just one step away..</h2>

				<?php if ( ! empty( $errors ) ) {
					foreach ( $errors as $error ) { ?>
						<div class="notification is-danger">
							<?php echo $error; ?>
						</div>
					<?php }
				} ?>

				<form id="register" method="post">

					<div class="field is-half-desktop is-pulled-left">
						<label class="label">First Name</label>
						<p class="control">
							<input class="input" type="text" name="first_name" placeholder="First Name">
						</p>
					</div>

					<div class="field is-half-desktop is-pulled-right">
						<label class="label">Last Name</label>
						<p class="control">
							<input class="input" type="text" name="last_name" placeholder="Last Name">
						</p>
					</div>

					<div class="field">
						<label class="label">Your email address</label>
						<p class="control has-icons-left has-icons-right">
							<input class="input" type="email" name="email" placeholder="Email" required>
							<span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
						</p>
					</div>
					<div class="field">
						<label class="label">Your account password</label>
						<p class="control has-icons-left">
							<input class="input" type="password" name="password" placeholder="Password" required>
							<span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
						</p>
					</div>
					<div class="field">
						<p class="control">
							<button type="submit" class="button is-primary">Register</button>
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