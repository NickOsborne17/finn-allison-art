<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('post_meta', __('Home Page Options'))
    ->where('post_type', '=', 'page')
    ->where('post_template', '=', 'template-parts/template-home.php')
    ->set_context('normal')
    ->add_fields(
        array(
            Field::make('image', 'crb_hero_image', __('Hero Image'))
                ->set_help_text('Background image for hero section')
                ->set_value_type( 'url' ),

            Field::make('color', 'crb_hero_heading_color', __('Heading Colour'))
                ->set_default_value('#191919'),

            Field::make('html', 'crb_home_sep_1')
                ->set_html('<hr style="margin: 25px 0; border: none; border-top: 1px solid #ddd;">'),

            Field::make('image', 'crb_profile_image', __('Profile Image'))
                ->set_help_text('Image that appears next to bio text')
                ->set_value_type( 'url' ),

            Field::make('rich_text', 'crb_bio_content', __('Bio Content'))
        )
    );