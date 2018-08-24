<?php
/**
 * The 500 error message.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/** @var AppUser|null $user */
/** @var array $routeConfig */
/** @var string $view */

?>

<section class="section main">
	<div class="container content">
		<div class="columns is-centered">
			<div class="column is-half">
				<h2 class="title is-2">Something went wrong.. 😢</h2>

				<div class="notification is-danger">
					<p>Through the twisted paths of computer logic, something went downhill and we couldn't quite figure out a way back.</p>
					<p>We've taken notice of this and next time we promise to do better.</p>
					<p>Thank you for understanding 🙏</p>
				</div>

			</div>
		</div>
	</div>
</section>
