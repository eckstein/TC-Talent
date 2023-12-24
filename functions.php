<?php
add_action('after_setup_theme', 'tctalent_setup');
function tctalent_setup()
{
    load_theme_textdomain('tctalent', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'navigation-widgets'
    ));
    add_theme_support('woocommerce');
    global $content_width;
    if (!isset($content_width)) {
        $content_width = 1920;
    }
    register_nav_menus(array(
        'main-menu' => esc_html__('Main Menu', 'tctalent')
    ));
}


add_action('wp_enqueue_scripts', 'tctalent_enqueue');
function tctalent_enqueue()
{
    wp_enqueue_style('tctalent-style', get_stylesheet_uri());
    wp_enqueue_script('jquery');

    // Custom jQuery
    wp_enqueue_script('tctalent-main', get_template_directory_uri() . '/js/tctalent-main.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('tct-quick-view', get_template_directory_uri() . '/js/quick-view.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('tct-talent-filters', get_template_directory_uri() . '/js/talent-filters.js', array('jquery'), '1.0.0', true);

    // Localize the script with the ajaxurl variable
    wp_localize_script('tctalent-main', 'tctalent_ajax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
		'tctnonce'   => wp_create_nonce( 'tct_global_nonce' )
    ));
}



add_action('wp_footer', 'tctalent_footer');
function tctalent_footer()
{
?>
<script>
jQuery(document).ready(function($) {
var deviceAgent = navigator.userAgent.toLowerCase();
if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
$("html").addClass("ios");
$("html").addClass("mobile");
}
if (deviceAgent.match(/(Android)/)) {
$("html").addClass("android");
$("html").addClass("mobile");
}
if (navigator.userAgent.search("MSIE") >= 0) {
$("html").addClass("ie");
}
else if (navigator.userAgent.search("Chrome") >= 0) {
$("html").addClass("chrome");
}
else if (navigator.userAgent.search("Firefox") >= 0) {
$("html").addClass("firefox");
}
else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
$("html").addClass("safari");
}
else if (navigator.userAgent.search("Opera") >= 0) {
$("html").addClass("opera");
}
});
</script>
<?php
}
add_filter('document_title_separator', 'tctalent_document_title_separator');
function tctalent_document_title_separator($sep)
{
    $sep = esc_html('|');
    return $sep;
}
add_filter('the_title', 'tctalent_title');
function tctalent_title($title)
{
    if ($title == '') {
        return esc_html('...');
    } else {
        return wp_kses_post($title);
    }
}
function tctalent_schema_type()
{
    $schema = 'https://schema.org/';
    if (is_single()) {
        $type = "Article";
    } elseif (is_author()) {
        $type = 'ProfilePage';
    } elseif (is_search()) {
        $type = 'SearchResultsPage';
    } else {
        $type = 'WebPage';
    }
    echo 'itemscope itemtype="' . esc_url($schema) . esc_attr($type) . '"';
}
add_filter('nav_menu_link_attributes', 'tctalent_schema_url', 10);
function tctalent_schema_url($atts)
{
    $atts['itemprop'] = 'url';
    return $atts;
}
if (!function_exists('tctalent_wp_body_open')) {
    function tctalent_wp_body_open()
    {
        do_action('wp_body_open');
    }
}
add_action('wp_body_open', 'tctalent_skip_link', 5);
function tctalent_skip_link()
{
    echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html__('Skip to the content', 'tctalent') . '</a>';
}
add_filter('the_content_more_link', 'tctalent_read_more_link');
function tctalent_read_more_link()
{
    if (!is_admin()) {
        return ' <a href="' . esc_url(get_permalink()) . '" class="more-link">' . sprintf(__('...%s', 'tctalent'), '<span class="screen-reader-text">  ' . esc_html(get_the_title()) . '</span>') . '</a>';
    }
}
add_filter('excerpt_more', 'tctalent_excerpt_read_more_link');
function tctalent_excerpt_read_more_link($more)
{
    if (!is_admin()) {
        global $post;
        return ' <a href="' . esc_url(get_permalink($post->ID)) . '" class="more-link">' . sprintf(__('...%s', 'tctalent'), '<span class="screen-reader-text">  ' . esc_html(get_the_title()) . '</span>') . '</a>';
    }
}
add_filter('big_image_size_threshold', '__return_false');
add_filter('intermediate_image_sizes_advanced', 'tctalent_image_insert_override');
function tctalent_image_insert_override($sizes)
{
    unset($sizes['medium_large']);
    unset($sizes['1536x1536']);
    unset($sizes['2048x2048']);
    return $sizes;
}
add_action('widgets_init', 'tctalent_widgets_init');
function tctalent_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar Widget Area', 'tctalent'),
        'id' => 'primary-widget-area',
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
}
add_action('wp_head', 'tctalent_pingback_header');
function tctalent_pingback_header()
{
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s" />' . "\n", esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('comment_form_before', 'tctalent_enqueue_comment_reply_script');
function tctalent_enqueue_comment_reply_script()
{
    if (get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
function tctalent_custom_pings($comment)
{
?>
<li <?php
    comment_class();
?> id="li-comment-<?php
    comment_ID();
?>"><?php
    echo esc_url(comment_author_link());
?></li>
<?php
}
add_filter('get_comments_number', 'tctalent_comment_count', 0);
function tctalent_comment_count($count)
{
    if (!is_admin()) {
        global $id;
        $get_comments     = get_comments('status=approve&post_id=' . $id);
        $comments_by_type = separate_comments($get_comments);
        return count($comments_by_type['comment']);
    } else {
        return $count;
    }
}



//Set the First middle and last name as the post title (and slug) from ACF fields
function tctalent_set_actor_post_title( $post_id ) {
    if (get_post_type($post_id) == 'talent') {
        remove_action('acf/save_post', 'tctalent_set_actor_post_title', 20);

        $titleParts = array(
            get_field('actor-first-name', $post_id),
            get_field('actor-middle-name', $post_id),
            get_field('actor-last-name', $post_id),
        );
        $title = implode(' ', array_filter($titleParts));  // Removes any empty parts and joins the rest with a space

        wp_update_post( array(
            'ID' => $post_id,
            'post_title' => $title,
            'post_name' => sanitize_title($title),
        ) );

        add_action('acf/save_post', 'tctalent_set_actor_post_title', 20);
    }
}

$custom_taxonomies = array( 'performance-type', 'age_range', 'gender' ); // Add your custom taxonomies here

// Add new columns to the post table
add_filter( 'manage_edit-talent_columns', 'add_custom_taxonomy_columns' );

function add_custom_taxonomy_columns( $columns ) {
    global $custom_taxonomies;

    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];  // Checkbox column
    $new_columns['title'] = $columns['title'];  // Post title column
    $new_columns['thumbnail'] = __( 'Thumbnail', 'textdomain' );  // Thumbnail column

    foreach ( $custom_taxonomies as $taxonomy ) {
        $taxonomy_obj = get_taxonomy( $taxonomy );
        $new_columns[$taxonomy] = __( $taxonomy_obj->label, 'textdomain' );  // Custom taxonomy columns
    }

    return $new_columns;
}

// Populate the new columns with custom taxonomy data
add_action( 'manage_talent_posts_custom_column', 'populate_custom_taxonomy_columns', 10, 2 );

function populate_custom_taxonomy_columns( $column, $post_id ) {
    global $custom_taxonomies;

    switch ( $column ) {
        case 'thumbnail':
            $thumbnail_id = get_post_thumbnail_id( $post_id );

            if ( $thumbnail_id ) {
                $thumbnail_img = wp_get_attachment_image( $thumbnail_id, array(100, 100) );

                echo $thumbnail_img;
            } else {
                echo __( 'No Thumbnail', 'textdomain' );
            }

            break;

        default:
            if ( in_array( $column, $custom_taxonomies ) ) {
                $terms = get_the_terms( $post_id, $column );

                if ( is_array( $terms ) ) {
                    $term_links = array();

                    foreach ( $terms as $term ) {
                        // Generate a link to the edit.php page with the appropriate post_type and taxonomy filters set
                        $term_link = add_query_arg(
                            array(
                                'post_type' => 'talent',
                                $column => $term->term_id,
                            ),
                            'edit.php'
                        );

                        $term_links[] = '<a href="' . esc_url( $term_link ) . '">' . $term->name . '</a>';
                    }

                    echo implode( ', ', $term_links );
                } else {
                    _e( 'No ' . $column, 'textdomain' );
                }
            }
            break;
    }
}



// Add a dropdown filter for custom taxonomy terms
add_action( 'restrict_manage_posts', 'filter_talent_by_custom_taxonomies' );

function filter_talent_by_custom_taxonomies( $post_type ) {
    global $custom_taxonomies;

    if ( 'talent' !== $post_type ) {
        return; // Only apply to 'talent' post type
    }

    foreach ( $custom_taxonomies as $taxonomy_slug ) {
        $taxonomy_obj = get_taxonomy( $taxonomy_slug );

        $selected = '';
        if ( isset( $_GET[$taxonomy_slug] ) ) {
            $selected = $_GET[$taxonomy_slug];
        }

        wp_dropdown_categories( array(
            'show_option_all' => __("Show All {$taxonomy_obj->label}"),
            'taxonomy'        => $taxonomy_slug,
            'name'            => $taxonomy_obj->name,
            'orderby'         => 'name',
            'selected'        => $selected,
            'hierarchical'    => true,
            'hide_empty'      => false,
        ) );
    }
}

// Modify the query based on the dropdown filter selection
add_filter( 'parse_query', 'filter_talent_by_custom_taxonomies_query' );

function filter_talent_by_custom_taxonomies_query( $query ) {
    global $pagenow, $custom_taxonomies;

    $post_type = 'talent'; // Change to your post type

    $q_vars = &$query->query_vars;
    if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type ) {
        foreach ( $custom_taxonomies as $taxonomy ) {
            if ( isset($_GET[$taxonomy]) && is_numeric($_GET[$taxonomy]) && $_GET[$taxonomy] != 0 ) {
                $term = get_term_by('id', $_GET[$taxonomy], $taxonomy);
                $q_vars[$taxonomy] = $term->slug;
            }
        }
    }
}

//Get Performance type based on set taxes
function topo_get_performance_type($post_id) {
	if (!$post_id) {
		return false;
	}
	$hasOnCamera = has_term('on-camera', 'performance-type', $post_id);
	$hasVoiceover = has_term('voiceover', 'performance-type', $post_id);
	if ($hasOnCamera || $hasVoiceover) {
		$return = 'Available for: ';
	}
	if ($hasOnCamera && $hasVoiceover) {
		$return = "On-Camera & Voiceover";
	} else if ($hasOnCamera) {
		$return = "On-Camera";
	} else if ($hasVoiceover) {
		$return = "Voiceover";
	} else {
		//no taxonomies set
		return false;
	}
	return $return;
}


// Quick view on talent grid
function talent_quick_view() {
    if (isset($_POST['post_id'])) {
        $post_id = intval($_POST['post_id']);
        $post = get_post($post_id);

        // Start output buffering
        ob_start();

        // Get the post image
        $image = get_the_post_thumbnail($post_id, 'large');
        ?>
        <div class="quick-view-image"><?php echo $image; ?></div>
        <div class="quick-view-details">
			<div class="quick-view-title-group">
				<h2 class="quick-view-title"><?php echo get_the_title($post_id); ?></h2>
				<?php
				$sagAftra = get_field('sag-aftra', $post_id);
				if ($sagAftra != 'none' && isset($sagAftra)) {
					?>
					<div class="quick-view-sag-aftra">SAG/AFTRA<?php if ($sagAftra == 'sagaftra_ast') echo '*'; ?></div>
					<?php
				}
				?>
				<div class="quick-view-taxes">
				<?php
				echo topo_get_performance_type($post_id);
				?>
				</div>
			</div>
            <div class="quick-view-attributes">
                <?php
                $appearanceFields = array('height', 'weight', 'hair', 'eyes');
                ?>
                <table class="talent-table">
                    <?php foreach ($appearanceFields as $fieldName) :
                        $fieldValue = get_field($fieldName, $post_id);
                        if ($fieldValue) : ?>
                            <tr class="talent-table-row field-<?php echo $fieldName; ?>">
                                <td class="attribute-label"><?php echo get_field_object($fieldName, $post_id)['label']; ?></td>
                                <td class="attribute-value"><?php echo $fieldValue; ?></td>
                            </tr>
                        <?php endif;
                    endforeach; ?>
                </table>
				<?php
					if( have_rows('actor-videos', $post_id) ) {
						?>
				<div class="talent-videos">
					<?php
						echo '<h3>Videos</h3>';
						echo '<div class="talent-videos-wrapper">';
						$videos = array();
						while( have_rows('actor-videos', $post_id) ) { the_row();
						$videoUrl = get_sub_field('actor-video-link');
						$videoTitle = get_sub_field('actor-video-title');
						?>
						<div class="talent-video">
							<h4><?php echo $videoTitle; ?></h4>
							<?php topo_display_video_player($videoUrl);?>
						</div>
						<?php
						 }
						 echo '</div>';
					?>
				</div>
				<?php }
					?>
				<?php
					if( have_rows('actor-voiceover-samples', $post_id) ) {
						?>
				<br/><br/>
				<div class="talent-audio-samples">
					<?php
						echo '<h3>Audio Samples</h2>';
						echo '<div class="talent-audio-samples-wrap">';
						while( have_rows('actor-voiceover-samples', $post_id) ) { the_row();
						$audioURL = get_sub_field('actor-audio-file');
						$audioTitle = get_sub_field('actor-audio-title');
						?>
						<div class="talent-audio">
							<h4><?php echo $audioTitle; ?></h4>
							<?php topo_display_audio_player($audioURL);?>
						</div>
						<?php }
						echo '</div>';
					?>
				</div>
				<br/><br/>
				<?php }
					?>
				
            </div>
            <div class="quick-view-profile-link"><a href="<?php echo get_the_permalink($post_id); ?>">View full profile ></a></div>
        </div>
        <?php

        // Get the buffered content and clean the buffer
        $html = ob_get_clean();

        // Return the HTML
        echo $html;
    }
    wp_die(); // This is required to terminate immediately and return a proper response
}
add_action('wp_ajax_talent_quick_view', 'talent_quick_view');
add_action('wp_ajax_nopriv_talent_quick_view', 'talent_quick_view');


function topo_display_video_player($videoUrl) {
    // Extract video ID from YouTube or Vimeo URL
    if (strpos($videoUrl, 'youtube.com') !== false) {
        // Regular YouTube URL
        $pattern = '/[?&]v=([a-zA-Z0-9_-]{11})/';
        preg_match($pattern, $videoUrl, $matches);
        $videoId = $matches[1];
        $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
    } elseif (strpos($videoUrl, 'youtu.be') !== false) {
        // Shortened YouTube URL
        $pattern = '/youtu\.be\/([a-zA-Z0-9_-]{11})/';
        preg_match($pattern, $videoUrl, $matches);
        $videoId = $matches[1];
        $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
    } elseif (strpos($videoUrl, 'vimeo.com') !== false) {
        // Vimeo URL
        $pattern = '/vimeo\.com\/([0-9]+)/';
        preg_match($pattern, $videoUrl, $matches);
        $videoId = $matches[1];
        $embedUrl = 'https://player.vimeo.com/video/' . $videoId;
    } else {
        // Invalid URL
        echo 'Invalid video URL.';
        return;
    }

    // Display embedded player
    echo '<div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
        <iframe src="' . $embedUrl . '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></iframe>
    </div>';
}


//display the audio player
function topo_display_audio_player($file) {
    if (strpos($file, 'http') === 0 || strpos($file, 'https') === 0) {
        // Remote URL
        echo '<audio controls>
            <source src="' . $file . '" type="audio/mp3">
            Your browser does not support the audio element.
        </audio>';
    } else {
        // Local file path
        if (!file_exists($file)) {
            echo 'File not found.';
            return;
        }

        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

        if ($fileExtension !== 'mp3') {
            echo 'Invalid file format. Only MP3 files are supported.';
            return;
        }

        echo '<audio controls>
            <source src="' . $file . '" type="audio/mp3">
            Your browser does not support the audio element.
        </audio>';
    }
}

// Set first gallery image as featured image
function topo_set_featured_image_from_gallery($post_id) {
    // If this isn't a 'talent' post, don't update the thumbnail.
    if (get_post_type($post_id) != 'talent') return;

    // Get the gallery field
    $gallery = get_field('talent_image_gallery', $post_id);

    // Check if the gallery is not empty
    if ($gallery && is_array($gallery)) {
        // Get the ID of the first image in the gallery
        $first_image_id = $gallery[0]['ID'];

        // Set the first image as the featured image for this post
        set_post_thumbnail($post_id, $first_image_id);
    }
}
add_action('save_post', 'topo_set_featured_image_from_gallery');




//When main search is submitted, redirect to Talent Grid
function custom_search_rewrite_rule() {
    if (is_search() && !empty($_GET['s'])) {
        $search_query = urlencode(get_query_var('s'));
        $talent_archive_url = home_url('/talent/');
        wp_redirect($talent_archive_url . '?_sf_s=' . $search_query);
        exit();
    }
}
add_action('template_redirect', 'custom_search_rewrite_rule');

function custom_admin_css() {
    echo '<style>
        #edit-slug-box {
            display: none;
        }
		#custom-permalink {
			margin-top: 10px;
		}
    </style>';
}
add_action('admin_head', 'custom_admin_css');

function add_custom_permalink() {
    global $post;
    $permalink = get_permalink($post->ID);
    
    echo '<div id="custom-permalink"><strong>Profile Link:</strong> <a href="'.$permalink.'" target="_blank">'.$permalink.'</a></div>';
}
add_action('edit_form_after_title', 'add_custom_permalink');




function tctalent_theme_customizer( $wp_customize ) {
    // Header Section with Background Color and Overlay Image
    $wp_customize->add_section( 'tctalent_header_settings', array(
        'title'    => __('Header Settings', 'tctalent'),
        'priority' => 20,
    ));

    $wp_customize->add_setting( 'header_background_color', array(
        'default'   => '#FFFFFF',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_background_color_control', array(
        'label'    => __('Header Background Color', 'tctalent'),
        'section'  => 'tctalent_header_settings',
        'settings' => 'header_background_color',
    )));

    $wp_customize->add_setting( 'header_overlay_image', array(
        'transport' => 'refresh',
    ));

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'header_overlay_image_control', array(
        'label'    => __('Header Overlay Image', 'tctalent'),
        'section'  => 'tctalent_header_settings',
        'settings' => 'header_overlay_image',
    )));

    // Color Settings Section
    $wp_customize->add_section( 'tctalent_color_settings', array(
        'title'    => __('Color Settings', 'tctalent'),
        'priority' => 30,
    ));

    // Gradient Start Color
    $wp_customize->add_setting( 'site_bg_gradient_start', array(
        'default'   => '#FFFFFF',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'site_bg_gradient_start_control', array(
        'label'    => __('Site BG Gradient Start', 'tctalent'),
        'section'  => 'tctalent_color_settings',
        'settings' => 'site_bg_gradient_start',
    )));

    // Gradient End Color
    $wp_customize->add_setting( 'site_bg_gradient_end', array(
        'default'   => '#FFFFFF',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'site_bg_gradient_end_control', array(
        'label'    => __('Site BG Gradient End', 'tctalent'),
        'section'  => 'tctalent_color_settings',
        'settings' => 'site_bg_gradient_end',
    )));

    // Main Content Background Color
    $wp_customize->add_setting( 'main_content_background_color', array(
        'default'   => '#FFFFFF',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'main_content_background_color_control', array(
        'label'    => __('Main Content Background Color', 'tctalent'),
        'section'  => 'tctalent_color_settings',
        'settings' => 'main_content_background_color',
    )));

     // Button Background Color
    $wp_customize->add_setting( 'button_background_color', array(
        'default'   => '#222222',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'button_background_color_control', array(
        'label'    => __('Button BG Color', 'tctalent'),
        'section'  => 'tctalent_color_settings',
        'settings' => 'button_background_color',
    )));

     // Button Text Color
    $wp_customize->add_setting( 'button_text_color', array(
        'default'   => '#FFFFFF',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'button_text_color_control', array(
        'label'    => __('Button Text Color', 'tctalent'),
        'section'  => 'tctalent_color_settings',
        'settings' => 'button_text_color',
    )));
}

add_action( 'customize_register', 'tctalent_theme_customizer' );



function tctalent_customizer_css() {
    ?>
    <style type="text/css">
        #wrapper {
            background-color: <?php echo get_theme_mod('site_bg_gradient_start', '#FFFFFF'); ?>;
            background-image: linear-gradient(to bottom right, <?php echo get_theme_mod('site_bg_gradient_start', '#FFFFFF'); ?>, <?php echo get_theme_mod('site_bg_gradient_end', '#000000'); ?>);
            background-attachment: fixed;
        }

        #container {
            padding: 40px;
            max-width: 1400px;
            margin: 0 auto;
            background-color: <?php echo get_theme_mod('main_content_background_color', 'rgba(255,255,255,.97)'); ?>;
        }

        @media screen and (max-width: 768px) {
            #container {
                padding: 20px;
                border-left: 4px solid #E3C467;
                border-right: 4px solid #E3C467;
            }
        }
        input[type="submit"],
        button,
        a.button {
            font-size: 18px;
            font-family: "Pierson", serif;
            padding: 9px 20px 7px;
            border: 1px solid #d2c659;
            border-radius: 5px;
            color: <?php echo get_theme_mod('button_text_color', '#FFFFFF'); ?>;
            cursor: pointer;
            transition: background-color 0.3s;
            background-color: <?php echo get_theme_mod('button_background_color', '#222222'); ?>;
            text-decoration: none;
        }

        input[type="submit"]:hover,
        button:hover,
        a.button:hover {
            filter: brightness(120%); 
        }

        input[type="submit"]:active,
        button:active,
        a.button:active {
            filter: brightness(120%);
        }

    </style>

    <?php
}
add_action('wp_head', 'tctalent_customizer_css');




