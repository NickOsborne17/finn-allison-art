<?php
use Carbon_Fields\Field;

function crb_footer_fields() {
    return array(
        Field::make('complex', 'crb_social_links', __('Social Links'))
            ->set_help_text('Add your social media links')
            ->add_fields(array(
                Field::make('text', 'icon', __('Font Awesome Icon Class'))
                    ->set_help_text('e.g., fa-brands fa-facebook, fa-brands fa-twitter, fa-brands fa-instagram')
                    ->set_width(50),
                Field::make('text', 'url', __('URL'))
                    ->set_attribute('type', 'url')
                    ->set_help_text('Full URL to your social profile')
                    ->set_width(50),
                Field::make('text', 'label', __('Label (optional)'))
                    ->set_help_text('Screen reader label, e.g., "Facebook" or "Follow us on Twitter"')
                    ->set_width(100),
            ))
            ->set_header_template('
                <% if (icon) { %>
                    <i class="<%= icon %>" style="margin-right: 8px; font-size: 18px;"></i> <%= url %>
                <% } else { %>
                    New Social Link
                <% } %>
            '),
        
        Field::make('textarea', 'crb_footer_message', __('Footer Message'))
            ->set_rows(2)
            ->set_help_text('Add a message to display in the footer. Use {year} to display current year.')
            ->set_width(100),
    );
}