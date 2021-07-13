<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package varik333
 */

get_header();
?>

<main class="main">
	<section class="page-section banner-section">
		<div class="container">
			<div class="banner-section__inner">
				<div class="swiper-container banner-section__slider">
					<?php echo do_shortcode('[smartslider3 slider="1"]'); ?>
					<!-- <div class="swiper-wrapper">
						<a class="swiper-slide" href="#">
							<img class="banner-section__slider-img" src="<?php echo get_template_directory_uri(); ?>/assets/images/banner-slider.jpg" alt="">
						</a>
						<a class="swiper-slide" href="#">
							<img class="banner-section__slider-img" src="images/banner-slider.jpg" alt="">
						</a>
						<a class="swiper-slide" href="#">
							<img class="banner-section__slider-img" src="images/banner-slider.jpg" alt="">
						</a>
						<a class="swiper-slide" href="#">
							<img class="banner-section__slider-img" src="images/banner-slider.jpg" alt="">
						</a>
						<a class="swiper-slide" href="#">
							<img class="banner-section__slider-img" src="images/banner-slider.jpg" alt="">
						</a>
						<a class="swiper-slide" href="#">
							<img class="banner-section__slider-img" src="images/banner-slider.jpg" alt="">
						</a>
					</div>
					<div class="swiper-pagination"></div>
					<button class="swiper-button-prev"></button>
					<button class="swiper-button-next"></button> -->
				</div>
				<div class="banner-section__item sale-item">
					<?php echo do_shortcode('[contact-form-7 id="106" title="Формачка" html_class="use-floating-validation-tip"]'); ?>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
