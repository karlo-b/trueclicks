<?php
/**
 * Helpers
 *
 * Collection of helper functions
 *
 * @package XT Framework
 */

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * strpos array helper function
 */
function xt_strposa( $haystack, $needles, $offset = 0 ) {

    if( !is_array( $needles ) ) $needles = array( $needles );

    foreach( $needles as $needle ) {
        if( strpos( $haystack, $needle, $offset ) !== false ) return true; // stop on first true result
    }

    return false;

}

/**
 * Pingback
 */
function xt_pingback_header() {

	// add Pingback header if we're on a singular & pings are open
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="'. esc_url( get_bloginfo( 'pingback_url' ) ) .'">';
	}

}
add_action( 'wp_head', 'xt_pingback_header' );

/**
 * Schema Markup
 */
function xt_body_schema_markup() {

	// Blog variable
	$is_blog = ( is_home() || is_date() || is_category() || is_author() || is_tag() || is_attachment() || is_singular( 'post' ) ) ? true : false;

	// Default itemtype
	$itemtype = 'WebPage';

	// Define itemtype for Blog pages, otherwise use WebPage
	$itemtype = ( $is_blog ) ? 'Blog' : $itemtype;

	// Define itemtype for search results, otherwise use WebPage
	$itemtype = ( is_search() ) ? 'SearchResultsPage' : $itemtype;

	// Make result filterable
	$result = apply_filters( 'xt_body_itemtype', $itemtype );

	// Output
	echo 'itemscope="itemscope" itemtype="https://schema.org/'. esc_html( $result ) . '"'; // WPCS: XSS ok.

}

/**
 * Inner Content
 */
function xt_inner_content( $echo = true ) {

	if( is_singular() ) {

		// vars
		$options = get_post_meta( get_the_ID(), 'xt_options', true );

		// checking if template is set to full width
		// return false if $options is empty
		$fullwidth = $options ? in_array( 'full-width', $options ) : false;

		// checking if template is set to contained
		// return false if $options is empty
		$contained = $options ? in_array( 'contained', $options ) : false;

		// construct inner-content wrapper
		// return false if template is set to full-width
		$inner_content = $fullwidth ? false : apply_filters( 'xt_inner_content', '<div id="inner-content" class="xt-container xt-container-center">' );

		// check if Premium Add-On is active
		// only proceed if template is not set to contained
		if( xt_is_premium() && !$contained ) {

			// vars
			$xt_settings = get_option( 'xt_settings' );

			// get the array of post types that are set to full width under Appearance -> Theme Settings -> Global Templat Settings
			$fullwidth_global = isset( $xt_settings['xt_fullwidth_global'] ) ? $xt_settings['xt_fullwidth_global'] : array();

			// if current post type has been set to full-width globally, set $inner_content to false
			$fullwidth_global && in_array( get_post_type(), $fullwidth_global ) ? $inner_content = false : '';

		}

	// on archives, we only add the xt_inner_content filter
	} else {

		$inner_content = apply_filters( 'xt_inner_content', '<div id="inner-content" class="xt-container xt-container-center xt-padding-medium">' );

	}

	if( $echo ) {

		echo $inner_content; // WPCS: XSS ok.

	} else {

		return $inner_content;

	}

}

/**
 * Inner Content Close
 */
function xt_inner_content_close() {

	if( is_singular() ) {

		$options = get_post_meta( get_the_ID(), 'xt_options', true );

		$fullwidth = $options ? in_array( 'full-width', $options ) : false;

		$contained = $options ? in_array( 'contained', $options ) : false;

		$inner_content_close = $fullwidth ? false : '</div>';

		if( xt_is_premium() && !$contained ) {

			$xt_settings = get_option( 'xt_settings' );

			$fullwidth_global = isset( $xt_settings['xt_fullwidth_global'] ) ? $xt_settings['xt_fullwidth_global'] : array();

			$fullwidth_global && in_array( get_post_type(), $fullwidth_global ) ? $inner_content_close = false : '';

		}

	} else {

		$inner_content_close = '</div>';

	}

	echo $inner_content_close; // WPCS: XSS ok.

}

/**
 * Title
 */
function xt_title()
{

    $options = get_post_meta(get_the_ID(), 'xt_options', true);

    $removetitle = $options ? in_array('remove-title', $options) : false;

    $title = $removetitle ? false : '<h1 class="entry-title post-header__title" itemprop="headline">' . get_the_title() . '</h1>';

    if ($title) {

        do_action('xt_before_page_title');

        if (is_single()):
        ?>

	<section class="post-header">
<?php if (!has_post_thumbnail()) {$class = "no-thumb";}?>
                <div class="post-header__text <?php echo $class; ?>">
                    <div class="post-header__meta">
                    	    <div class="blog-post__meta">
                    	       <?php the_category(' ');?> </a>
                    	    </div>
                    </div>
                    <?php echo $title; ?>
                    <div class="post-author-tag">
                        <div class="post-author-tag__image-container">
                            <div class="post-author-tag__image">
                                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" target="_blank">
                                    <?php echo do_shortcode('[avatar]'); ?>
	                            </a>
                            </div>
                        </div>
                        <div class="post-author-tag__text">
							<p>Written by <?php echo get_the_author_link(); ?> / <?php echo '<span class="posted-on">' . __('Posted on', 'xt-framework') . '</span> <time class="article-time published" datetime="' . get_the_date('c') . '" itemprop="datePublished">' . get_the_date() . '</time>';?></p>
							<p class="read"><?php echo reading_time();?></p>
						</div>
                    </div>
				</div>

    </section>

	<?php

        else:
            echo $title; // WPCS: XSS ok.
        endif;
        do_action('xt_after_page_title');

    }

}

/**
 * Disable Header
 */
function xt_remove_header() {

	// stop here if we're on archives
	if( !is_singular() ) return;

	// vars
	$options = get_post_meta( get_the_ID(), 'xt_options', true );

	// checking if disable header is checked
	// return false if $options is empty
	$remove_header = $options ? in_array( 'remove-header', $options ) : false;

	// remove header if disable header is checked
	if( $remove_header ) {
		remove_action( 'xt_header', 'xt_do_header' );
	}

}
add_action( 'wp', 'xt_remove_header' );

/**
 * Disable Footer
 */
function xt_remove_footer() {

	// stop here if we're on archives
	if( !is_singular() ) return;

	// vars
	$options = get_post_meta( get_the_ID(), 'xt_options', true );

	// checking if disable footer is checked
	// return false if $options is empty
	$remove_footer = $options ? in_array( 'remove-footer', $options ) : false;

	// remove footer if disable footer is checked
	// also remove custom footer that has been added in the customizer
	if( $remove_footer ) {
		remove_action( 'xt_footer', 'xt_do_footer' );
		remove_action( 'xt_before_footer', 'xt_custom_footer' );
	}

}
add_action( 'wp', 'xt_remove_footer' );

/**
 * ScrollTop
 */
function xt_scrolltop() {

	if ( get_theme_mod( 'layout_scrolltop' ) ) {

		$scrollTop = get_theme_mod( 'scrolltop_value', 400 );
		echo '<a class="scrolltop" href="javascript:void(0)" data-scrolltop-value="'. (int) $scrollTop .'">';
		echo '<span class="screen-reader-text">'. __( 'Scroll to Top', 'xt-framework' ) .'</span>';
		echo '</a>';

	}

}
add_action( 'wp_footer', 'xt_scrolltop' );

/**
 * Archive Class
 *
 * We're adding unique classes to each archive type that exists
 *
 * xt-archive-content
 * xt-{post-type}-archive
 * xt-{archive-type}-content (for post archives)
 *
 * xt-{post-type}-archive-content (for cpt archives)
 * xt-{post-type}-taxonomy-content (for cpt-related taxonomies)
 */
function xt_archive_class() {

	$archive_class = '';

	if( is_date() ) {
		$archive_class = ' xt-archive-content xt-post-archive xt-date-content';
	} elseif( is_category() ) {
		$archive_class = ' xt-archive-content xt-post-archive xt-category-content';
	} elseif( is_tag() ) {
		$archive_class = ' xt-archive-content xt-post-archive xt-tag-content';
	} elseif( is_author() ) {
		$archive_class = ' xt-archive-content xt-post-archive xt-author-content';
	} elseif( is_home() ) {
		$archive_class = ' xt-archive-content xt-post-archive xt-blog-content';
	} elseif( is_search() ) {
		$archive_class = ' xt-archive-content xt-post-archive xt-search-content';
	} elseif( is_post_type_archive() ) {

		$post_type = get_post_type();
		if( !$post_type ) return $archive_class; // stop here if no post has been found

		$archive_class = ' xt-archive-content';
		$archive_class .= ' xt-'. $post_type .'-archive';
		$archive_class .= ' xt-'. $post_type .'-archive-content';

	} elseif( is_tax() ) {

		$post_type = get_post_type();
		if( !$post_type ) return $archive_class; // stop here if no post has been found

		$archive_class = ' xt-archive-content';
		$archive_class .= ' xt-'. $post_type .'-archive';
		$archive_class .= ' xt-'. $post_type .'-archive-content';
		$archive_class .= ' xt-'. $post_type .'-taxonomy-content';

	}

	return apply_filters( 'xt_archive_class', $archive_class );

}

/**
 * Singular Class
 */
function xt_singular_class() {

	if( is_singular( 'post' ) ) {
		$singular_class = ' xt-single-content';
	} elseif( is_attachment() ) {
		$singular_class = ' xt-attachment-content';
	} elseif( is_page() ) {
		$singular_class = ' xt-page-content';
	} elseif( is_404() ) {
		$singular_class = ' xt-404-content';
	} else {
		$post_type = get_post_type();
		$singular_class = ' xt-'. $post_type .'-content';
	}

	return apply_filters( 'xt_singular_class', $singular_class );

}

/**
 * Archive Header
 */
function xt_archive_header() {

	if( is_author() ) { ?>

		<section class="xt-author-box">
			<h1 class="page-title"><span class="vcard"><?php echo get_the_author(); ?></span></h1>
			<p><?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?></p>
			<?php echo get_avatar( get_the_author_meta( 'email' ), 120 ); ?>
		</section>

	<?php } else {

		if( get_the_archive_title() ) {

			do_action( 'xt_before_page_title' );

			the_archive_title( '<h1 class="page-title">', '</h1>' );

			do_action( 'xt_after_page_title' );

		}

		the_archive_description( '<div class="taxonomy-description">', '</div>' );

	}

}

/**
 * Archive Headline
 */
function xt_archive_title( $title ) {

	$archive_headline  = get_theme_mod( 'archive_headline' );

	if( is_category() ) {

		if( $archive_headline == 'hide_prefix' ) {
			$title = single_cat_title( '', false );
		} elseif( $archive_headline == 'hide' ) {
			$title = false;
		}

	} elseif( is_tag() ) {

		if( $archive_headline == 'hide_prefix' ) {
			$title = single_tag_title( '', false );
		} elseif( $archive_headline == 'hide' ) {
			$title = false;
		}

	} elseif( is_date() ) {

		$date = get_the_date( 'F Y' );
		if( is_year() ) $date = get_the_date( 'Y' );
		if( is_day() ) $date = get_the_date( 'F j, Y' );

		if( $archive_headline == 'hide_prefix' ) {
			$title = $date;
		} elseif( $archive_headline == 'hide' ) {
			$title = false;
		}

	} elseif( is_post_type_archive() ) {

		if( $archive_headline == 'hide_prefix' ) {
			$title = post_type_archive_title( '', false );
		} elseif( $archive_headline == 'hide' ) {
			$title = false;
		}

	} elseif( is_tax() ) {

		if( $archive_headline == 'hide_prefix' ) {
			$title = single_term_title( '', false );
		} elseif( $archive_headline == 'hide' ) {
			$title = false;
		}

	}

	return $title;

}
add_filter( 'get_the_archive_title', 'xt_archive_title', 10 );

/**
 * Responsive Breakpoints
 *
 * Simple check if Responsive Breakpoints are set
 */
if( !function_exists( 'xt_has_responsive_breakpoints' ) ) {

	function xt_has_responsive_breakpoints() {

		// there can't be Responsive Breakpoints if there's no Premium Add-On
		if( !xt_is_premium() ) return false;

		// vars
		$xt_settings = get_option( 'xt_settings' );

		// check if custom breakpoints are set, otherwise return false
		if ( !empty( $xt_settings['xt_breakpoint_medium'] ) || !empty( $xt_settings['xt_breakpoint_desktop'] ) || !empty( $xt_settings['xt_breakpoint_mobile'] ) ) {
			return true;
		} else {
			return false;
		}

	}

}

/**
 * Right Sidebar
 */
function xt_do_sidebar_right() {

	if( xt_sidebar_layout() == 'right' ) get_sidebar();

}
add_action( 'xt_sidebar_right', 'xt_do_sidebar_right' );

/**
 * Left Sidebar
 */
function xt_do_sidebar_left() {

	if( xt_sidebar_layout() == 'left' ) get_sidebar();

}
add_action( 'xt_sidebar_left', 'xt_do_sidebar_left' );

/**
 * Sidebar Layout
 */
function xt_sidebar_layout() {

	// Default Sidebar Position is 'right'
	$sidebar = get_theme_mod( 'sidebar_position', 'right' );

	$archive_sidebar_position = get_theme_mod( 'archive_sidebar_layout', 'global' );
	$sidebar = $archive_sidebar_position !== 'global' ? $archive_sidebar_position : $sidebar;

	if( is_singular() ) {

		$id                             = get_the_ID();
		$single_sidebar_position        = get_post_meta( $id, 'xt_sidebar_position', true );
		$single_sidebar_position_global = get_theme_mod( 'single_sidebar_layout', 'global' );

		$sidebar = $single_sidebar_position_global !== 'global' ? $single_sidebar_position_global : $sidebar;
		$sidebar = $single_sidebar_position && $single_sidebar_position !== 'global' ? $single_sidebar_position : $sidebar;

	}

	return apply_filters( 'xt_sidebar_layout', $sidebar );

}

/*
 * Article Meta
 *
 * Construct Sortable Article Meta
 */
function xt_article_meta() {

	$blog_meta = get_theme_mod( 'blog_sortable_meta', array( 'author', 'date' ) );

	if ( is_array( $blog_meta ) && !empty( $blog_meta ) ) {

		do_action( 'xt_before_article_meta' );
		echo '<p class="article-meta">';
		do_action( 'xt_article_meta_open' );

		foreach ( $blog_meta as $value ) {

			switch ( $value ) {
				case 'author':
					do_action( 'xt_before_author_meta' );
					xt_author_meta();
					do_action( 'xt_after_author_meta' );
					break;
				case 'date':
					do_action( 'xt_before_date_meta' );
					xt_date_meta();
					do_action( 'xt_after_date_meta' );
					break;
				case 'comments':
					do_action( 'xt_before_comments_meta' );
					xt_comments_meta();
					do_action( 'xt_after_comments_meta' );
					break;
				default:
					break;
			}
		}

		do_action( 'xt_article_meta_close' );
		echo '</p>';
		do_action( 'xt_after_article_meta' );

	}

}

/**
 * Blog Meta – Author
 */
function xt_author_meta() {

	$rtl    = is_rtl();
	$avatar = get_theme_mod( 'blog_author_avatar' );

	if( !$rtl && $avatar ) {
		echo get_avatar( get_the_author_meta( 'ID' ), 128 );
	}

	echo sprintf( '<span class="article-author author vcard" itemscope="itemscope" itemprop="author" itemtype="https://schema.org/Person"><a class="url fn" href="%1$s" title="%2$s" rel="author" itemprop="url"><span itemprop="name">%3$s</span></a></span>', // WPCS: XSS ok, sanitization ok.
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'xt-framework' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);

	if( $rtl && $avatar ) {
		echo get_avatar( get_the_author_meta( 'ID' ), 128 );
	}

	echo '<span class="article-meta-separator">'. apply_filters( 'xt_article_meta_separator', ' | ' ) .'</span>';

}

/*
 * Blog Meta – Date
 */
function xt_date_meta() {

	echo '<span class="posted-on">'. __( 'Posted on', 'xt-framework' ) .'</span> <time class="article-time published" datetime="'. get_the_date( 'c' ) .'" itemprop="datePublished">'. get_the_date() .'</time>'; // WPCS: XSS ok.
	echo '<span class="article-meta-separator">'. apply_filters( 'xt_article_meta_separator', ' | ' ) .'</span>';

}

/**
 * Blog Meta – Comments
 */
function xt_comments_meta() {

	echo '<span class="comments-count">';

	comments_number( __( '<span>No</span> Comments', 'xt-framework' ), __( '<span>1</span> Comment', 'xt-framework' ), __( '<span>%</span> Comments', 'xt-framework' ) );

	echo '</span>';

	echo '<span class="article-meta-separator">'. apply_filters( 'xt_article_meta_separator', ' | ' ) .'</span>';

}

/**
 * Blog Layout
 */
function xt_blog_layout() {

	$template_parts_header = get_theme_mod( 'archive_sortable_header', array( 'title', 'meta', 'featured' ) );
	$template_parts_footer = get_theme_mod( 'archive_sortable_footer', array( 'readmore', 'categories' ) );
	$blog_layout           = get_theme_mod( 'archive_layout', 'default' );
	$style                 = get_theme_mod( 'archive_post_style', 'plain' );
	$stretched             = get_theme_mod( 'archive_boxed_image_streched', false );

	if( $blog_layout !== 'beside' && $style == 'boxed' && $stretched ) {
		$style             .= ' stretched';
	}

	return apply_filters( 'xt_blog_layout', array( 'blog_layout' => $blog_layout, 'template_parts_header' => $template_parts_header, 'template_parts_footer' => $template_parts_footer, 'style' => $style ) );

}

/**
 * Navigation
 *
 * Set wp_nav_menu for main navigations
 */
function xt_nav_menu() {

	$custom_menu = get_theme_mod( 'menu_custom' );

	if( $custom_menu ) {

		echo do_shortcode( $custom_menu );

	} elseif( in_array( get_theme_mod( 'menu_position' ), array( 'menu-off-canvas', 'menu-off-canvas-left' ) ) ) {

		wp_nav_menu( array(
			'theme_location'	=> 'main_menu',
			'container'			=> false,
			'menu_class'		=> 'xt-menu',
			'depth'				=> 3,
			'fallback_cb'		=> 'xt_main_menu_fallback'
		));

	} elseif ( get_theme_mod( 'menu_position' ) == 'menu-full-screen' ) {

		wp_nav_menu( array(
			'theme_location'	=> 'main_menu',
			'container'			=> false,
			'menu_class'		=> 'xt-menu',
			'depth'				=> 1,
			'fallback_cb'		=> 'xt_main_menu_fallback'
		));

	} else {

		wp_nav_menu( array(
			'theme_location'	=> 'main_menu',
			'container'			=> false,
			'menu_class'		=> 'xt-menu xt-sub-menu' . xt_sub_menu_alignment() . xt_sub_menu_animation() . xt_menu_hover_effect(),
			'depth'				=> 4,
			'fallback_cb'		=> 'xt_main_menu_fallback'
		));

	}
}
add_action( 'xt_main_menu', 'xt_nav_menu' );

/**
 * Menu
 *
 * returns the menu selected under Header -> Navigation in the WordPress customizer
 */
function xt_menu() {
	return get_theme_mod( 'menu_position', 'menu-right' );
}

/**
 * Mobile Menu
 *
 * returns the menu selected under Header -> Mobile Navigation in the WordPress customizer
 */
function xt_mobile_menu() {
	return get_theme_mod( 'mobile_menu_options', 'menu-mobile-hamburger' );
}

/**
 * Is Off Canvas Menu
 *
 * A simple check that returns true if an Off-Canvas menu is being used
 */
function xt_is_off_canvas_menu() {

	if( in_array( get_theme_mod( 'menu_position' ), array( 'menu-off-canvas', 'menu-off-canvas-left', 'menu-full-screen' ) ) ) {
		return true;
	} else {
		return false;
	}

}

/**
 * Add Sub Menu Indicators to Mobile Menu's
 */
function xt_mobile_sub_menu_indicators( $item_output, $items, $depth, $args ) {

	if ( $args->theme_location == 'mobile_menu' || ( in_array( get_theme_mod( 'menu_position' ), array( 'menu-off-canvas', 'menu-off-canvas-left' ) ) && $args->theme_location == 'main_menu' ) ) {

		if ( isset( $items->classes ) && in_array( 'menu-item-has-children', $items->classes ) ) {
			$item_output .= '<button class="xt-submenu-toggle" aria-expanded="false"><i class="xtf xtf-arrow-down" aria-hidden="true"></i><span class="screen-reader-text">'. __( 'Menu Toggle', 'xt-framework' ) .'</span></button>';
		}
	}

	return $item_output;

}
add_filter( 'walker_nav_menu_start_el', 'xt_mobile_sub_menu_indicators', 10, 4 );

/**
 * Sub Menu Alignment
 */
function xt_sub_menu_alignment() {

	$sub_menu_alignment = get_theme_mod( 'sub_menu_alignment', 'left' );
	$alignment = ' xt-sub-menu-align-' . $sub_menu_alignment;

	return $alignment;

}

/**
 * Sub Menu Animation
 */
function xt_sub_menu_animation() {

	$sub_menu_animation = get_theme_mod( 'sub_menu_animation', 'fade' );
	$sub_menu_animation = ' xt-sub-menu-animation-' . $sub_menu_animation;

	return $sub_menu_animation;

}

/**
 * Menu Alignment
 *
 * return the stacked advanced menu alignment
 */
function xt_menu_alignment() {

	$alignment = get_theme_mod( 'menu_alignment', 'left' );
	$alignment = ' menu-align-' . $alignment;

	return $alignment;

}

/**
 * Menu Effect
 */
function xt_menu_hover_effect() {

	$menu_effect           = get_theme_mod( 'menu_effect', 'none' );
	$menu_effect_animation = get_theme_mod( 'menu_effect_animation', 'fade' );
	$menu_effect_alignment = get_theme_mod( 'menu_effect_alignment', 'center' );

	$hover_effect = ' xt-menu-effect-' . $menu_effect;
	$hover_effect .= ' xt-menu-animation-' . $menu_effect_animation;
	$hover_effect .= ' xt-menu-align-' . $menu_effect_alignment;

	return $hover_effect;

}

/**
 * Navigation Attributes
 *
 * Currently only being used to add the sub menu animation duration
 */
function xt_navigation_attributes() {

	// vars
	$submenu_animation_duration = get_theme_mod( 'sub_menu_animation_duration' );

	// Construct Navigation Attributes
	$navigation_attributes = $submenu_animation_duration ? 'data-sub-menu-animation-duration="' . esc_attr( $submenu_animation_duration ) . '"' : 'data-sub-menu-animation-duration="250"';

	echo $navigation_attributes; // WPCS: XSS ok.

}

/**
 * Responsive embed/oembed
 */
function xt_responsive_embed( $html, $url, $attr ) {

	$providers = array( 'vimeo.com', 'youtube.com', 'youtu.be', 'wistia.com', 'wistia.net' );

	if ( xt_strposa( $url, $providers ) ) {
			$html = '<div class="xt-video">' . $html . '</div>';
	}

	return $html;

}
add_filter( 'embed_oembed_html', 'xt_responsive_embed', 10, 3 );




/**
 * Sticky Navigation
 */
function xt_sticky_navigation() {

	// vars
	$menu_sticky					= get_theme_mod( 'menu_sticky' );
	$menu_active_delay				= get_theme_mod( 'menu_active_delay' );
	$menu_active_animation			= get_theme_mod( 'menu_active_animation' );
	$menu_active_animation_duration	= get_theme_mod( 'menu_active_animation_duration' );

	if ( $menu_sticky ) {

		$sticky_navigation = 'data-sticky="true"';
		$sticky_navigation .= $menu_active_delay ? ' data-sticky-delay="' . esc_attr( $menu_active_delay ) . '"' : ' data-sticky-delay="100px"';
		$sticky_navigation .= $menu_active_animation ? ' data-sticky-animation="' . esc_attr( $menu_active_animation ) . '"' : false;
		$sticky_navigation .= $menu_active_animation_duration ? ' data-sticky-animation-duration="' . esc_attr( $menu_active_animation_duration ) . '"' : ' data-sticky-animation-duration="200"';

		echo $sticky_navigation;

	}

}


/**
 * Transparent Header
 *
 * Add a class to .xt-navigation if Transparent Header body class exists
 */
function xt_transparent_header() {

	$classes = get_body_class();

	if ( in_array( 'xt-transparent-header', $classes ) ) {

		echo " xt-navigation-transparent";

	}

}

/**
 * Transparent Header Body Class
 */
function xt_transparent_header_body_class_2083582( $classes ) {

	// don't take it further if we're on archives
	if( is_singular() ) {

		$options = get_post_meta( get_the_ID(), 'xt_premium_options', true );

		// check if Transparent Header is ticked
		// return false if $options is empty
		$transparent_header = $options ? in_array( 'transparent-header', $options ) : false;

		if( $transparent_header ) {

			$classes[] = 'xt-transparent-header';

		} else {

			// check for Global Settings
			$xt_settings = get_option( 'xt_settings' );

			// get the array of post types that are set to Transparent Header under Appearance -> Theme Settings -> Global Templat Settings
			$transparent_header_global = isset( $xt_settings['xt_transparent_header_global'] ) ? $xt_settings['xt_transparent_header_global'] : array();

			if( in_array( get_post_type(), $transparent_header_global ) ) {
				$classes[] = 'xt-transparent-header';
			}

		}

	// Archives
	} else {

		$xt_settings = get_option( 'xt_settings' );

		// get the array of post types that are set to Transparent Header under Appearance -> Theme Settings -> Global Templat Settings
		$transparent_headers_global = isset( $xt_settings['xt_transparent_header_global'] ) ? $xt_settings['xt_transparent_header_global'] : array();

		// remove public post types from array as we've already taken care of them above
		$transparent_headers_global = array_diff( $transparent_headers_global, get_post_types( array( 'public' => true ) ) );

		if ( !empty( $transparent_headers_global ) ) {

			foreach ( $transparent_headers_global as $transparent_header_global ) {

				switch ( $transparent_header_global ) {

					case '404':
						if( is_404() ) $classes[] = 'xt-transparent-header';
						break;
					case 'front_page':
						if( is_home() ) $classes[] = 'xt-transparent-header';
						break;
					case 'search':
						if( is_search() ) $classes[] = 'xt-transparent-header';
						break;
					case 'archives':
						if( is_archive() ) $classes[] = 'xt-transparent-header';
						break;
					case 'post_archives':
						if( is_date() || is_category() || is_author() || is_tag() ) $classes[] = 'xt-transparent-header';
						break;
					default:
						// Post Type Archives
						// cut given value to get cpt (example: turns download_archive into download to use it in is_post_type_archive())
						$transparent_header_global = substr( $transparent_header_global, 0, strpos( $transparent_header_global, '_' ) );

						if( is_post_type_archive( $transparent_header_global ) ) $classes[] = 'xt-transparent-header';

						// apply to related taxonomies
						$taxonomies = get_object_taxonomies( $transparent_header_global, 'names' );

						if( !empty( $taxonomies ) ) {

							foreach( $taxonomies as $taxonomy ) {
								if( is_tax( $taxonomy ) ) $classes[] = 'xt-transparent-header';
							}

						}
						break;

				}

			}

		}

	}

	return $classes;

}
add_filter( 'body_class', 'xt_transparent_header_body_class_2083582' );

function cc_mime_types($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
   }
   add_filter('upload_mimes', 'cc_mime_types');


/**
 * Desktop Breakpoint
 */
function xt_breakpoint_desktop() {

	$xt_settings = get_option( 'xt_settings' );

	if ( !empty( $xt_settings['xt_breakpoint_desktop'] ) ) {
		$desktop_breakpoint = (int) $xt_settings['xt_breakpoint_desktop'];
	} else {
		$desktop_breakpoint = 1024;
	}

	return $desktop_breakpoint;

}

/**
 * Medium Breakpoint
 */
function xt_breakpoint_medium() {

	$xt_settings = get_option( 'xt_settings' );

	if ( !empty( $xt_settings['xt_breakpoint_medium'] ) ) {
		$medium_breakpoint = (int) $xt_settings['xt_breakpoint_medium'];
	} else {
		$medium_breakpoint = 768;
	}

	return $medium_breakpoint;

}

/**
 * Mobile Breakpoint
 */
function xt_breakpoint_mobile() {

	$xt_settings = get_option( 'xt_settings' );

	if ( !empty( $xt_settings['xt_breakpoint_mobile'] ) ) {
		$mobile_breakpoint = (int) $xt_settings['xt_breakpoint_mobile'];
	} else {
		$mobile_breakpoint = 480;
	}

	return $mobile_breakpoint;

}


function xt_premium_body_classes( $classes ) {

	$push_menu		= get_theme_mod( 'menu_off_canvas_push' );
	$menu_position	= get_theme_mod( 'menu_position' );

	if( $push_menu && $menu_position == 'menu-off-canvas' ) {
		$classes[] = 'xt-push-menu-right';
	}

	if( $push_menu && $menu_position == 'menu-off-canvas-left' ) {
		$classes[] = 'xt-push-menu-left';
	}

	if( xt_has_responsive_breakpoints() ) {

		$classes[] = 'xt-responsive-breakpoints';

		$classes[] = 'xt-mobile-breakpoint-' . xt_breakpoint_mobile();
		$classes[] = 'xt-medium-breakpoint-' . xt_breakpoint_medium();
		$classes[] = 'xt-desktop-breakpoint-' . xt_breakpoint_desktop();

	}

	return $classes;

}
add_filter( 'body_class', 'xt_premium_body_classes' );


/**
 * Social Icon Shortcode
 */
function xt_social() {

	$active_networks	= get_theme_mod( 'social_sortable', array() );
	$social_shape		= get_theme_mod( 'social_shapes' );
	$social_style		= get_theme_mod( 'social_styles' );
	$social_size		= get_theme_mod( 'social_sizes' );

	ob_start();

	if ( ! empty( $active_networks ) && is_array( $active_networks ) ) : ?>
	<div class="xt-social-icons<?php echo esc_attr( $social_shape . $social_style . $social_size ); ?>">
	
		<?php foreach ( $active_networks as $social_network_label ) : ?>
			<a class="xt-social-icon xt-social-<?php echo esc_attr( $social_network_label ); ?>" target="_blank" href="<?php echo esc_url( get_theme_mod( $social_network_label . '_link', '' ) ); ?>">
				<i class="xtf xtf-<?php echo esc_attr( $social_network_label ); ?>" aria-hidden="true"></i>
			</a>
		<?php endforeach; ?>
	</div>
	<?php endif;

	return ob_get_clean();

}
add_shortcode( 'social', 'xt_social' );


// Limit excerpts

function get_excerpt($limit){
$excerpt = get_the_content();
$excerpt = preg_replace(" ([.*?])",'',$excerpt);
$excerpt = strip_shortcodes($excerpt);
$excerpt = strip_tags($excerpt);
$excerpt = substr($excerpt, 0, $limit);
$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
$excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
return $excerpt;
}

// Add author contact methods


function change_contact_info($contactmethods) {
    unset($contactmethods['aim']);
    unset($contactmethods['yim']);
    unset($contactmethods['jabber']);
    $contactmethods['website_title'] = 'Website Title';
    $contactmethods['twitter'] = 'Twitter';
    $contactmethods['facebook'] = 'Facebook';
    $contactmethods['linkedin'] = 'Linked In';
    return $contactmethods;
}

add_filter('user_contactmethods','change_contact_info',10,1);

//add_action( 'author_box', 'single_suthor_box');
function single_suthor_box(){

$author             = get_the_author();
$author_description = get_the_author_meta( 'description' );
$author_url         = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
$author_avatar      = get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'wpex_author_bio_avatar_size', 100 ) );
$facebook = get_the_author_meta('facebook');
$twitter = get_the_author_meta('twitter');
$linkedin = get_the_author_meta('linkedin');
$authsocial ="";
// Only display if author has a description

if($facebook ){
	$authsocial .= "<a class=\" user-social author-fb\" href=\"". $facebook . "\" target=\"_blank\" rel=\"nofollow\" title=\"" . get_the_author_meta('display_name') . " on Facebook\"><i class=\"xtf xtf-facebook\"></i></a>";
}
if($twitter){
	$authsocial .= "<a class=\"user-social author-twitter\" href=\"". $twitter . "\" target=\"_blank\" rel=\"nofollow\" title=\"" . get_the_author_meta('display_name') . " on Twitter\"><i class=\"xtf xtf-twitter\"></i></a>";
}
if($linkedin){
	$authsocial .= "<a class=\"user-social author-linkedin\"  href=\"" . $linkedin . "\" target=\"_blank\" rel=\"nofollow\" title=\"" . get_the_author_meta('display_name') . " LinkedIn\"><i class=\"xtf xtf-linkedin\"></i></a>";
}
?>
    <div class="author-info clr">
        <div class="author-info-inner clr">
            <?php if ( $author_avatar ) { ?>
                <div class="author-avatar clr">
                    
                        <?php echo $author_avatar; ?>
                    
                </div><!-- .author-avatar -->
            <?php } ?>
            <div class="author-description">
			<h4 class="heading"><span><?php echo  $author; ?></span></h4>
                <p><?php echo wp_kses_post( $author_description ); ?></p>
                <div class="author-links">
				<?php echo $authsocial; ?>
            </div><!-- .author-description -->
        </div><!-- .author-info-inner -->
    </div><!-- .author-info -->

<?php
}


// Optimizations


// remove unnecessary header info
add_action( 'init', 'remove_header_info' );
function remove_header_info() {
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'start_post_rel_link' );
    remove_action( 'wp_head', 'index_rel_link' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link' );         // for WordPress < 3.0
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' ); // for WordPress >= 3.0
}

// remove extra CSS that 'Recent Comments' widget injects
add_action( 'widgets_init', 'remove_recent_comments_style' );
function remove_recent_comments_style() {
    global $wp_widget_factory;
    remove_action( 'wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ) );
}

// remove dashicons in frontend to non-admin
    function wpdocs_dequeue_dashicon() {
        if (current_user_can( 'update_core' )) {
            return;
        }
        wp_deregister_style('dashicons');
    }
	add_action( 'wp_enqueue_scripts', 'wpdocs_dequeue_dashicon' );

// Disable Gutenberg editor.
add_filter('use_block_editor_for_post_type', '__return_false', 10);
// Don't load Gutenberg-related stylesheets.
add_action( 'wp_enqueue_scripts', 'remove_block_css', 100 );
function remove_block_css() {
    wp_dequeue_style( 'wp-block-library' ); // Wordpress core
    wp_dequeue_style( 'wp-block-library-theme' ); // Wordpress core
    wp_dequeue_style( 'wc-block-style' ); // WooCommerce
    wp_dequeue_style( 'storefront-gutenberg-blocks' ); // Storefront theme
}

//Optimizations
function wpb_remove_version() {
	return '';
	}
	add_filter('the_generator', 'wpb_remove_version');

	
function xt_sharing_buttons()
{
    global $post;

    // Get current page URL
    $xtURL = urlencode(get_permalink());

    // Get current page title
    $xtTitle = htmlspecialchars(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
    // $xtTitle = str_replace( ' ', '%20', get_the_title());

    // Get Post Thumbnail for pinterest
    $xtThumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');

    // Construct sharing URL without using any script
    $twitterURL = 'https://twitter.com/intent/tweet?text=' . $xtTitle . '&amp;url=' . $xtURL . '&ampvia=' . get_bloginfo("name");
    $facebookURL = 'https://www.facebook.com/sharer/sharer.php?u=' . $xtURL;
    $linkedInURL = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $xtURL . '&amp;title=' . $xtTitle;

    // Add sharing button at the end of page/page content
	$content .='<h6>Share this</h6>';
    $content .= '<div class="xt-social">';
    $content .= '<a class="xt-link xt-twitter" href="' . $twitterURL . '" target="_blank"><i class="xtf xtf-twitter"></i></a>';
    $content .= '<a class="xt-link xt-facebook" href="' . $facebookURL . '" target="_blank"><i class="xtf xtf-facebook"></i></a>';
    $content .= '<a class="xt-link xt-linkedin" href="' . $linkedInURL . '" target="_blank"><i class="xtf xtf-linkedin"></i></a>';
    $content .= '<a href="mailto:type email address here?subject=I wanted to share this post with you from' . get_bloginfo("name") . '&body=' . get_the_title() . '&#32;&#32;' . get_the_permalink() . '"><i class="xtf xtf-envelope"></i></a>';
    $content .= '</div>';

    return $content;
}

// Reading time
function reading_time()
{
    $content = get_post_field('post_content', $post->ID);
    $word_count = str_word_count(strip_tags($content));
    $readingtime = ceil($word_count / 200);
    //$manual_reading = get_post_meta(get_the_ID(), 'reading_time_value', true);

    if ($readingtime == 1) {
        $timer = " min read";
    } else {
        $timer = " min read";
    }
    if ($manual_reading) {
        $totalreadingtime = $manual_reading . ' MIN READ';
    } else {
        $totalreadingtime = $readingtime . $timer;
    }
    return $totalreadingtime;
}
add_action('xt_after_main_navigation', 'blog_progess');
function blog_progess(){
	if (is_single()) {
    ?>
		<progress class="xt-progress" value="0">
		  <div class="progress-container">
			<span class="progress-bar"></span>
		  </div>
	  </progress>
		<?php
}
}

// Register Custom Post Type Testimonial
function create_testimonial_cpt()
{

    $labels = array(
        'name' => _x('Testimonials', 'Post Type General Name', 'xstream'),
        'singular_name' => _x('Testimonial', 'Post Type Singular Name', 'xstream'),
        'menu_name' => _x('Testimonials', 'Admin Menu text', 'xstream'),
        'name_admin_bar' => _x('Testimonial', 'Add New on Toolbar', 'xstream'),
        'archives' => __('Testimonial Archives', 'xstream'),
        'attributes' => __('Testimonial Attributes', 'xstream'),
        'parent_item_colon' => __('Parent Testimonial:', 'xstream'),
        'all_items' => __('All Testimonials', 'xstream'),
        'add_new_item' => __('Add New Testimonial', 'xstream'),
        'add_new' => __('Add New', 'xstream'),
        'new_item' => __('New Testimonial', 'xstream'),
        'edit_item' => __('Edit Testimonial', 'xstream'),
        'update_item' => __('Update Testimonial', 'xstream'),
        'view_item' => __('View Testimonial', 'xstream'),
        'view_items' => __('View Testimonials', 'xstream'),
        'search_items' => __('Search Testimonial', 'xstream'),
        'not_found' => __('Not found', 'xstream'),
        'not_found_in_trash' => __('Not found in Trash', 'xstream'),
        'featured_image' => __('Featured Image', 'xstream'),
        'set_featured_image' => __('Set featured image', 'xstream'),
        'remove_featured_image' => __('Remove featured image', 'xstream'),
        'use_featured_image' => __('Use as featured image', 'xstream'),
        'insert_into_item' => __('Insert into Testimonial', 'xstream'),
        'uploaded_to_this_item' => __('Uploaded to this Testimonial', 'xstream'),
        'items_list' => __('Testimonials list', 'xstream'),
        'items_list_navigation' => __('Testimonials list navigation', 'xstream'),
        'filter_items_list' => __('Filter Testimonials list', 'xstream'),
    );
    $args = array(
        'label' => __('Testimonial', 'xstream'),
        'description' => __('', 'xstream'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-thumbs-up',
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies' => array('testimonial_category'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'hierarchical' => true,
        'exclude_from_search' => false,
        'show_in_rest' => true,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    );
    register_post_type('testimonials', $args);

}
add_action('init', 'create_testimonial_cpt', 0);

// Register Taxonomy Testimonial Category
function create_testimonialcategory_tax()
{

    $labels = array(
        'name' => _x('Testimonial Categories', 'taxonomy general name', 'xstream'),
        'singular_name' => _x('Testimonial Category', 'taxonomy singular name', 'xstream'),
        'search_items' => __('Search Testimonial Categories', 'xstream'),
        'all_items' => __('All Testimonial Categories', 'xstream'),
        'parent_item' => __('Parent Testimonial Category', 'xstream'),
        'parent_item_colon' => __('Parent Testimonial Category:', 'xstream'),
        'edit_item' => __('Edit Testimonial Category', 'xstream'),
        'update_item' => __('Update Testimonial Category', 'xstream'),
        'add_new_item' => __('Add New Testimonial Category', 'xstream'),
        'new_item_name' => __('New Testimonial Category Name', 'xstream'),
        'menu_name' => __('Testimonial Category', 'xstream'),
    );
    $args = array(
        'labels' => $labels,
        'description' => __('', 'xstream'),
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'show_admin_column' => false,
        'show_in_rest' => true,
    );
    register_taxonomy('testimonial_category', array('testimonials'), $args);

}
add_action('init', 'create_testimonialcategory_tax');

class GFLimitCheckboxes
{

    private $form_id;
    private $field_limits;
    private $output_script;

    public function __construct($form_id, $field_limits)
    {

        $this->form_id = $form_id;
        $this->field_limits = $this->set_field_limits($field_limits);

        add_filter("gform_pre_render_$form_id", array(&$this, 'pre_render'));
        add_filter("gform_validation_$form_id", array(&$this, 'validate'));

    }

    public function pre_render($form)
    {

        $script = '';
        $output_script = false;

        foreach ($form['fields'] as $field) {

            $field_id = $field['id'];
            $field_limits = $this->get_field_limits($field['id']);

            if (!$field_limits // if field limits not provided for this field
                 || RGFormsModel::get_input_type($field) != 'checkbox' // or if this field is not a checkbox
                 || !isset($field_limits['max']) // or if 'max' is not set for this field
            ) {
                continue;
            }

            $output_script = true;
            $max = $field_limits['max'];
            $selectors = array();

            foreach ($field_limits['field'] as $checkbox_field) {
                $selectors[] = "#field_{$form['id']}_{$checkbox_field} .gfield_checkbox input:checkbox";
            }

            $script .= "jQuery(\"" . implode(', ', $selectors) . "\").checkboxLimit({$max});";

        }

        GFFormDisplay::add_init_script($form['id'], 'limit_checkboxes', GFFormDisplay::ON_PAGE_RENDER, $script);

        if ($output_script):
        ?>

            <script type="text/javascript">
            jQuery(document).ready(function($) {
                $.fn.checkboxLimit = function(n) {

                    var checkboxes = this;

                    this.toggleDisable = function() {

                        // if we have reached or exceeded the limit, disable all other checkboxes
                        if(this.filter(':checked').length >= n) {
                            var unchecked = this.not(':checked');
                            unchecked.prop('disabled', true);
                        }
                        // if we are below the limit, make sure all checkboxes are available
                        else {
                            this.prop('disabled', false);
                        }

                    }

                    // when form is rendered, toggle disable
                    checkboxes.bind('gform_post_render', checkboxes.toggleDisable());

                    // when checkbox is clicked, toggle disable
                    checkboxes.click(function(event) {

                        checkboxes.toggleDisable();

                        // if we are equal to or below the limit, the field should be checked
                        return checkboxes.filter(':checked').length <= n;
                    });

                }
            });
            </script>

            <?php
endif;

        return $form;
    }

    public function validate($validation_result)
    {

        $form = $validation_result['form'];
        $checkbox_counts = array();

        // loop through and get counts on all checkbox fields (just to keep things simple)
        foreach ($form['fields'] as $field) {

            if (RGFormsModel::get_input_type($field) != 'checkbox') {
                continue;
            }

            $field_id = $field['id'];
            $count = 0;

            foreach ($_POST as $key => $value) {
                if (strpos($key, "input_{$field['id']}_") !== false) {
                    $count++;
                }

            }

            $checkbox_counts[$field_id] = $count;

        }

        // loop through again and actually validate
        foreach ($form['fields'] as &$field) {

            if (!$this->should_field_be_validated($form, $field)) {
                continue;
            }

            $field_id = $field['id'];
            $field_limits = $this->get_field_limits($field_id);

            $min = isset($field_limits['min']) ? $field_limits['min'] : false;
            $max = isset($field_limits['max']) ? $field_limits['max'] : false;

            $count = 0;
            foreach ($field_limits['field'] as $checkbox_field) {
                $count += rgar($checkbox_counts, $checkbox_field);
            }

            if ($count < $min) {
                $field['failed_validation'] = true;
                $field['validation_message'] = sprintf(_n('You must select at least %s item.', 'You must select at least %s items.', $min), $min);
                $validation_result['is_valid'] = false;
            } else if ($count > $max) {
                $field['failed_validation'] = true;
                $field['validation_message'] = sprintf(_n('You may only select %s item.', 'You may only select %s items.', $max), $max);
                $validation_result['is_valid'] = false;
            }

        }

        $validation_result['form'] = $form;

        return $validation_result;
    }

    public function should_field_be_validated($form, $field)
    {

        if ($field['pageNumber'] != GFFormDisplay::get_source_page($form['id'])) {
            return false;
        }

        // if no limits provided for this field
        if (!$this->get_field_limits($field['id'])) {
            return false;
        }

        // or if this field is not a checkbox
        if (RGFormsModel::get_input_type($field) != 'checkbox') {
            return false;
        }

        // or if this field is hidden
        if (RGFormsModel::is_field_hidden($form, $field, array())) {
            return false;
        }

        return true;
    }

    public function get_field_limits($field_id)
    {

        foreach ($this->field_limits as $key => $options) {
            if (in_array($field_id, $options['field'])) {
                return $options;
            }

        }

        return false;
    }

    public function set_field_limits($field_limits)
    {

        foreach ($field_limits as $key => &$options) {

            if (isset($options['field'])) {
                $ids = is_array($options['field']) ? $options['field'] : array($options['field']);
            } else {
                $ids = array($key);
            }

            $options['field'] = $ids;

        }

        return $field_limits;
    }

}

new GFLimitCheckboxes(3, array(
    10 => array(
        'min' => 1,
        'max' => 1,
    )
));

add_filter('body_class', 'my_body_classes');
function my_body_classes($classes)
{
if(is_search() ){
$classes[] = 'archive';

}
    

    return $classes;

}
