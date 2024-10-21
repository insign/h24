<?php
// Função para configurar os recursos do tema
function h24_setup()
{
  // Suporte ao título dinâmico do site
  add_theme_support("title-tag");

  // Suporte a imagens destacadas em posts e páginas
  add_theme_support("post-thumbnails");

  // Suporte a marcação HTML5
  add_theme_support("html5", ["search-form", "comment-form", "comment-list", "gallery", "caption"]);

  // Suporte a links automáticos de feed
  add_theme_support("automatic-feed-links");

  // Carrega o arquivo de texto para tradução
  load_theme_textdomain("h24", get_template_directory() . "/languages");

  // Registro de menus de navegação
  register_nav_menus([
    "primary" => __("Menu Principal", "h24"),
    "footer" => __("Menu de Rodapé", "h24"),
  ]);
}
add_action("after_setup_theme", "h24_setup");

// Função para enfileirar estilos e scripts
function h24_scripts()
{
  // Enfileirar o stylesheet principal do tema
  wp_enqueue_style("h24-style", get_stylesheet_uri(), [], wp_get_theme()->get("Version"));

  // Enfileirar o script de alternância de tema
  wp_enqueue_script("h24-scripts", get_template_directory_uri() . "/js/scripts.js", [], wp_get_theme()->get("Version"), true);
}
add_action("wp_enqueue_scripts", "h24_scripts");

// Função para verificar atualizações automáticas do tema via GitHub
function h24_theme_update($transient)
{
  // Verifica se já foi realizada uma verificação de atualização
  if (empty($transient->checked)) {
    return $transient;
  }

  // Configurações do repositório GitHub
  $user = "insign"; // Nome de usuário ou organização no GitHub
  $repo = "h24"; // Nome do repositório do tema
  $theme_slug = "h24"; // Slug do tema (diretório do tema)

  // Obtém a versão atual do tema
  $theme = wp_get_theme($theme_slug);
  $current_version = $theme->get("Version");

  // Monta a URL para a API do GitHub que retorna as tags do repositório
  $url = "https://api.github.com/repos/{$user}/{$repo}/tags";
  $args = [
    "headers" => [
      "Accept" => "application/vnd.github.v3+json",
      "User-Agent" => "WordPress/" . get_bloginfo("version") . "; " . get_bloginfo("url"),
    ],
  ];

  // Faz a requisição à API do GitHub
  $response = wp_remote_get($url, $args);

  // Verifica se houve erro na requisição
  if (is_wp_error($response)) {
    // Erro na chamada à API do GitHub
    return $transient;
  }

  // Decodifica a resposta JSON
  $tags = json_decode(wp_remote_retrieve_body($response));

  // Verifica se as tags foram obtidas corretamente
  if (!$tags || !is_array($tags)) {
    // Não foi possível obter as informações das tags
    return $transient;
  }

  // Extrai os nomes das tags
  $tag_names = array_map(function ($tag) {
    return $tag->name;
  }, $tags);

  // Ordena as tags usando version_compare
  usort($tag_names, "version_compare");

  // Obtém a última tag (maior versão)
  $latest_tag = end($tag_names);

  // Compara a versão remota com a versão atual do tema
  if (version_compare($latest_tag, $current_version, ">")) {
    // Há uma atualização disponível

    // Monta o URL para o arquivo zip da tag
    $package = "https://github.com/{$user}/{$repo}/archive/refs/tags/{$latest_tag}.zip";

    // Define os dados da atualização no objeto $transient
    $transient->response[$theme_slug] = [
      "theme" => $theme_slug,
      "new_version" => $latest_tag,
      "url" => "https://github.com/{$user}/{$repo}",
      "package" => $package,
    ];
  }

  return $transient;
}
// Adiciona filtro para verificar atualizações do tema
add_filter("pre_set_site_transient_update_themes", "h24_theme_update");

// Função para limpar o cache de atualização do tema após atualização
function h24_clear_theme_update_cache($upgrader_object, $options)
{
  if ($options["action"] === "update" && $options["type"] === "theme") {
    // Limpa o cache de atualização de temas
    delete_site_transient("update_themes");
  }
}
// Adiciona ação para limpar cache após o processo de atualização
add_action("upgrader_process_complete", "h24_clear_theme_update_cache", 10, 2);

// 1. Remover Dashicons CSS da parte pública, exceto para usuários que podem atualizar o core
function rw_remove_dashicons()
{
  if (!current_user_can("update_core")) {
    wp_deregister_style("dashicons");
  }
}
add_action("wp_enqueue_scripts", "rw_remove_dashicons", 100);

// 2. Remover links desnecessários do cabeçalho
remove_action("wp_head", "rsd_link"); // Remover link RSD
remove_action("wp_head", "wlwmanifest_link"); // Remover link WLW Manifest
remove_action("wp_head", "feed_links", 2); // Remover links RSS
remove_action("wp_head", "feed_links_extra", 3); // Remover links RSS extras
remove_action("wp_head", "adjacent_posts_rel_link", 10, 0); // Remover links de posts adjacentes
remove_action("wp_head", "adjacent_posts_rel_link_wp_head", 10, 0); // Remover links de posts adjacentes do cabeçalho
remove_action("wp_head", "wp_shortlink_wp_head", 10, 0); // Remover shortlink do cabeçalho
remove_action("template_redirect", "wp_shortlink_header", 11, 0); // Remover shortlink do header HTTP

// 3. Remover a versão do WordPress
add_filter("the_generator", "__return_empty_string");

// 4. Remover números de versão de arquivos CSS e JS
function rw_remove_version_query($src)
{
  if (strpos($src, "ver=")) {
    $src = remove_query_arg("ver", $src);
  }
  return $src;
}
add_filter("style_loader_src", "rw_remove_version_query", 9999);
add_filter("script_loader_src", "rw_remove_version_query", 9999);

// 5. Desativar emojis
function rw_disable_emojis()
{
  remove_action("admin_print_styles", "print_emoji_styles");
  remove_action("wp_head", "print_emoji_detection_script", 7);
  remove_action("admin_print_scripts", "print_emoji_detection_script");
  remove_action("wp_print_styles", "print_emoji_styles");
  remove_filter("wp_mail", "wp_staticize_emoji_for_email");
  remove_filter("the_content_feed", "wp_staticize_emoji");
  remove_filter("comment_text_rss", "wp_staticize_emoji");
  add_filter("tiny_mce_plugins", "rw_disable_emojis_tinymce");
  add_filter("wp_resource_hints", "rw_disable_emojis_remove_dns_prefetch", 10, 2);
}
add_action("init", "rw_disable_emojis");

function rw_disable_emojis_tinymce($plugins)
{
  if (is_array($plugins)) {
    return array_diff($plugins, ["wpemoji"]);
  } else {
    return [];
  }
}

function rw_disable_emojis_remove_dns_prefetch($urls, $relation_type)
{
  if ("dns-prefetch" !== $relation_type) {
    return $urls;
  }

  $emoji_svg_url = apply_filters("emoji_svg_url", "https://s.w.org/images/core/emoji/2/svg/");

  return array_diff($urls, [$emoji_svg_url]);
}

// 6. Desativar JSON API e remover links relacionados
function rw_disable_json_api()
{
  // Remover links do REST API do cabeçalho
  remove_action("wp_head", "rest_output_link_wp_head", 10);
  remove_action("wp_head", "wp_oembed_add_discovery_links", 10);
  remove_action("rest_api_init", "wp_oembed_register_route");
  add_filter("embed_oembed_discover", "__return_false");
  remove_filter("oembed_dataparse", "wp_filter_oembed_result", 10);
  remove_action("wp_head", "wp_oembed_add_discovery_links");
  remove_action("wp_head", "wp_oembed_add_host_js");
  remove_action("template_redirect", "rest_output_link_header", 11, 0);

  // Desativar o REST API
  add_filter("json_enabled", "__return_false");
  add_filter("json_jsonp_enabled", "__return_false");
  add_filter("rest_enabled", "__return_false");
  add_filter("rest_jsonp_enabled", "__return_false");
}
add_action("after_setup_theme", "rw_disable_json_api");

// 7. Remover link canônico
remove_action("embed_head", "rel_canonical");
remove_action("wp_head", "rel_canonical");

// 8. Remover versão do WooCommerce do cabeçalho (se WooCommerce estiver ativo)
remove_action("wp_head", "wc_generator_tag");

// 9. Desativar widgets padrão do WordPress
function rw_unregister_default_widgets()
{
  unregister_widget("WP_Widget_Pages");
  unregister_widget("WP_Widget_Calendar");
  unregister_widget("WP_Widget_Archives");
  unregister_widget("WP_Widget_Links");
  unregister_widget("WP_Widget_Categories");
  unregister_widget("WP_Widget_Recent_Posts");
  unregister_widget("WP_Widget_Search");
  unregister_widget("WP_Widget_Tag_Cloud");
  unregister_widget("WP_Nav_Menu_Widget");
  // Adicione mais widgets conforme necessário
}
add_action("widgets_init", "rw_unregister_default_widgets", 11);

// 10. Remover jQuery Migrate
function rw_remove_jquery_migrate($scripts)
{
  if (!is_admin() && isset($scripts->registered["jquery"])) {
    $script = $scripts->registered["jquery"];
    if ($script->deps) {
      $script->deps = array_diff($script->deps, ["jquery-migrate"]);
    }
  }
}
add_action("wp_default_scripts", "rw_remove_jquery_migrate", 99);

// 11. Desativar XML-RPC
// add_filter( 'xmlrpc_enabled', '__return_false' );

// 12. Remover scripts e estilos do Gutenberg
function rw_remove_gutenberg_assets()
{
  wp_dequeue_style("wp-block-library");
  wp_dequeue_style("wp-block-library-theme");
  wp_dequeue_style("wc-block-style"); // Remover estilos de blocos do WooCommerce, se necessário
  wp_dequeue_style("global-styles");
  wp_dequeue_style("classic-theme-styles"); // Remover estilos de editor clássico
}
add_action("wp_enqueue_scripts", "rw_remove_gutenberg_assets", 100);
remove_action("enqueue_block_assets", "wp_enqueue_registered_block_scripts_and_styles");

// 13. Desativar Gravatar
add_filter("get_avatar", "__return_false");
add_filter("option_show_avatars", "__return_false");
