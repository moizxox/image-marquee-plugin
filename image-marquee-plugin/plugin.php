<?php
/**
 * Plugin Name: Image Marquee Plugin
 * Description: A plugin for making image marquee in Elementor! 
 * Version: 1.0
 * Author: Mian Moiz
 * Author URI: https://github.com/moizxox
 */

// Exit if accessed directly
if(!defined('ABSPATH')){
    exit;
}

function register_custom_widget(){
    require_once plugin_dir_path(__FILE__).'widget.php';
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new My_Marquee_Widget());
}
add_action('elementor/widgets/widgets_registered', 'register_custom_widget');
