<?php
/**
 * Footer
 *
 * Construct the Theme's Footer
 *
 * @package XT Framework
 * @subpackage Template Parts
 */

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// vars
$theme_author = apply_filters(
	'xt_theme_author',
	array(
		'name' => __( 'XT Framework', 'xt-framework' ),
		'url'  => 'https://xstreamthemes.com//',
	)
);

$footer_layout		= get_theme_mod( 'footer_layout', 'two' );
$layout				= $footer_layout == 'one' ? ' xt-footer-one-column' : ' xt-footer-two-columns';
$inner_layout		= $footer_layout == 'one' ? 'xt-inner-footer-content' : 'xt-inner-footer-left';
$footer_column_one	= get_theme_mod( 'footer_column_one', '&copy; [year] - [blogname] | All rights reserved' );
$footer_column_two	= get_theme_mod( 'footer_column_two', 'Powered by [theme_author]' );
$search_for			= array( '[year]', '[blogname]', '[theme_author]' );
$replace_with		= array( date( 'Y' ), get_option( 'blogname' ), '<a href="' . esc_url( $theme_author['url'] ) . '">' . $theme_author['name'] . '</a>' );
$footer_column_one	= str_replace( $search_for, $replace_with, $footer_column_one );
$footer_column_two	= str_replace( $search_for, $replace_with, $footer_column_two );
$footer_column_one_layout = get_theme_mod( 'footer_column_one_layout', 'text' );
$footer_column_two_layout = get_theme_mod( 'footer_column_two_layout', 'text' );

?>

		<footer id="footer" class="xt-page-footer" itemscope="itemscope" itemtype="https://schema.org/WPFooter">

			<?php do_action( 'xt_footer_open' ); ?>

			<div class="xt-inner-footer xt-container xt-container-center<?php echo esc_attr( $layout ); ?>">

				<div class="<?php echo esc_attr( $inner_layout ); ?>">

					<?php

					if( $footer_column_one_layout == 'text' ) {

						wp_nav_menu( array(
							'theme_location'	=>		'footer_menu',
							'container'			=>		false,
							'menu_class'		=>		'xt-menu',
							'depth'				=>		'1',
							'fallback_cb'		=>		false,
						));

						echo do_shortcode( apply_filters( 'footer-column-left', $footer_column_one ) );

					} elseif( $footer_column_one_layout == 'menu' ) {

						wp_nav_menu( array(
							'theme_location'	=>		'footer_menu',
							'container'			=>		false,
							'menu_class'		=>		'xt-menu',
							'depth'				=>		'1',
							'fallback_cb'		=>		'xt_menu_fallback',
						));

					}

					?>

				</div>

				<?php if ( $footer_layout == 'two' ) { ?>

				<div class="xt-inner-footer-right">

					<?php

					if( $footer_column_two_layout == 'text' ) {

						echo do_shortcode( apply_filters( 'footer-column-right', $footer_column_two ) );

						wp_nav_menu( array(
							'theme_location'	=>		'footer_menu_right',
							'container'			=>		false,
							'menu_class'		=>		'xt-menu',
							'depth'				=>		'1',
							'fallback_cb'		=>		false,
						));

					} elseif( $footer_column_two_layout == 'menu' ) {

						wp_nav_menu( array(
							'theme_location'	=>		'footer_menu_right',
							'container'			=>		false,
							'menu_class'		=>		'xt-menu',
							'depth'				=>		'1',
							'fallback_cb'		=>		'xt_menu_fallback',
						));

					}

					?>

				</div>

				<?php } ?>

			</div>

			<?php do_action( 'xt_footer_close' ); ?>

		</footer>
