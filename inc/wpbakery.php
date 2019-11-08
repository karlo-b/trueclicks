<?php
/**
 * wpbakery
 *
 * All files are being called from here.
 *
 * @package XT Framework
 */

// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

add_action('vc_after_init', 'vc_after_init_actions');

function vc_after_init_actions()
{

    /*** Row ***/
    vc_remove_param('vc_row', 'rtl_reverse');
    vc_remove_param('vc_row_inner', 'rtl_reverse');

    vc_add_param('vc_row', array(
        'type' => 'dropdown',
        'heading' => esc_html__('Row Padding', 'xt-framework'),
        'param_name' => 'row_padding',
        'weight' => 10,
        'std' => '',
        'value' => array(
            'initial' => 'xt-padding-initial',
            'none' => 'xt-padding-none',
            'Small' => 'xt-padding-small',
            'Medium' => 'xt-padding-medium',
            'Large' => 'xt-padding-large',
            'Extra Large' => 'xt-padding-xlarge',
        ),
    ));
    vc_add_param('vc_row', array(
        'type' => 'checkbox',
        'heading' => esc_html__('Reverse Row Columns on Desktop Size', 'xt-framework'),
        'param_name' => 'row_reverse',
        'weight' => 10,
        'std' => '',
        'value' => array(
            'yes' => 'xt-reverse-row',
        ),
    ));

}

// Create Shortcode xt_modal_popup
// Use the shortcode: [xt_modal_popup xt_modal_popup_trigger="" xt_modal_popup_content=""]
function create_xtmodalpopup_shortcode($atts, $content)
{
    // Attributes
    $atts = shortcode_atts(
        array(
            'xt_modal_popup_trigger' => '',
            'xt_modal_popup_content' => '',
        ),
        $atts,
        'xt_modal_popup'
    );
    // Attributes in var
    $xt_modal_popup_trigger = $atts['xt_modal_popup_trigger'];
    $image_src = wp_get_attachment_url($xt_modal_popup_trigger);

    ob_start();
    // Output Code
    ?>
	<div class="xt-modal-wrap">
		<img class="trigger" src=" <?php echo $image_src; ?> " />
		<div class="xt-modal">
			<div class="xt-modal-content">
				<span class="close-button">×</span>
				<?php echo $content; ?>
			</div>
		</div>
	</div>
	<?php
$output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
}
add_shortcode('xt_modal_popup', 'create_xtmodalpopup_shortcode');

// Create XT Modal Popup element for Visual Composer
add_action('vc_before_init', 'xtmodalpopup_integrateWithVC');
function xtmodalpopup_integrateWithVC()
{
    vc_map(array(
        'name' => __('XT Modal Popup', 'xstream'),
        'base' => 'xt_modal_popup',
        'class' => 'xt_modal_popup',
        'show_settings_on_create' => false,
        'category' => __('XT', 'xstream'),
        'icon' => get_template_directory_uri() . '/img/icon.png',
        'admin_label' => false,
        'params' => array(
            array(
                'type' => 'attach_image',
                'holder' => 'div',
                'class' => '',
                'admin_label' => false,
                'heading' => __('Trigger Image', 'xstream'),
                'param_name' => 'xt_modal_popup_trigger',
            ),
            array(
                'type' => 'textarea_html',
                'holder' => 'div',
                'class' => '',
                'admin_label' => false,
                'heading' => __('Modal Content', 'xstream'),
                'param_name' => 'content',
            ),
        ),
    ));
}

// Create Shortcode image_content_block
// Use the shortcode: [image_content_block image_content_block_image="" content="" image_content_block_button_text="" image_content_block_button_link=""]
function create_imagecontentblock_shortcode($atts, $content)
{
    // Attributes
    $atts = shortcode_atts(
        array(
            'image_content_block_image' => '',
            'content' => $content,
            'image_content_block_button_text' => '',
            'image_content_block_button_link' => '',
            'image_content_block_bg' => '',
            'image_content_below_button' => '',
        ),
        $atts,
        'image_content_block'
    );
    // Attributes in var
    $image_content_block_image = $atts['image_content_block_image'];
    $image_src = wp_get_attachment_url($image_content_block_image);
    $image_content_block_button_text = $atts['image_content_block_button_text'];
    $image_content_block_button_link = $atts['image_content_block_button_link'];
    $image_content_below_button = $atts['image_content_below_button'];
    $image_content_block_bg = $atts['image_content_block_bg'];
    $image_content_block_bg_src = wp_get_attachment_url($image_content_block_bg);
    $style = "";
    if ($image_content_block_bg) {
        $style = 'style="background:url(' . $image_content_block_bg_src . ')no-repeat";';
    }

    // Output Code
    ob_start();
    ?>
	<div class="xt-image-block" <?php echo $style; ?>>
		<div class="inner">
			<div class="image">
				<img src="<?php echo $image_src; ?>"/>
			</div>
			<div class="content">
				<?php echo $content; ?>
			</div>

		</div>
			<div class="button">
				<div class="vc_btn3-container vc_btn3-center"><a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-round vc_btn3-style-modern vc_btn3-color-danger" href="<?php echo $image_content_block_button_link; ?>" title=""><?php echo $image_content_block_button_text; ?></a></div>
				<?php if ($image_content_below_button): ?>
				<p style="text-align: center;margin-bottom: -26px;"><?php echo $image_content_below_button; ?></p>
				<?php endif;?>
			</div>
	</div>

	<?php
$output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;

}
add_shortcode('image_content_block', 'create_imagecontentblock_shortcode');

// Create Image Content Block element for Visual Composer
add_action('vc_before_init', 'imagecontentblock_integrateWithVC');
function imagecontentblock_integrateWithVC()
{
    vc_map(array(
        'name' => __('Image Content Block', 'xstream'),
        'base' => 'image_content_block',
        'class' => 'image_content_block',
        'show_settings_on_create' => true,
        'category' => __('XT', 'xstream'),
        'icon' => get_template_directory_uri() . '/img/icon.png',
        'params' => array(
            array(
                'type' => 'attach_image',
                'holder' => 'div',
                'class' => '',
                'admin_label' => false,
                'heading' => __('', 'xstream'),
                'param_name' => 'image_content_block_image',
            ),
            array(
                'type' => 'textarea_html',
                'holder' => 'div',
                'class' => '',
                'admin_label' => false,
                'heading' => __('Content', 'xstream'),
                'param_name' => 'content',
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => false,
                'heading' => __('Button Text', 'xstream'),
                'param_name' => 'image_content_block_button_text',
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => false,
                'heading' => __('Buton Link', 'xstream'),
                'param_name' => 'image_content_block_button_link',
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => false,
                'heading' => __('Below Button text', 'xstream'),
                'param_name' => 'image_content_below_button',
            ),
            array(
                'type' => 'attach_image',
                'holder' => 'div',
                'class' => '',
                'admin_label' => false,
                'heading' => __('Background-image', 'xstream'),
                'param_name' => 'image_content_block_bg',
            ),
        ),
    ));
}

// Create Shortcode blog-grid
// Use the shortcode: [blog-grid blog_grid_query="" blog_grid_item_class="animated standby fadeIn"]
function create_bloggrid_shortcode($atts)
{
    // Attributes
    $atts = shortcode_atts(
        array(
            'post_row' => 'third',
            'blog_grid_item_class' => '',
            'posts_per_page' => '9',
            'post_excerpts' => 'no',
            'blog_grid_item_shadow' => 'yes',
            'blog_grid_item_pagination' => 'no',
        ),
        $atts,
        'blog-grid'
    );
    // Attributes in var
    global $paged;
    $posts_per_page = $atts['posts_per_page'];
    $post_row = $atts['post_row'];
    $post_excerpts = $atts['post_excerpts'];
    $blog_grid_item_shadow = $atts['blog_grid_item_shadow'];
    $blog_grid_item_class = $atts['blog_grid_item_class'];
    $blog_grid_item_pagination = $atts['blog_grid_item_pagination'];
    $output = '<div class="xt-blog-grid">';
    ob_start();
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $data = new WP_Query(array(
        'post_type' => 'post', // your post type name
        'posts_per_page' => $posts_per_page, // post per page
        'order' => 'DESC',
        'paged' => $paged,
        'orderby' => 'date',
    ));
    if ($blog_grid_item_shadow === 'yes') {
        $shadow = 'shadow';
    }
    if ($data->have_posts()):
        while ($data->have_posts()):
            $data->the_post();
            $id = get_the_ID();
            $title = get_the_title();
            $permalink = get_the_permalink();
            $featured = get_post_thumbnail_id($id, 'large');
            $featured_src = wp_get_attachment_image_src($featured, 'large')[0];
            $alt_text = get_post_meta($featured, '_wp_attachment_image_alt', true);
            $date = get_the_date('M j Y');
            echo '<div class="xt-blog-grid-item animated standby fadeIn ' . $blog_grid_item_class . ' ' . $post_row . '' . ' ' . $shadow . '">';
            if ($featured) {
                echo '<div class="xt-blog-grid-item-image"><a href="' . $permalink . '"><img src="' . $featured_src . '" alt="' . $alt_text . '"/></a></div>';
            }

            echo '<div class="xt-blog-grid-item-title"><a href="' . $permalink . '">' . $title . '</a></div>';
            if ($post_excerpts === 'yes') {
                echo '<div class="xt-blog-grid-item-content">' . get_excerpt(150) . '</div>';
            }
            echo '<div class="xt-blog-grid-item-meta">' . $date . '</div>';
            echo '</div>';
        endwhile;
        $total_pages = $data->max_num_pages;
        if ($total_pages > 1 && $blog_grid_item_pagination === 'yes') {
            $current_page = max(1, get_query_var('paged'));
            echo '<div class="xt-pagination">' . paginate_links(array(
                'base' => get_pagenum_link(1) . '%_%',
                'format' => '/page/%#%',
                'current' => $current_page,
                'total' => $total_pages,
                'prev_text' => __('« prev'),
                'next_text' => __('next »'),
            )) . '</div>';
        }

    endif;
    wp_reset_postdata();
    wp_reset_query();
    $output .= ob_get_contents();
    ob_end_clean();
    $output .= '</div>';
    return $output;
}
add_shortcode('blog-grid', 'create_bloggrid_shortcode');
// Create Blog Grid element for Visual Composer
add_action('vc_before_init', 'bloggrid_integrateWithVC');
function bloggrid_integrateWithVC()
{
    vc_map(array(
        'name' => __('Blog Grid', 'xstream'),
        'base' => 'blog-grid',
        'class' => 'blog_grid',
        'icon' => get_template_directory_uri() . '/img/icon.png',
        'show_settings_on_create' => true,
        'category' => __('XT', 'xstream'),
        'params' => array(
            array(
                'type' => 'dropdown',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('How many posts in row?', 'xstream'),
                'param_name' => 'post_row',
                'value' => array(
                    'half' => 'halves',
                    'third' => 'thirds',
                    'fourth' => 'fourths',
                ),
                'std' => 'third',
            ),
            array(
                'type' => 'dropdown',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Display Excerpts?', 'xstream'),
                'param_name' => 'post_excerpts',
                'value' => array(
                    'yes' => 'yes',
                    'no' => 'no',
                ),
                'std' => 'no',
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Number of posts per page', 'xstream'),
                'param_name' => 'posts_per_page',
                'value' => '9',
            ),
            array(
                'type' => 'dropdown',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Show Post Pagination', 'xstream'),
                'param_name' => 'blog_grid_item_pagination',
                'value' => array(
                    'yes' => 'yes',
                    'no' => 'no',
                ),
                'std' => 'no',
            ),
            array(
                'type' => 'dropdown',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Box Shadow Style', 'xstream'),
                'param_name' => 'blog_grid_item_shaddow',
                'value' => array(
                    'yes' => 'yes',
                    'no' => 'no',
                ),
                'std' => 'yes',
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Custom Class', 'xstream'),
                'param_name' => 'blog_grid_item_class',
                'value' => '',
            ),
        ),
    ));
}

vc_map(array(
    "name" => __("Tabs Wrapper", "xstream"),
    "base" => "tabs_wrapper",
    'icon' => get_template_directory_uri() . '/img/icon.png',
    'category' => __('XT', 'xstream'),
    "as_parent" => array('only' => 'tab_item'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
    "content_element" => true,
    "show_settings_on_create" => false,
    "is_container" => true,
    "js_view" => 'VcColumnView',
));

function tabs_wrapper_block_function($atts, $content)
{
    extract(shortcode_atts(array(
        'el_class' => '',
    ), $atts));
    ob_start();
    echo '<section class="team-wrapper">
' . do_shortcode($content) . '
</div>
</div>
</section>';
    $content = ob_get_contents();
    ob_get_clean();
    return $content;
}
add_shortcode('tabs_wrapper', 'tabs_wrapper_block_function');

vc_map(array(
    "name" => __("Tab Item", "xstream"),
    "base" => "tab_item",
    'icon' => get_template_directory_uri() . '/img/icon.png',
    "category" => __('XT', 'xstream'),
    "content_element" => true,
    "as_child" => array('only' => 'tabs_wrapper'), // Use only|except attributes to limit parent (separate multiple values with comma)
    "params" => array(
// add params same as with any other content element
        array(
            "type" => "textfield",
            "heading" => __("Name", "xstream"),
            "param_name" => "name",
            "description" => __("Name", "xstream"),
            "holder" => 'div',
            "class" => 'text-class',
        ),
        array(
            "type" => "textfield",
            "heading" => __("Status", "xstream"),
            "param_name" => "status",
            "description" => __("Status", "xstream"),
            "holder" => 'div',
            "class" => 'text-class',
        )
        , array(
            "type" => "attach_image",
            "heading" => __("Profile Image", "xstream"),
            "param_name" => "profile_image",
            "description" => __("Profile Image", "xstream"),
        )
        , array(
            "type" => "textarea",
            "heading" => __("Description", "xstream"),
            "param_name" => "description",
            "description" => __("Description", "xstream"),
        ),
    ),
));

function tab_item_block_function($atts, $content)
{
    extract(shortcode_atts(array(
        'name' => '',
        'status' => '',
        'profile_image' => '',
        'description' => '',
    ), $atts));

    ob_start();
    ?>
    	<div class="col-md-3 col-sm-4">
        <div class="team-thumb">
            <div class="team-img">
            	<?php $banner = wp_get_attachment_url($profile_image, 'full');?>
              <img src="<?php echo $banner; ?>" alt="">
            </div>
            <div class="team-caption">
                <div class="team-header">
                    <h3 class="name"><?php echo $name; ?></h3>
                    <h4 class="post-title"><?php echo $status; ?></h4>
                </div>
                <p><?php echo $description; ?></p>
            </div>
        </div>
      </div>
    <?php
$content = ob_get_contents();

    ob_get_clean();

    return $content;
}
add_shortcode('tab_item', 'tab_item_block_function');

if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_Tabs_Wrapper extends WPBakeryShortCodesContainer
    {
    }
}
if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_tab_item extends WPBakeryShortCode
    {
    }
}

// Create Shortcode icon_list_item
// Use the shortcode: [icon_list_item text="" custom_class=""]
function create_iconlist_items_shortcode($atts, $content = null)
{
    // Attributes
    $atts = shortcode_atts(
        array(
            'custom_class' => '',
        ),
        $atts,
        'icon_list_items'
    );
    $content = wpb_js_remove_wpautop($content, true);

    // Attributes in var
    $custom_class = $atts['custom_class'];

    // Output Code
    $output .= '<div class="custom-list ' . $custom_class . '">';
    $output .= $content;
    $output .= '</div>';

    return $output;
}
add_shortcode('icon_list_items', 'create_iconlist_items_shortcode');

// Create Icon List Item element for Visual Composer
add_action('vc_before_init', 'iconlistitem_integrateWithVC');
function iconlistitem_integrateWithVC()
{
    vc_map(array(
        'name' => __('Icon List Items', 'xstream'),
        'base' => 'icon_list_items',
        'class' => 'icon_list_items',
        'icon' => get_template_directory_uri() . '/img/icon.png',
        'show_settings_on_create' => false,
        'category' => __('XT', 'xstream'),
        'params' => array(
            array(
                'type' => 'textarea_html',
                'holder' => 'div',
                'class' => '',
                'heading' => __('Content', 'xstream'),
                'param_name' => 'content',
				'value' => __('', 'xstream'),
				'admin_label' => false,

            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => false,
                'heading' => __('Custom Class', 'xstream'),
                'param_name' => 'custom_class',
            ),
        ),
    ));
}
