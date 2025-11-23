<?php
/**
 * Generate custom CSS from Carbon Fields values
 */

function crb_build_custom_css() {
    // Retrieve all Carbon Fields values
    $primary_colour = carbon_get_theme_option('crb_primary_colour');
    $secondary_colour = carbon_get_theme_option('crb_secondary_colour');
    $accent_colour = carbon_get_theme_option('crb_accent_colour');
    $text_colour = carbon_get_theme_option('crb_text_colour');
    $background_colour = carbon_get_theme_option('crb_background_colour');
    
    // Build CSS content
    $css = "
    /* Custom CSS generated from theme settings */
    /* Generated: " . date('Y-m-d H:i:s') . " */

    :root {
        --primary-color: {$primary_colour};
        --secondary-color: {$secondary_colour};
        --accent-color: {$accent_colour};
        --text-color: {$text_colour};
        --background-color: {$background_colour};
    }
";
    
    return $css;
}

function crb_generate_css_file() {
    $css = crb_build_custom_css();
    
    // Set file location in theme
    $theme_dir = get_template_directory();
    $css_dir = $theme_dir . '/styles/dist';
    $css_file = $css_dir . '/theme-generated-styles.css';
    
    // Create directory if needed
    if (!file_exists($css_dir)) {
        wp_mkdir_p($css_dir);
    }
    
    // Write CSS file
    $result = file_put_contents($css_file, $css);
    
    if ($result === false) {
        error_log('Failed to write custom CSS file to: ' . $css_file);
        return false;
    }
    
    return true;
}

function crb_get_generated_css_info() {
    return array(
        'file' => get_template_directory() . '/styles/dist/theme-generated-styles.css',
        'url' => get_template_directory_uri() . '/styles/dist/theme-generated-styles.css',
    );
}

// Hook to regenerate CSS when theme options are saved
add_action('carbon_fields_theme_options_container_saved', 'crb_generate_css_file');