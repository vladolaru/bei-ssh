<?php
/**
 * The persons list view.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

include ABSPATH . '/App/Views/templates/header.php';

// The persons list
include ABSPATH . '/App/Views/templates/persons/list.php';

include ABSPATH . '/App/Views/templates/footer.php';