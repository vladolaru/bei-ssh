<?php
/**
 * The person edit view.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

include ABSPATH . '/App/Views/templates/header.php';

// The person edit form
include ABSPATH . '/App/Views/templates/persons/edit.php';

include ABSPATH . '/App/Views/templates/footer.php';