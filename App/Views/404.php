<?php
/**
 * The 404 page.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

include ABSPATH . '/App/Views/templates/header.php';

// The 404 message.
include ABSPATH . '/App/Views/templates/errors/404.php';

include ABSPATH . '/App/Views/templates/footer.php';