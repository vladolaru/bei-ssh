<?php
/**
 * The app header template. It includes the branding, navigation and current user controls.
 */

// Bail if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/** @var AppUser|null $user */
/** @var array $routeConfig */
/** @var string $view */

?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SSH</title>

	<link rel="stylesheet" href="<?php echo AppConfig::BASE_URL . '/assets/vendor/bulma/css/bulma.css?v=0.7.1'; ?>">
	<link rel="stylesheet" href="<?php echo AppConfig::BASE_URL . '/assets/css/main.css?v=' . AppConfig::VERSION; ?>">

	<script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>
</head>

<body>

<section class="hero is-primary">
	<div class="hero-head">
		<div class="container">
			<?php if ( ! empty( $user ) ) { ?>
			<div class="logged-in is-pulled-right">
				Welcome back, <?php echo $user->firstName; ?>!
				<span class="loggout">(<a href="<?php echo AppConfig::BASE_URL . 'logout'; ?>">Logout</a>)</span>
			</div>
			<?php } ?>
		</div>
	</div>
	<div class="hero-body is-paddingless">
		<div class="container">
			<div class="columns">
				<div class="column is-two-thirds">
					<h1 class="title">
						SSH
					</h1>
					<h2 class="subtitle">
						Santa's Secret Helper
					</h2>
				</div>
				<div class="column">

				</div>
			</div>
		</div>
	</div>
	<div class="hero-foot">
		<div class="container">
			<?php if ( ! empty( $user ) ) { ?>
				<div class="nav-main is-pulled-right">
					<div class="is-grouped">
						<a class="button <?php if ( 0 === strpos( $view, 'person' ) ) { echo 'is-primary'; } ?> is-inverted" href="<?php echo AppConfig::BASE_URL . 'persons'; ?>">Persons</a>
						<a class="button <?php if ( 0 === strpos( $view, 'round' ) ) { echo 'is-primary'; } ?> is-inverted" href="<?php echo AppConfig::BASE_URL . 'rounds'; ?>">Rounds</a>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
