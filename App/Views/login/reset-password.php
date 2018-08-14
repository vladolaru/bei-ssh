<?php
/**
 * The reset password form view.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

include ABSPATH . '/App/Views/templates/header.php';

// The reset password form
include ABSPATH . '/App/Views/templates/login/reset-password-form.php';

include ABSPATH . '/App/Views/templates/footer.php';