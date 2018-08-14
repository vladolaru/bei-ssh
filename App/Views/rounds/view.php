<?php
/**
 * The single round view.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

include ABSPATH . '/App/Views/templates/header.php';

// The rounds list
include ABSPATH . '/App/Views/templates/rounds/view.php';

include ABSPATH . '/App/Views/templates/footer.php';