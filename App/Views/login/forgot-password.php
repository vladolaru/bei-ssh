<?php
/**
 * The forgot password form view.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

include ABSPATH . '/App/Views/templates/header.php';

// The forgot password form
include ABSPATH . '/App/Views/templates/login/forgot-password-form.php';

include ABSPATH . '/App/Views/templates/footer.php';