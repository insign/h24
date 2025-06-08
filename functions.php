<?php
// Function to set up theme features
function h24_setup ()
{
  // Support for dynamic site title
  add_theme_support('title-tag');
  
  // Support for featured images in posts and pages
  add_theme_support('post-thumbnails');
  
  // Support for HTML5 markup
  add_theme_support('html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ]);
  
  // Support for automatic feed links
  add_theme_support('automatic-feed-links');
  
  // Load the text domain for translation
  load_theme_textdomain('h24', get_template_directory() . '/languages');
  
  // Register navigation menus
  register_nav_menus([
                       'primary' => __('Menu Principal', 'h24'),
                       'footer'  => __('Menu de Rodapé', 'h24'),
                     ]);
}

add_action('after_setup_theme', 'h24_setup');

// Function to enqueue styles and scripts
function h24_scripts ()
{
  // Enqueue the main theme stylesheet
  wp_enqueue_style('h24-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
  
  // Enqueue the theme toggle script
  wp_enqueue_script('h24-scripts',
                    get_template_directory_uri() . '/js/scripts.js',
                    [],
                    wp_get_theme()->get('Version'),
                    TRUE);
}

add_action('wp_enqueue_scripts', 'h24_scripts');

// Function to check for automatic theme updates via GitHub
function h24_theme_update ( $transient )
{
  // Check if an update check has already been performed
  if (empty($transient->checked)) {
    return $transient;
  }
  
  // GitHub repository settings
  $user       = 'insign'; // GitHub username or organization
  $repo       = 'h24';    // Theme repository name
  $theme_slug = 'h24';    // Theme slug (theme directory)
  
  // Get the current theme version
  $theme           = wp_get_theme($theme_slug);
  $current_version = $theme->get('Version');
  
  // Build the URL for the GitHub API that returns repository tags
  $url  = "https://api.github.com/repos/{$user}/{$repo}/tags";
  $args = [
    'headers' => [
      'Accept'     => 'application/vnd.github.v3+json',
      'User-Agent' => 'WordPress/' . get_bloginfo('version') . '; ' . get_bloginfo('url'),
    ],
  ];
  
  // Make the request to the GitHub API
  $response = wp_remote_get($url, $args);
  
  // Check for errors in the request
  if (is_wp_error($response)) {
    // Error in the GitHub API call
    return $transient;
  }
  
  // Decode the JSON response
  $tags = json_decode(wp_remote_retrieve_body($response));
  
  // Check if tags were obtained correctly
  if (!$tags || !is_array($tags)) {
    // Could not get tag information
    return $transient;
  }
  
  // Extract tag names
  $tag_names = array_map(function ( $tag ) {
    return $tag->name;
  }, $tags);
  
  // Sort tags using version_compare
  usort($tag_names, 'version_compare');
  
  // Get the latest tag (highest version)
  $latest_tag = end($tag_names);
  
  // Compare the remote version with the current theme version
  if (version_compare($latest_tag, $current_version, '>')) {
    // An update is available
    
    // Build the URL for the tag's zip file
    $package = "https://github.com/{$user}/{$repo}/archive/refs/tags/{$latest_tag}.zip";
    
    // Set the update data in the $transient object
    $transient->response[ $theme_slug ] = [
      'theme'       => $theme_slug,
      'new_version' => $latest_tag,
      'url'         => "https://github.com/{$user}/{$repo}",
      'package'     => $package,
    ];
  }
  
  return $transient;
}

// Add filter to check for theme updates
add_filter('pre_set_site_transient_update_themes', 'h24_theme_update');

// Function to clear the theme update cache after an update
function h24_clear_theme_update_cache ( $upgrader_object, $options )
{
  if ($options[ 'action' ] === 'update' && $options[ 'type' ] === 'theme') {
    // Clear the theme update cache
    delete_site_transient('update_themes');
  }
}

// Add action to clear cache after the update process is complete
add_action('upgrader_process_complete', 'h24_clear_theme_update_cache', 10, 2);

// 1. Remove Dashicons CSS from the frontend, except for users who can update the core
function rw_remove_dashicons ()
{
  if (!current_user_can('update_core')) {
    wp_deregister_style('dashicons');
  }
}

add_action('wp_enqueue_scripts', 'rw_remove_dashicons', 100);

// 2. Remove unnecessary links from the header
remove_action('wp_head', 'rsd_link');                               // Remove RSD link
remove_action('wp_head', 'wlwmanifest_link');                       // Remove WLW Manifest link
remove_action('wp_head', 'feed_links', 2);                          // Remove RSS links
remove_action('wp_head', 'feed_links_extra', 3);                    // Remove extra RSS links
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);         // Remove adjacent posts links
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); // Remove adjacent posts links from the header
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);            // Remove shortlink from the header
remove_action('template_redirect', 'wp_shortlink_header', 11, 0);   // Remove shortlink from the HTTP header

// 3. Remove WordPress version
add_filter('the_generator', '__return_empty_string');

// 4. Remove version numbers from CSS and JS files
function rw_remove_version_query ( $src )
{
  if (strpos($src, 'ver=')) {
    $src = remove_query_arg('ver', $src);
  }
  
  return $src;
}

add_filter('style_loader_src', 'rw_remove_version_query', 9999);
add_filter('script_loader_src', 'rw_remove_version_query', 9999);

// 5. Disable emojis
function rw_disable_emojis ()
{
  remove_action('admin_print_styles', 'print_emoji_styles');
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  add_filter('tiny_mce_plugins', 'rw_disable_emojis_tinymce');
  add_filter('wp_resource_hints', 'rw_disable_emojis_remove_dns_prefetch', 10, 2);
}

add_action('init', 'rw_disable_emojis');

function rw_disable_emojis_tinymce ( $plugins )
{
  if (is_array($plugins)) {
    return array_diff($plugins, [ 'wpemoji' ]);
  }
  else {
    return [];
  }
}

function rw_disable_emojis_remove_dns_prefetch ( $urls, $relation_type )
{
  if ('dns-prefetch' !== $relation_type) {
    return $urls;
  }
  
  $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
  
  return array_diff($urls, [ $emoji_svg_url ]);
}

// 6. Disable JSON API and remove related links
function rw_disable_json_api ()
{
  // Remove REST API links from the header
  remove_action('wp_head', 'rest_output_link_wp_head', 10);
  remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
  remove_action('rest_api_init', 'wp_oembed_register_route');
  add_filter('embed_oembed_discover', '__return_false');
  remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
  remove_action('wp_head', 'wp_oembed_add_discovery_links');
  remove_action('wp_head', 'wp_oembed_add_host_js');
  remove_action('template_redirect', 'rest_output_link_header', 11, 0);
  
  // Disable the REST API
  add_filter('json_enabled', '__return_false');
  add_filter('json_jsonp_enabled', '__return_false');
  add_filter('rest_enabled', '__return_false');
  add_filter('rest_jsonp_enabled', '__return_false');
}

add_action('after_setup_theme', 'rw_disable_json_api');

// 7. Remove canonical link
remove_action('embed_head', 'rel_canonical');
remove_action('wp_head', 'rel_canonical');

// 8. Remove WooCommerce version from header (if WooCommerce is active)
remove_action('wp_head', 'wc_generator_tag');

// 9. Disable default WordPress widgets
function rw_unregister_default_widgets ()
{
  unregister_widget('WP_Widget_Pages');
  unregister_widget('WP_Widget_Calendar');
  unregister_widget('WP_Widget_Archives');
  unregister_widget('WP_Widget_Links');
  unregister_widget('WP_Widget_Categories');
  unregister_widget('WP_Widget_Recent_Posts');
  unregister_widget('WP_Widget_Search');
  unregister_widget('WP_Widget_Tag_Cloud');
  unregister_widget('WP_Nav_Menu_Widget');
  // Add more widgets as needed
}

add_action('widgets_init', 'rw_unregister_default_widgets', 11);

// 10. Remove jQuery Migrate
function rw_remove_jquery_migrate ( $scripts )
{
  if (!is_admin() && isset($scripts->registered[ 'jquery' ])) {
    $script = $scripts->registered[ 'jquery' ];
    if ($script->deps) {
      // Remove 'jquery-migrate' from the dependencies array
      $script->deps = array_diff($script->deps, [ 'jquery-migrate' ]);
    }
  }
}

add_action('wp_default_scripts', 'rw_remove_jquery_migrate', 99);

// 11. Disable XML-RPC
// add_filter( 'xmlrpc_enabled', '__return_false' );

// 12. Remove Gutenberg scripts and styles
function rw_remove_gutenberg_assets ()
{
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  wp_dequeue_style('wc-block-style'); // Remove WooCommerce block styles, if necessary
  wp_dequeue_style('global-styles');
  wp_dequeue_style('classic-theme-styles'); // Remove classic editor styles
}

add_action('wp_enqueue_scripts', 'rw_remove_gutenberg_assets', 100);
remove_action('enqueue_block_assets', 'wp_enqueue_registered_block_scripts_and_styles');

// 13. Disable Gravatar
add_filter('get_avatar', '__return_false');
add_filter('option_show_avatars', '__return_false');

// 14. Sort posts in the 'pages' category by modification date
function h24_sort_pages_category_by_modified_date ( $query )
{
  // Check if we are not in the admin panel, if it is the main query
  // and if we are on the 'pages' category archive page
  if (!is_admin() && $query->is_main_query() && $query->is_category('pages')) {
    // Set the orderby criterion to modification date
    $query->set('orderby', 'modified');
    // Set the order to descending (newest first)
    $query->set('order', 'DESC');
  }
}

// Add the function to the 'pre_get_posts' hook to modify the query before it is executed
add_action('pre_get_posts', 'h24_sort_pages_category_by_modified_date');

// 15. Add social media meta tags (Open Graph & Twitter Cards)
function h24_add_social_meta_tags ()
{
  if (is_singular()) {
    $post_id   = get_the_ID();
    $title     = get_the_title();
    $url       = get_permalink();
    // get_the_excerpt() automatically generates an excerpt from content if a manual one doesn't exist.
    $excerpt   = wp_strip_all_tags(get_the_excerpt($post_id));
    $site_name = get_bloginfo('name');
    
    // Open Graph Tags
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($excerpt) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_attr($url) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
    echo '<meta property="og:type" content="article">' . "\n";
    
    // Twitter Card Tags
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($excerpt) . '">' . "\n";
    
    if (has_post_thumbnail($post_id)) {
      // Use the 'large' image size as a good balance of quality and performance
      $image_url = get_the_post_thumbnail_url($post_id, 'large');
      echo '<meta property="og:image" content="' . esc_attr($image_url) . '">' . "\n";
      echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
      echo '<meta name="twitter:image" content="' . esc_attr($image_url) . '">' . "\n";
    }
    else {
      echo '<meta name="twitter:card" content="summary">' . "\n";
    }
  }
}

add_action('wp_head', 'h24_add_social_meta_tags', 5);