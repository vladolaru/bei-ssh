<?php
/**
 * Created by PhpStorm.
 * User: vladolaru
 * Date: 24/08/2018
 * Time: 14:52
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/** @var array $messages */
/** @var array $errors */

?>

<?php if ( ! empty( $messages ) ) {
	foreach ( $messages as $message ) { ?>
		<div class="notification is-success">
			<?php echo $message; ?>
		</div>
	<?php }
} ?>

<?php if ( ! empty( $errors ) ) {
	foreach ( $errors as $error ) { ?>
		<div class="notification is-danger">
			<?php echo $error; ?>
		</div>
	<?php }
} ?>
