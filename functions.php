<?php
/**
 * Set up Carbon Fields
 */
use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('after_setup_theme', 'crb_load');
function crb_load() {
    require_once(__DIR__ . '/vendor/autoload.php');
    \Carbon_Fields\Carbon_Fields::boot();
}

// Include custom CSS generator
// require_once get_stylesheet_directory_uri() . '/inc/generate-css.php';
require_once(__DIR__ . '/inc/generate-css.php');

// Load custom fields
add_action('carbon_fields_register_fields', 'crb_load_custom_fields');
function crb_load_custom_fields() {
    require_once(__DIR__ . '/inc/custom-fields/colours.php');
    require_once(__DIR__ . '/inc/custom-fields/footer.php');
    require_once(__DIR__ . '/inc/custom-fields/theme-options.php');
    require_once(__DIR__ . '/inc/custom-fields/home.php');
}

add_action('admin_head', 'custom_carbon_fields_css');
function custom_carbon_fields_css() {
    echo '<style>
        .postbox-container .cf-container .cf-field {
            border-top: none !important;
        }
    </style>';
}

// Disable default Gutenberg lightbox
add_filter('render_block_core/image', function($block_content) {
    // Remove lightbox trigger attributes
    $block_content = preg_replace('/data-wp-on--click="[^"]*"/', '', $block_content);
    $block_content = preg_replace('/data-wp-on-async--click="[^"]*"/', '', $block_content);
    $block_content = preg_replace('/data-wp-context="[^"]*"/', '', $block_content);
    return $block_content;
}, 10, 1);

// Change lightbox background to black
function custom_gutenberg_styles() {
    echo '<style>
        .wp-lightbox-overlay {
            background-color: #000 !important;
        }
    </style>';
}
add_action('wp_head', 'custom_gutenberg_styles');

/**
 * Admin scripts and styles.
 */
function underscores_child_admin_scripts() {
    wp_enqueue_style(
        'font-awesome-7', 
        'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/css/all.min.css', 
        array(), 
        '7.1.0'
    );
}
add_action('admin_enqueue_scripts', 'underscores_child_admin_scripts');

/**
 * Enqueue scripts and styles.
 */
function underscores_child_enqueue_scripts() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'parent-style' ),
        wp_get_theme()->get('Version')
    );

    // Load font awesome if icons are being used in the footer
    if (!empty(carbon_get_theme_option('crb_social_links'))) {
        wp_enqueue_style(
            'font-awesome-7', 
            'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/css/all.min.css', 
            array(), 
            '7.1.0'
        );
    }

	// Custom Scripts
	wp_enqueue_style( 
        'style-main', 
        get_stylesheet_directory_uri() . '/styles/dist/style.min.css', 
        array(), 
        filemtime( get_stylesheet_directory() . '/styles/dist/style.min.css' )
    );
    wp_enqueue_script( 
        'script-main', 
        get_stylesheet_directory_uri() . '/js/dist/scripts.min.js', 
        array(), 
        filemtime( get_stylesheet_directory() . '/js/dist/scripts.min.js' ), 
        true 
    );

    // Enqueue generated CSS
    $css_info = crb_get_generated_css_info();
    
    if (file_exists($css_info['file'])) {
        wp_enqueue_style(
            'theme-generated-styles',
            $css_info['url'],
            array('style-main'),
            filemtime($css_info['file'])
        );
    }
}
add_action( 'wp_enqueue_scripts', 'underscores_child_enqueue_scripts' );

add_action('wp_head', function() {
    if (is_singular()) {
        echo '<!-- Title debug: check header.php and theme hooks -->';
    }
});

remove_all_actions('wp_body_open');

/**
 * Register Projects Post Type and Taxonomies
 */
function register_projects_post_type() {
    $labels = array(
        'name'                  => _x('Projects', 'Post Type General Name', 'underscores_child'),
        'singular_name'         => _x('Project', 'Post Type Singular Name', 'underscores_child'),
        'menu_name'             => __('Projects', 'underscores_child'),
        'name_admin_bar'        => __('Project', 'underscores_child'),
        'archives'              => __('Project Archives', 'finnalunderscores_childlison'),
        'attributes'            => __('Project Attributes', 'underscores_child'),
        'parent_item_colon'     => __('Parent Project:', 'underscores_child'),
        'all_items'             => __('All Projects', 'underscores_child'),
        'add_new_item'          => __('Add New Project', 'underscores_child'),
        'add_new'               => __('Add New', 'underscores_child'),
        'new_item'              => __('New Project', 'underscores_child'),
        'edit_item'             => __('Edit Project', 'underscores_child'),
        'update_item'           => __('Update Project', 'underscores_child'),
        'view_item'             => __('View Project', 'underscores_child'),
        'view_items'            => __('View Projects', 'underscores_child'),
        'search_items'          => __('Search Project', 'underscores_child'),
        'not_found'             => __('Not found', 'underscores_child'),
        'not_found_in_trash'    => __('Not found in Trash', 'underscores_child'),
        'featured_image'        => __('Featured Image', 'underscores_child'),
        'set_featured_image'    => __('Set featured image', 'underscores_child'),
        'remove_featured_image' => __('Remove featured image', 'underscores_child'),
        'use_featured_image'    => __('Use as featured image', 'underscores_child'),
        'insert_into_item'      => __('Insert into project', 'underscores_child'),
        'uploaded_to_this_item' => __('Uploaded to this project', 'underscores_child'),
        'items_list'            => __('Projects list', 'underscores_child'),
        'items_list_navigation' => __('Projects list navigation', 'underscores_child'),
        'filter_items_list'     => __('Filter projects list', 'underscores_child'),
    );
    
    $args = array(
        'label'                 => __('Project', 'underscores_child'),
        'description'           => __('Projects custom post type', 'underscores_child'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions'),
        'taxonomies'            => array(),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        // 'rewrite'     => array(
		// 	'slug'       => 'gallery',
		// 	'with_front' => false,
		// ),
    );
    
    register_post_type('projects', $args);
    
    // Define taxonomies to register
    $taxonomies = array(
        'project_type' => array(
            'singular' => 'Type',
            'plural'   => 'Types',
        ),
        'project_subject' => array(
            'singular' => 'Subject',
            'plural'   => 'Subjects',
        ),
        'project_stage' => array(
            'singular' => 'Stage',
            'plural'   => 'Stages',
        ),
        'project_media' => array(
            'singular' => 'Media',
            'plural'   => 'Media',
        ),
    );
    
    // Register each taxonomy
    foreach ($taxonomies as $taxonomy_key => $names) {
        $singular = $names['singular'];
        $plural = $names['plural'];
        $singular_lower = strtolower($singular);
        $plural_lower = strtolower($plural);
        
        $labels = array(
            'name'                       => _x($plural, 'Taxonomy General Name', 'underscores_child'),
            'singular_name'              => _x($singular, 'Taxonomy Singular Name', 'underscores_child'),
            'menu_name'                  => __($singular, 'underscores_child'),
            'all_items'                  => __("All {$plural}", 'underscores_child'),
            'parent_item'                => __("Parent {$singular}", 'underscores_child'),
            'parent_item_colon'          => __("Parent {$singular}:", 'underscores_child'),
            'new_item_name'              => __("New {$singular} Name", 'underscores_child'),
            'add_new_item'               => __("Add New {$singular}", 'underscores_child'),
            'edit_item'                  => __("Edit {$singular}", 'underscores_child'),
            'update_item'                => __("Update {$singular}", 'underscores_child'),
            'view_item'                  => __("View {$singular}", 'underscores_child'),
            'separate_items_with_commas' => __("Separate {$plural_lower} with commas", 'underscores_child'),
            'add_or_remove_items'        => __("Add or remove {$plural_lower}", 'underscores_child'),
            'choose_from_most_used'      => __('Choose from the most used', 'underscores_child'),
            'popular_items'              => __("Popular {$plural}", 'underscores_child'),
            'search_items'               => __("Search {$plural}", 'underscores_child'),
            'not_found'                  => __('Not Found', 'underscores_child'),
            'no_terms'                   => __("No {$plural_lower}", 'underscores_child'),
            'items_list'                 => __("{$plural} list", 'underscores_child'),
            'items_list_navigation'      => __("{$plural} list navigation", 'underscores_child'),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
            'rewrite'                    => array('with_front' => false),
            'publicly_queryable'         => false,
        );
        
        register_taxonomy($taxonomy_key, array('projects'), $args);
    }
}
add_action('init', 'register_projects_post_type', 0);

// Regester custom block styles
function register_custom_block_styles() {
    // Groups
    register_block_style(
        'core/group',
        array(
            'name'  => 'width-750',
            'label' => 'Standard Width (750px)',
            'is_default' => true,
        )
    );

    register_block_style(
        'core/group',
        array(
            'name'  => 'width-1300',
            'label' => 'Wide Width (1300px)',
        )
    );

    // Images
    register_block_style(
        'core/image',
        array(
            'name'  => 'width-750',
            'label' => 'Standard Width (750px)',
            'is_default' => true,
        )
    );

    register_block_style(
        'core/image',
        array(
            'name'  => 'width-1300',
            'label' => 'Wide Width (1300px)',
        )
    );

    // Groups
    register_block_style(
        'core/gallery',
        array(
            'name'  => 'width-750',
            'label' => 'Standard Width (750px)',
            'is_default' => true,
        )
    );

    register_block_style(
        'core/gallery',
        array(
            'name'  => 'width-1300',
            'label' => 'Wide Width (1300px)',
        )
    );
}
add_action('init', 'register_custom_block_styles', 0);

// Remove default image sizes
add_filter( 'intermediate_image_sizes', 'remove_default_img_sizes', 10, 1);
function remove_default_img_sizes( $sizes ) {
  $targets = ['medium', 'medium_large', 'large', '1536x1536', '2048x2048'];

  foreach($sizes as $size_index=>$size) {
    if(in_array($size, $targets)) {
      unset($sizes[$size_index]);
    }
  }

  return $sizes;
}

/**
 * Add classes to split menu items around centered logo
 */
function add_menu_split_classes($classes, $item, $args, $depth) {
    if ($args->theme_location === 'menu-1') {
        static $item_count = 0;
        static $total_items = null;
        
        // Get total items once
        if ($total_items === null) {
            $locations = get_nav_menu_locations();
            if (isset($locations['menu-1'])) {
                $menu_items = wp_get_nav_menu_items($locations['menu-1']);
                $total_items = $menu_items ? count($menu_items) : 0;
            }
        }
        
        $item_count++;
        $split_point = floor($total_items / 2);
        
        // Add class based on position
        if ($item_count <= $split_point) {
            $classes[] = 'before-logo';
        } else {
            $classes[] = 'after-logo';
        }
    }
    
    return $classes;
}
add_filter('nav_menu_css_class', 'add_menu_split_classes', 10, 4);

function photoswipe_image_block_markup( $block_content, $block ) {
    // Only target core image blocks
    if ( $block['blockName'] !== 'core/image' ) {
        return $block_content;
    }
    
    // Future: Check for custom attribute here when JS toggle is added
    // For now, apply to all image blocks
    
    // Parse the block content
    $dom = new DOMDocument();
    libxml_use_internal_errors( true );
    $dom->loadHTML( mb_convert_encoding( $block_content, 'HTML-ENTITIES', 'UTF-8' ), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
    libxml_clear_errors();
    
    // Find the img element
    $img = $dom->getElementsByTagName( 'img' )->item( 0 );
    
    if ( ! $img ) {
        return $block_content;
    }
    
    // Get image data
    $attachment_id = $block['attrs']['id'] ?? null;
    
    if ( ! $attachment_id ) {
        return $block_content;
    }
    
    // Get full size image URL and dimensions
    $full_image = wp_get_attachment_image_src( $attachment_id, 'full' );
    
    if ( ! $full_image ) {
        return $block_content;
    }
    
    list( $full_url, $full_width, $full_height ) = $full_image;
    
    // Create PhotoSwipe wrapper link
    $link = $dom->createElement( 'a' );
    $link->setAttribute( 'href', esc_url( $full_url ) );
    $link->setAttribute( 'data-pswp-width', $full_width );
    $link->setAttribute( 'data-pswp-height', $full_height );
    
    // Get the figure element (Gutenberg wraps images in figure)
    $figure = $dom->getElementsByTagName( 'figure' )->item( 0 );
    
    if ( $figure ) {
        // Clone the img element
        $img_clone = $img->cloneNode( true );
        
        // Insert link before img, then move img inside link
        $img->parentNode->insertBefore( $link, $img );
        $link->appendChild( $img_clone );
        $img->parentNode->removeChild( $img );
    }
    
    // Return modified HTML
    return $dom->saveHTML();
}

add_filter( 'render_block', 'photoswipe_image_block_markup', 10, 2 );