<?php

// Vite
// Main switch to get fontend assets from a Vite dev server OR from production built folder
// it is recommeded to move it into wp-config.php
// define('IS_VITE_DEVELOPMENT', false);

if (!defined('WPCF7_AUTOP')) {
	define('WPCF7_AUTOP', false);
}

include "inc/inc.vite.php";
include "inc/helper-fns.php";
require_once(realpath(dirname(__FILE__) . '/blocks/register-blocks.php'));


/**
 * Vite_starter functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Vite_starter
 */

if (!defined('BHB_BAYERN_VERSION')) {
	// Replace the version number of the theme on each release.
	define('BHB_BAYERN_VERSION', '0.1.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function bhb_bayern_setup()
{
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Vite_starter, use a find and replace
	 * to change 'bhb-bayern' to the name of your theme in all the template files.
	 */
	load_theme_textdomain('bhb-bayern', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support('title-tag');

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Main Navigation', 'bhb-bayern'),
			'menu-2' => esc_html__('Secondary Navigation', 'bhb-bayern'),
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			// 'comment-form',
			// 'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'bhb_bayern_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height' => 250,
			'width' => 250,
			'flex-width' => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'bhb_bayern_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function bhb_bayern_content_width()
{
	$GLOBALS['content_width'] = apply_filters('bhb_bayern_content_width', 640);
}
add_action('after_setup_theme', 'bhb_bayern_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function bhb_bayern_widgets_init()
{
	// register_sidebar(
	// 	array(
	// 		'name'          => esc_html__('Sidebar', 'bhb-bayern'),
	// 		'id'            => 'sidebar-1',
	// 		'description'   => esc_html__('Add widgets here.', 'bhb-bayern'),
	// 		'before_widget' => '<section id="%1$s" class="widget %2$s">',
	// 		'after_widget'  => '</section>',
	// 		'before_title'  => '<h2 class="widget-title">',
	// 		'after_title'   => '</h2>',
	// 	)
	// );
}
add_action('widgets_init', 'bhb_bayern_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function bhb_bayern_scripts()
{

	if (defined('IS_VITE_DEVELOPMENT') && IS_VITE_DEVELOPMENT === false) {
		wp_enqueue_style('bhb-bayern-style', get_stylesheet_uri(), array(), BHB_BAYERN_VERSION);
		wp_style_add_data('bhb-bayern-style', 'rtl', 'replace');
	}

	// wp_enqueue_script('vitestarter-by-vlownavigation', get_template_directory_uri() . '/js/navigation.js', array(), BHB_BAYERN_VERSION, true);

	// if (is_singular() && comments_open() && get_option('thread_comments')) {
	// 	wp_enqueue_script('comment-reply');
	// }
}
add_action('wp_enqueue_scripts', 'bhb_bayern_scripts');


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';



/** 
 * Contact Form 7 
 */
add_filter('wpcf7_form_elements', function ($content) {
	$content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);

	return $content;
});



add_filter('disable_captions', function () {
	return true;
});


add_filter('allowed_block_types_all', 'bhb_bayern_allowed_block_types', 25, 2);

function bhb_bayern_allowed_block_types($allowed_blocks, $editor_context)
{

	return array(
		// 'core/image',
		// 'core/paragraph',
		// 'core/heading',
		// 'core/list',
		// 'core/list-item',
		'vitestarter/accordian',
		'vitestarter/content',
		'vitestarter/form',
		'vitestarter/hero',
		'vitestarter/atoms',
		'vitestarter/molecules',
	);
}

function bhb_bayern_plugin_block_categories($categories)
{
	return array_merge(
		$categories,
		[
			[
				'slug' => 'vitestarter',
				'title' => __('BHB_Bayern Blocks', 'bhb-bayern'),
			],
		]
	);
}
add_action('block_categories', 'bhb_bayern_plugin_block_categories', 10, 2);


function custom_upload_and_move_file($request)
{
	$upload_dir = wp_upload_dir();
	$destination_path = $upload_dir['basedir'] . '/gutenberg-uploads/';

	if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
		$temp_file = $_FILES['file']['tmp_name'];
		$destination_file = $destination_path . $_FILES['file']['name'];

		if (!file_exists($destination_path)) {
			wp_mkdir_p($destination_path);
		}

		move_uploaded_file($temp_file, $destination_file);


		return new WP_REST_Response('File uploaded and moved successfully', 200);
	}

	return new WP_REST_Response('File upload failed', 500);
}

add_action('rest_api_init', function () {
	register_rest_route(
		'custom/v1',
		'/upload-and-move',
		array(
			'methods' => 'POST',
			'callback' => 'custom_upload_and_move_file',
		)
	);
});