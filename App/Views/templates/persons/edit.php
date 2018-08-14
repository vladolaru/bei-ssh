<?php
/**
 * The person edit template.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/** @var AppPerson $person */
?>

<section class="section main">
	<div class="container content">
		<div class="columns is-centered">
			<div class="column is-half">
				<h2 class="title is-2">What is this person all about?</h2>

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
							<input class="input" type="text" name="first_name" placeholder="First Name" value="<?php echo $person->firstName ;?>">
						</p>
					</div>

					<div class="field is-half-desktop is-pulled-right">
						<label class="label">Last Name</label>
						<p class="control">
							<input class="input" type="text" name="last_name" placeholder="Last Name" value="<?php echo $person->lastName ;?>">
						</p>
					</div>

					<div class="field">
						<label class="label">Email address</label>
						<p class="control has-icons-left has-icons-right">
							<input class="input" type="email" name="email" placeholder="Email" required value="<?php echo $person->email ;?>">
							<span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
						</p>
					</div>

					<div class="field">
						<label class="label">Personal preferences</label>
						<p class="control">
							<textarea class="textarea" name="preferences" placeholder="Some preferences" rows="4"><?php echo $person->preferences ;?></textarea>
						</p>
					</div>

					<div class="field">
						<label class="label">Private notes about this person</label>
						<p class="control">
							<textarea class="textarea" name="private_notes" placeholder="Things you want to note about this person" rows="6"><?php echo $person->preferences ;?></textarea>
						</p>
					</div>

					<div class="field">
						<p class="control">
							<button type="submit" class="button is-primary">Save person's details</button>
						</p>
					</div>
				</form>
				<p></p>
				<p>or..</p>
				<p>
					<a href="<?php echo AppConfig::BASE_URL; ?>">Cancel</a>
				</p>
			</div>
		</div>
	</div>
</section>