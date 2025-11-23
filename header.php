<?php
/**
 * The header for our theme
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<header class="site-header">
		<nav class="main-navigation">
			<!-- Mobile header with logo and toggle -->
			<div class="mobile-nav-header">
				<div class="mobile-logo">
					<a href="<?php echo esc_url(home_url('/')); ?>">
						<svg class="brand-nav" width="100%" height="100%" viewBox="0 0 220 220" xmlns="http://www.w3.org/2000/svg">
							<circle class="logo-circle" cx="110" cy="110" r="110"/>
							<image href="/wp-content/uploads/2025/10/logo.png" x="33.5" y="36.5" width="153" height="175"/>
						</svg>
					</a>
				</div>
				<button id="menu-toggle" aria-label="Toggle menu">
					<span></span>
					<span></span>
					<span></span>
				</button>
			</div>

			<!-- Desktop centered logo -->
			<a href="<?php echo esc_url(home_url('/')); ?>" class="desktop-logo">
				<svg class="brand-nav" width="100%" height="100%" viewBox="0 0 220 220" xmlns="http://www.w3.org/2000/svg">
					<circle class="logo-circle" cx="110" cy="110" r="110"/>
					<image href="/wp-content/uploads/2025/10/logo.png" x="33.5" y="36.5" width="153" height="175"/>
				</svg>
			</a>

			<!-- Menu -->
			<?php
			wp_nav_menu(array(
				'theme_location' => 'menu-1',
				'container' => false,
				'menu_class' => 'menu-list',
				'menu_id' => 'menuList',
				'fallback_cb' => false
			));
			?>
		</nav>
	</header>
