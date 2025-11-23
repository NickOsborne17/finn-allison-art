<?php
use Carbon_Fields\Field;

function crb_colours_fields() {
    return array(
        Field::make('color', 'crb_primary_colour', __('Primary Colour'))
            ->set_help_text('Main brand colour'),
        
        Field::make('color', 'crb_secondary_colour', __('Secondary Colour'))
            ->set_help_text('Secondary brand colour'),
        
        Field::make('color', 'crb_accent_colour', __('Accent Colour'))
            ->set_help_text('Accent colour for highlights'),
        
        Field::make('color', 'crb_text_colour', __('Text Colour'))
            ->set_help_text('Main text colour')
            ->set_default_value('#333333'),
        
        Field::make('color', 'crb_background_colour', __('Background Colour'))
            ->set_help_text('Page background colour')
            ->set_default_value('#ffffff'),
    );
}