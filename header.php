<!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?>>

<head>
<?php wp_head(); // Hook required for scripts, styles, and other <head> items. ?>
</head>

<body <?php hybrid_attr( 'body' ); ?>>

	<div id="container">

		<div class="skip-link">
			<a href="#content" class="screen-reader-text"><?php _e( 'Skip to content', 'envince' ); ?></a>
		</div><!-- .skip-link -->

		<?php
		$phone_info 	= get_theme_mod( 'envince_phone_info' );
		$email_info 	= get_theme_mod( 'envince_email_info' );
		$email			= sanitize_email($email_info); // Added layer of security from e-mail harvesters
		$location_info  = get_theme_mod( 'envince_location_info' );

		?>

		<header <?php hybrid_attr( 'header' ); ?>>

			<div id="header-top">
				<div  class="container">
					<div class="row">

						<div class="info-icons col-md-6 col-sm-12 pull-left">
							<ul>
							<?php if(!empty($phone_info)):?>
							<li><a href="tel:<?php echo $phone_info;?>"><?php echo $phone_info;?></a></li>
							<?php endif;?>

							<?php if(!empty($email_info)):?>
							<li><a href="mailto:<?php echo antispambot($email,1); ?>"><?php echo antispambot($email); ?></a></li>
							<?php endif;?>

							<?php if(!empty($location_info)):?>
							<li><i class="fa fa-location-arrow"></i> <?php echo $location_info;?></li>
							<?php endif;?>
							</ul>

						</div>

						<div class="social-icons col-md-6 col-sm-12 pull-right">
							<?php hybrid_get_menu( 'social-header' ); // Loads the menu/social-header.php template. ?>
						</div>

					</div>
				</div>
			</div>

			<div id="main-header" class="container">
				<div class="row">

					<div id="branding" class="site-branding col-md-4">


						<?php if ( ( get_theme_mod( 'envince_logo' ) != "" ) && ( ! function_exists( 'the_custom_logo' ) ) ) : // If there's a logo?>

							<div class="header-logo">

								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo get_theme_mod( 'envince_logo' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></a>
							</div>

						<?php elseif ( function_exists( 'the_custom_logo' ) && has_custom_logo( $blog_id = 0 ) ) : ?>

							<div class="header-logo">
								<?php envince_the_custom_logo(); ?>
							</div>

						<?php endif; // End check for logo ?>



						<div class="header-text">
						<?php
						if ( get_theme_mod( 'envince_site_title', '1' ) == '1') {
							hybrid_site_title();
						}
						if ( get_theme_mod( 'envince_site_description', '1' ) == '1') {
							hybrid_site_description();
						}
						?>
						</div>
					</div><!-- #branding -->

					<div class="header-right-section col-md-8 pull-right">
						<?php hybrid_get_sidebar( 'header' ); // Loads the sidebar/header.php template. ?>
					</div>

				</div>
			</div>

			<div id="main-menu" class="clearfix">

				<?php hybrid_get_menu( 'primary' ); // Loads the menu/primary.php template. ?>

			</div>

		</header><!-- #header -->

		<?php if ( get_header_image() || ( function_exists( 'the_custom_header_markup' ) && is_front_page() && has_header_video() ) ) : // If there's a header image. ?>

		<section id="intro" data-speed="6" data-type="background">
			<?php if ( function_exists( 'the_custom_header_markup' ) && is_front_page() && has_header_video() ) {
				the_custom_header_markup();
			} ?>
		</section>

		<?php endif; // End check for header image. ?>

		<div id="#site-content" class="site-content clearfix">

			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<?php hybrid_get_menu( 'breadcrumbs' ); // Loads the menu/breadcrumbs.php template. ?>
					</div>
