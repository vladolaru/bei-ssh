<?php
/**
 * The 404 message.
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
				<h2 class="title is-2">Where did I put that?</h2>

				<div class="notification is-danger">
					We couldn't find the page you are looking for.
				</div>

			</div>
		</div>
	</div>
</section>
