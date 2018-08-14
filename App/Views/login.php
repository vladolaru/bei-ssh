<?php
/**
 * The login form view.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

include ABSPATH . '/App/Views/templates/header.php';

// The login form
include ABSPATH . '/App/Views/templates/login/login-form.php';

include ABSPATH . '/App/Views/templates/footer.php';