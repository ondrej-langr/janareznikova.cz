<?php

namespace App;

use App\Classes\Vite;
use Roots\Acorn\Assets\Asset\JsonAsset;
use Dotenv\Dotenv;
use Illuminate\Contracts\View\Factory as ViewFactory;

$root_dir = dirname(__DIR__, 1);
/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
$dotenv = Dotenv::createImmutable($root_dir);
if (file_exists($root_dir . '/.env')) {
  $dotenv->load();
}

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
  Vite::useVite();
}, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
  /**
   * Enable features from Soil when plugin is activated
   * @link https://roots.io/plugins/soil/
   */
  add_theme_support('soil-clean-up');
  add_theme_support('soil-jquery-cdn');
  add_theme_support('soil-nav-walker');
  add_theme_support('soil-nice-search');
  add_theme_support('soil-relative-urls');

  /**
   * Enable plugins to manage the document title
   * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
   */
  add_theme_support('title-tag');

  /**
   * Register navigation menus
   * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
   */
  register_nav_menus([
    'primary_navigation' => __('Primary Navigation', 'sage')
  ]);

  /**
   * Enable post thumbnails
   * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
   */
  add_theme_support('post-thumbnails');

  /**
   * Enable HTML5 markup support
   * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
   */
  add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

  /**
   * Enable selective refresh for widgets in customizer
   * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
   */
  add_theme_support('customize-selective-refresh-widgets');

  /**
   * Use main stylesheet for visual editor
   * @see resources/assets/styles/layouts/_tinymce.scss
   */
  add_editor_style(asset_path('styles/main.css'));

  add_theme_support('custom-logo');
}, 20);

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
  $config = [
    'before_widget' => '<div class="widget %1$s %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ];

  register_sidebar([
    'name'          => __('Sidebar with a contact', 'sage'),
    'id'            => 'sidebar-with-contact'
  ] + $config);

  register_sidebar([
    'name'          => __('Sidebar with billing information', 'sage'),
    'id'            => 'sidebar-with-billing'
  ] + $config);

  register_sidebar([
    'name'          => __('Sidebar with documents', 'sage'),
    'id'            => 'sidebar-with-documents'
  ] + $config);
});

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function ($post) {
  \app(ViewFactory::class)->share('post', $post);
});

/**
 * Setup Sage options
 */
add_action('after_setup_theme', function () {
  /**
   * Add JsonManifest to Sage container
   */
  \app()->singleton('sage.assets', function () {
    return new JsonAsset(config('assets.manifest'), config('assets.uri'));
  });

  load_theme_textdomain('sage', get_template_directory() . '/resources/lang');
});
