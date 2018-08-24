<?php
/**
 * The 500 page.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

include ABSPATH . '/App/Views/templates/header.php';

// The 500 message.
include ABSPATH . '/App/Views/templates/errors/500.php';

include ABSPATH . '/App/Views/templates/footer.php';