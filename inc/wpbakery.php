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
            $featured_src = wp_get_attachment_image_src($featured, 'blog-thumb')[0];
            $alt_text = get_post_meta($featured, '_wp_attachment_image_alt', true);
            $date = get_the_date('M j Y');
            echo '<div class="xt-blog-grid-item' . $blog_grid_item_class . ' ' . $post_row . '' . ' ' . $shadow . '">';
            if ($featured) {
                echo '<div class="xt-blog-grid-item-image"><a href="' . $permalink . '"><img src="' . $featured_src . '" alt="' . $alt_text . '"/></a></div>';
            }
echo '<div class="xt-blog-grid-item-meta">' . $date . '</div>';

            echo '<div class="xt-blog-grid-item-title"><a href="' . $permalink . '">' . $title . '</a></div>';
            if ($post_excerpts === 'yes') {
                echo '<div class="xt-blog-grid-item-content">' . get_excerpt(150) . '</div>';
            }
           
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

// Create Shortcode team
// Use the shortcode: [team image="" name="" position="" linkedin=""]
function create_team_shortcode($atts , $content = null)
{
    // Attributes
    $atts = shortcode_atts(
        array(
            'image' => '',
            'name' => '',
            'position' => '',
            'linkedin' => '',
        ),
        $atts,
        'team'
    );
    // Attributes in var
    $image = wp_get_attachment_url($atts['image'], 'full');
    $name = $atts['name'];
    $position = $atts['position'];
    $linkedin = $atts['linkedin'];
    $content = wpb_js_remove_wpautop($content, true);

    // Output Code
    $output = '<div class="team-member">';
        $output .= '<div class="team-img">';
            $output .= '<img src="'.$image.'" alt="'.$name.'">';
        $output .= '</div>';
        $output .= '<div class="team-caption">';
            $output .= '<h4 class="name">'.$name.'</h4>';
            if($position){
                $output .= '<p class="post-title">' . $position . '</p>';
            }
            if($linkedin){
                $output .= '<a href="' . $linkedin . '">' . file_get_contents(get_template_directory_uri() . "/img/ln-icon.svg") . '</a>';
            }
        $output .= '</div>';
        $output .= '<div class="team-content">'.$content.'</div>';
    $output .= '</div>';

    return $output;
}
add_shortcode('team', 'create_team_shortcode');

// Create team element for Visual Composer
add_action('vc_before_init', 'team_integrateWithVC');
function team_integrateWithVC()
{
    vc_map(array(
        'name' => __('team', 'xstream'),
        'base' => 'team',
        'class' => 'team',
        'icon' => get_template_directory_uri() . '/img/icon.png',
        'show_settings_on_create' => true,
        'category' => __('XT', 'xstream'),
        'params' => array(
            array(
                'type' => 'attach_image',
                'holder' => 'div',
                'class' => '',
                'admin_label' => false,
                'heading' => __('Image', 'xstream'),
                'param_name' => 'image',
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => false,
                'heading' => __('Name', 'xstream'),
                'param_name' => 'name',
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => false,
                'heading' => __('Position', 'xstream'),
                'param_name' => 'position',
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('LinkedIn', 'xstream'),
                'param_name' => 'linkedin',
            ),
            array(
                'type' => 'textarea_html',
                'holder' => 'div',
                'class' => '',
                'heading' => __('Content', 'xstream'),
                'param_name' => 'content',
                'value' => __('', 'xstream'),
                'admin_label' => false,

            ),
        ),
    ));
}


// Shortcode
// source : https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524362

if (!function_exists('accordion_content')) {
    function accordion_content($atts, $content = null)
    {
        return '<div class="xt-accordion">' . do_shortcode($content) . '</div>';
    }
    add_shortcode('accordion_content', 'accordion_content');
}

if (!function_exists('single_accordion_content')) {
    function single_accordion_content($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'title' => '',
            'type' => '',
            'location' => '',
            'butto' => '',
        ), $atts));

    

        $output .= '<div class="accordion-item">
                    <div class="container">
                       <div class="top">';  
                          $output .= '<div class="left">
                                        <h4>'.$atts['title'].'</h4>
                                        <p>'.$atts['type'].'</p>
                                    </div>';
                           $output .= '<div class="right">
                                        <p>'.$atts['location'].'<i class="xtf xtf-arrow-down"></i></p>
                                    </div>'; 
        $output .=  '</div>';
                $output .= '<div class="bottom">'.$content.'
                <a class="btn btn-blue" href="'.$atts['button'].'">Apply Now</a></div>
                    </div>
                </div>';

        return $output;
    }
    add_shortcode('single_accordion_content', 'single_accordion_content');
}

// Mapping
vc_map(array(
    "name" => __("Accordion Content", "xt"),
    "base" => "accordion_content",
    "as_parent" => array('only' => 'single_accordion_content'),
    'icon' => get_template_directory_uri() . '/img/icon.png',
    "content_element" => true,
    "show_settings_on_create" => false,
    "is_container" => true,
    "js_view" => 'VcColumnView',
    "category" => array('XT'),
));

vc_map(array(
    "name" => __("Single Accordion Item", "xt"),
    "base" => "single_accordion_content",
    "content_element" => true,
     'icon' => get_template_directory_uri() . '/img/icon.png',
    "as_child" => array('only' => 'accordion_content'),
    "show_settings_on_create" => true,
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", "xt"),
            "param_name" => "title",
        ),
        array(
            "type" => "textarea",
            "heading" => __("Position Type", "xt"),
            "param_name" => "type",
        ),
         array(
            "type" => "textarea",
            "heading" => __("Location", "xt"),
            "param_name" => "location",
        ),
        array(
            "type" => "textfield",
            "heading" => __("Button Link", "xt"),
            "param_name" => "button",
        ),
         array(
                'type' => 'textarea_html',
                'holder' => 'div',
                'class' => '',
                'heading' => __('Content', 'xstream'),
                'param_name' => 'content',
                'value' => __('', 'xstream'),
                'admin_label' => false,

            ),
       
    ),
));

if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_Accordion_Content extends WPBakeryShortCodesContainer
    {
    }
}
if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_Single_Accordion_Content extends WPBakeryShortCode
    {
    }
}


// Create Shortcode testimonials
// Use the shortcode: [testimonials carousel_type="" category="" items_number=""]
function create_testimonials_shortcode($atts)
{
    // Attributes
    $atts = shortcode_atts(
        array(
            'carousel_type' => '',
            'category' => '',
            'items_number' => '3',
        ),
        $atts,
        'testimonials'
    );
    // Attributes in var
    $carousel_type = $atts['carousel_type'];
    $category = $atts['category'];
    $items_number = $atts['items_number'];

    if(!$category){
        $args = array(
            'post_type' => 'testimonials',
            'posts_per_page' => $items_number,
        );
    }else{
        $args = array(
        'post_type' => 'testimonials',
        'posts_per_page' => $items_number,
        'tax_query' => array(
            array(
                'taxonomy' => 'testimonial_category',
                'field' => 'slug',
                'terms' => $category,
            ),
        ),
        );
    }
    $posts = get_posts($args);
    ob_start();

    if( $carousel_type =='With Logos'){
        ?>  
        <script>
        jQuery(document).ready(function($) {
            $('.main-slider').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: false,
                asNavFor: '.logo-thumbs',
                 responsive: [
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                dots:true
                                
                            }
                        }
                    ]

                });
            $('.logo-thumbs').slick({
                slidesToShow: <?php echo $items_number;?>,
                slidesToScroll: 1,
                asNavFor: '.main-slider',
                focusOnSelect: true,
                dots: false,
                centerMode: false,
                arrows: false,
                responsive: [
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 2,
                                
                            }
                        }
                    ]
                });
        
                $('.logo-thumbs .slick-slide').removeClass('slick-active');
                // Set active class to first thumbnail slides
                $('.logo-thumbs .slick-slide').eq(0).addClass('slick-active');
                $('.main-slider').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
                    var mySlideNumber = nextSlide;
                    $('.logo-thumbs .slick-slide').removeClass('slick-active');
                    $('.logo-thumbs .slick-slide').eq(mySlideNumber).addClass('slick-active');
                });
            });
        </script>
       
    <div class="logo-thumbs">
        <?php 
       foreach ($posts as $post) {
        $thum_id = $post->ID;
        $logo = get_post_meta($thum_id, 'logo', true);
        $logo_src = wp_get_attachment_image_src($logo, 'full')[0];
        $alt =  get_post_meta($logo, '_wp_attachment_image_alt', true);

          echo '<img src="'.$logo_src.'" alt="'.$alt.'" />';
       }
        ?>
    </div>

    <?php
    }else{
        ?>
         <script>
        jQuery(document).ready(function($) {
            $('.main-slider').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: false,
                dots:true,
                });
            });
        </script>
        <?php
    }
    ?>
    
    <div class="main-slider">
  <?php
    foreach ($posts as $post) {
        $post_id = $post->ID;
        $featured_img_url = get_the_post_thumbnail_url($post_id , 'full');
        ?>
        <div class="testimonial-item">
            <?php  if( $carousel_type =='Without Logos'){?>
                <div class="xt-testimonial-author">
                <img src="<?php echo $featured_img_url;?>" alt="<?php echo $post->post_title;?>"/>
                    <div class="details">
                    <h4><?php echo $post->post_title;?></h4>
                    <h5><?php echo get_post_meta($post_id, 'position',true);?></h5>
                    </div>
                    
                </div>
            <?php } ?>

            <div class="xt-testimonials-content">
            <?php echo $post->post_content;?>
            </div>
             <?php  if( $carousel_type =='With Logos'){?>
            <div class="xt-testimonial-author">
                <div class="details">
                 <h4><?php echo $post->post_title;?></h4>
                 <h5><?php echo get_post_meta($post_id, 'position',true);?></h5>
                </div>
                <img src="<?php echo $featured_img_url;?>" alt="<?php echo $post->post_title;?>"/>
            </div>
           <?php } ?> 
        </div>
        <?php
    }
?>
    </div>


    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;  
}
add_shortcode('testimonials', 'create_testimonials_shortcode');

// Create Testimonials element for Visual Composer
add_action('vc_before_init', 'testimonials_integrateWithVC');
function testimonials_integrateWithVC()
{
    vc_map(array(
        'name' => __('Testimonials', 'xstream'),
        'base' => 'testimonials',
        'class' => 'testimonials_carousel',
        'icon' => get_template_directory_uri() . '/img/icon.png',
        'show_settings_on_create' => true,
        'category' => __('XT', 'xstream'),
        'params' => array(
            array(
                'type' => 'dropdown',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Display Logos', 'xstream'),
                'param_name' => 'carousel_type',
                'value' => array(
                    'with-logos' => 'With Logos',
                    'without-logos' => 'Without Logos',
                ),
                'std' => 'without-logos',
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Category', 'xstream'),
                'param_name' => 'category',
            ),
            array(
                'type' => 'textfield',
                'holder' => 'div',
                'class' => '',
                'admin_label' => true,
                'heading' => __('Number of items', 'xstream'),
                'param_name' => 'items_number',
            ),
        ),
    ));
}
