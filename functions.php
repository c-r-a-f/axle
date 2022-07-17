<?php
function axle_theme_setup() {
  add_theme_support('html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'axle_theme_setup');

function axle_attachment404() {
  if (!is_attachment()) return;

  global $wp_query;
  $wp_query->set_404();
  status_header(404);
}
add_action('template_redirect', 'axle_attachment404');

function axle_knockout_author_query() {
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
  $wp_rewrite->author_base = '';
  $wp_rewrite->author_structure = '/';

  if (isset($_REQUEST['author']) && !empty($_REQUEST['author'])) {
    $user_info = get_userdata(intval($_REQUEST['author']));
    if ($user_info && array_key_exists('administrator', $user_info->caps) && in_array('administrator', $user_info->roles)) {
      wp_redirect(home_url());
      exit;
    }
  }
}
add_action('init', 'axle_knockout_author_query');

function axle_enqueue_scripts() {
    wp_enqueue_style( 'axle-style', get_stylesheet_uri() );
    wp_dequeue_style( 'wp-block-library' );
}
add_action( 'wp_enqueue_scripts', 'axle_enqueue_scripts' );

function axle_register_javascript() {
    wp_deregister_script('wp-embed');
    wp_deregister_script('comment-reply');
  }
add_action('wp_enqueue_scripts', 'axle_register_javascript');


function axle_ogp_meta() {
  if (is_admin()) return;

  global $post;
  $type = (is_singular()) ? 'article' : 'website';
  $url = (is_singular()) ? esc_url(get_permalink($post->ID)) : esc_url(home_url('/'));
  $site_name = esc_attr(get_option('blogname'));
  $title = (is_singular()) ? esc_attr($post->post_title) : $site_name;
  $image = 'http://example.com/xxx.png';

  if (is_singular()) {
    if (function_exists('has_post_thumbnail') AND has_post_thumbnail($post->ID)) {
      $attachment = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
      $image = esc_url($attachment[0]);
    }
  }
  if (is_singular()) {
    $description  = strip_tags($post->post_excerpt ? $post->post_excerpt : $post->post_content);
    $description  = mb_substr($description, 0, 90) . '...';
  } else {
    $description = esc_attr(get_option('blogdescription'));
  }

  echo '<meta property="og:type" content="' . $type . '">'.PHP_EOL;
  echo '<meta property="og:url" content="' . $url . '">'.PHP_EOL;
  echo '<meta property="og:title" content="' . $title . '">'.PHP_EOL;
  echo '<meta property="og:image" content="' . $image . '">'.PHP_EOL;
  echo '<meta property="og:site_name" content="' . $site_name . '">'.PHP_EOL;
  echo '<meta property="og:description" content="' . $description . '">'.PHP_EOL;
  echo '<meta name="description" content="' . $description . '">'.PHP_EOL;
}

@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);

remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'rsd_link' );
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles' );
remove_action('admin_print_styles', 'print_emoji_styles');

function axle_remove_src_wp_ver( $dep ) {
  $dep->default_version = '';
}
add_action( 'wp_default_scripts', 'axle_remove_src_wp_ver' );
add_action( 'wp_default_styles', 'axle_remove_src_wp_ver' );
