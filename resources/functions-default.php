<?php

use App\Classes\API;
use App\Classes\Vite;

new API();

/*========================*/
/*   Define Functions     */
/*========================*/
/** Remove non-essential items from the WP Admin bar  */
function clean_admin_bar()
{
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('wp-logo');
  // $wp_admin_bar->remove_menu('customize');
  $wp_admin_bar->remove_menu('updates');
  $wp_admin_bar->remove_menu('comments');
  $wp_admin_bar->remove_menu('itsec_admin_bar_menu');
  $wp_admin_bar->remove_menu('wpseo-menu');
}

/** Global custom stylesheet for WP back-end. */
function get_sage_admin_styles()
{
  wp_register_style('sage-admin-styles', get_theme_file_uri() . '/resources/sage-admin.css');
  wp_enqueue_style('sage-admin-styles');
}

/** Allow uploads of SVGs to the media library */
function allow_svg_upload($mimes)
{
  $mimes['svg'] = 'image/svg+xml';

  return $mimes;
}

/** fixes improper display of svg thumbnails in media library */
function fix_svg_thumb_display()
{
  echo '<style>
    td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail {
        width: 100% !important;
        height: auto !important;
    }
    </style>';
}

/** Hide pages for CPTUI and ACF if the user isn't privileged. */
function remove_menu_items_from_admin()
{
  remove_menu_page('cptui_main_menu');
  remove_menu_page('edit.php?post_type=acf-field-group');
}

function remove_wp_comments()
{
  // Redirect any user trying to access comments page
  global $pagenow;

  if ($pagenow === 'edit-comments.php') {
    wp_safe_redirect(admin_url());
    exit;
  }

  // Remove comments metabox from dashboard
  remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

  // Disable support for comments and trackbacks in post types
  foreach (get_post_types() as $post_type) {
    if (post_type_supports($post_type, 'comments')) {
      remove_post_type_support($post_type, 'comments');
      remove_post_type_support($post_type, 'trackbacks');
    }
  }
}

function enable_block_editor_for_custom_post_type($use_block_editor, $post_type)
{
  if ($post_type === 'services') {
    return true;
  }

  return $use_block_editor;
}

function manage_wp_admin_menu_link()
{
  // Remove comments from admin links menu
  remove_menu_page('edit-comments.php');
  // Remove posts from admin links menu
  remove_menu_page('edit.php');
}

function on_init()
{
  // Disable posts entirely
  unregister_post_type('post');

  // Create services post type
  register_post_type(
    'services',
    array(
      'labels' => array(
        'name' => __('Services', 'sage'),
        'singular_name' => __('Service', 'sage')
      ),
      'public' => true,
      'has_archive' => false,
      'supports' => array('title', 'editor', 'author', 'excerpt', 'comments')
    )
  );

  // Create a references post type
  register_post_type(
    'references',
    array(
      'labels' => array(
        'name' => __('References', 'sage'),
        'singular_name' => __('Reference', 'sage')
      ),
      'public' => true,
      'has_archive' => true,
      'supports' => array('title', 'editor', 'author', 'excerpt', 'comments')
    )
  );

  if (is_admin_bar_showing()) {
    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
  }
}

/** Browser detection function for Last 3 Versions of IE */
function is_ie()
{
  return boolval(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/') !== false);
}

/** opinionated theme default setup */
function theme_setup()
{
  add_theme_support('align-wide');
  add_theme_support('disable-custom-colors');
}

/** lazyload images from wysiwyg.  */
function lazy_load_wysiwyg_images($content)
{
  // parse DOM
  if (!strlen($content)) return $content;
  $content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
  $document = new DOMDocument();
  libxml_use_internal_errors(true);
  $document->loadHTML(utf8_decode($content));

  // replace image src with data-src
  $imgs = $document->getElementsByTagName('img');
  foreach ($imgs as $img) {
    $existing_class = $img->getAttribute('class');
    $img->setAttribute('class', "$existing_class lazy");
    $img->setAttribute('data-src', $img->getAttribute('src'));
    $img->setAttribute('src', '');
  }

  $html = $document->saveHTML();
  return $html;
}

// change default excerpt text from "Continued" to "Read More"
function custom_excerpt_link_text($more)
{
  $post = get_post();
  if (is_object($post)) {
    return '&hellip;<a class="read-more-link" href="' . get_the_permalink($post->ID) . '">Read More</a>';
  }
}

function strip_archive_title($title)
{
  if (is_category()) {
    $title = single_cat_title('', false);
  } elseif (is_tag()) {
    $title = single_tag_title('', false);
  } elseif (is_author()) {
    $title = '<span class="vcard">' . get_the_author() . '</span>';
  } elseif (is_post_type_archive()) {
    $title = post_type_archive_title('', false);
  } elseif (is_tax()) {
    $title = single_term_title('', false);
  } elseif (is_month()) {
    $title = str_replace('Month: ', '', $title);
  }

  return $title;
}

function images_shortcode()
{
  $items =  acf_photo_gallery('images', get_the_ID());
  $result = "";

  foreach ($items as $item) {
    $imageContent = wp_get_attachment_image($item['id'], "gallery", false, ['class' => 'absolute left-0 top-0 w-full h-full object-cover']);
    [$imageUrl, $imgWidth, $imgHeight] = wp_get_attachment_image_src($item['id'], "gallery-lightbox");

    $result .= "<a href='$imageUrl' class='gallery-item w-full aspect-square relative block' data-pswp-width='$imgWidth' data-pswp-height='$imgHeight'>$imageContent</a>";
  }

  return "<div class='grid md:grid-cols-3 gap-7 sm:gap-10 gallery-photoswipe'>$result</div>";
}

add_shortcode('images', 'images_shortcode');


/*============================*/
/*      Admin Functions       */
/*============================*/
if (is_admin()) {
  $current_user = wp_get_current_user();
  add_action('admin_head', 'get_sage_admin_styles');

  // User is not an admin
  if (!in_array('administrator', $current_user->roles)) {
    add_action('admin_init', 'remove_menu_items_from_admin');
  }
}
add_action('admin_init', 'remove_wp_comments');

/*===========================*/
/*          Actions          */
/*===========================*/

add_action('wp_before_admin_bar_render', 'clean_admin_bar');
add_action('admin_head', 'fix_svg_thumb_display');
add_action('after_setup_theme', 'theme_setup');


/*===========================*/
/*          Filters          */
/*===========================*/

add_filter('upload_mimes', 'allow_svg_upload');
add_filter('excerpt_more', 'custom_excerpt_link_text', 21);
add_filter('the_content', 'lazy_load_wysiwyg_images', 10, 1);
add_filter('get_the_archive_title', 'strip_archive_title');


// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', 'manage_wp_admin_menu_link');

// Remove comments links from admin bar
add_action('init', 'on_init');

// enable gutenberg on custom post types
add_filter('use_block_editor_for_post_type', 'enable_block_editor_for_custom_post_type', 10, 2);

add_image_size('main-page-about-me-section-image', 540, 540, true);
add_image_size('gallery', 640, 640, true);
add_image_size('gallery-lightbox', 1920, 1280, false);
