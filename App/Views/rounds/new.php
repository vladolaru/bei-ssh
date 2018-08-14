<?php
/**
 * The round add new view.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

include ABSPATH . '/App/Views/templates/header.php';

// The round form
include ABSPATH . '/App/Views/templates/rounds/new.php';

include ABSPATH . '/App/Views/templates/footer.php';