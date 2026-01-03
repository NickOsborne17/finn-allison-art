<?php
use Carbon_Fields\Field;

function crb_footer_fields() {
    $font_sizes = array_combine(
        $sizes = ['6px', '7px', '8px', '9px', '10px', '11px', '12px', '13px', '14px', '15px', '16px', '17px', '18px', '20px', '22px', '24px'],
        $sizes
    );
    
    $separator = '<hr style="margin: 25px 0; border: none; border-top: 1px solid #ddd;">';
    
    $create_message_fields = function($number) use ($font_sizes, $separator) {
        return [
            Field::make('html', "crb_footer_sep_{$number}")
                ->set_html($separator),
            
            Field::make('text', "crb_footer_message_{$number}_text", __('Line One'))
                ->set_help_text('Use {year} to display current year.')
                ->set_width(100),
            
            Field::make('color', "crb_footer_message_{$number}_colour", __('Text Colour'))
                ->set_help_text('Text colour')
                ->set_default_value('#333333'),
            
            Field::make('select', "crb_footer_message_{$number}_size", __('Text Alignment'))
                ->add_options($font_sizes)
                ->set_default_value('14px'),
        ];
    };
    
    return array_merge(
        [
            Field::make('complex', 'crb_social_links', __('Social Links'))
                ->set_help_text('Add your social media links')
                ->add_fields([
                    Field::make('text', 'icon', __('Font Awesome Icon Class'))
                        ->set_help_text('Get from font Awesome. e.g., fa-brands fa-facebook, fa-brands fa-twitter, fa-brands fa-instagram')
                        ->set_width(50),
                    
                    Field::make('text', 'url', __('URL'))
                        ->set_attribute('type', 'url')
                        ->set_help_text('Full URL to your social profile')
                        ->set_width(50),
                    
                    Field::make('text', 'label', __('Label (optional)'))
                        ->set_help_text('Screen reader label, e.g., "Facebook" or "Follow us on Twitter"')
                        ->set_width(100),
                ])
                ->set_header_template('
                    <% if (icon) { %>
                        <i class="<%= icon %>" style="margin-right: 8px; font-size: 18px;"></i> <%= url %>
                    <% } else { %>
                        New Social Link
                    <% } %>
                '),
        ],
        $create_message_fields(1),
        $create_message_fields(2)
    );
}