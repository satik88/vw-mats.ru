<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package varik333
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>


<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<header id="masthead" class="site-header">
		<div class="header__top">
			<div class="container">
				<div class="header__top-inner">
					<nav class="menu">
						<?php wp_nav_menu(['theme_location' => 'header-menu2',]); ?>
					</nav>
					<div class="logo">
						<a class="logo__img" href="">
							<picture>
								<source srcset="images/logo-mobile.svg" media="(max-width: 380px)">
								<img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.svg" alt="">
							</picture>
						</a>
					</div>
					<div class="header__box">
						<p class="header__adress">
							8-800-222-64-33
						</p>
						<ul class="user-list">
							<li class="user-list__item">
								<a class="user-list__link" href="#">
									<img src="<?php echo get_template_directory_uri(); ?>/assets/images/heart.svg" alt="">
								</a>
							</li>
							<li class="user-list__item">
								<a class="user-list__link" href="#">
									<img src="<?php echo get_template_directory_uri(); ?>/assets/images/user.svg" alt="">
								</a>
							</li>
							<li class="user-list__item">
								<a class="user-list__link basket" href="#">
									<img src="<?php echo get_template_directory_uri(); ?>/assets/images/basket.svg" alt="">
									<p class="basket__num">1</p>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="header__bottom">
			<div class="container">
				<?php wp_nav_menu(['theme_location' => 'header-menu',]); ?>
			</div>
		</div>
	</header>