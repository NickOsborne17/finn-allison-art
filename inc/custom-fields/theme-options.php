<?php
use Carbon_Fields\Container;

Container::make('theme_options', __('Theme Options'))
    ->set_page_parent('themes.php')
    ->set_page_file('theme-options')
    ->add_tab(__('Colours'), crb_colours_fields())
    ->add_tab(__('Social'), crb_social_fields());