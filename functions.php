<?php
// Função para configurar os recursos do tema
function h24_setup() {
  // Suporte ao título dinâmico do site
  add_theme_support('title-tag');

  // Suporte a imagens destacadas em posts e páginas
  add_theme_support('post-thumbnails');

  // Registro de menus de navegação
  register_nav_menus([
                       'primary' => __('Menu Principal', 'h24'),
                       'footer'  => __('Menu de Rodapé', 'h24'),
                     ]);
}
add_action('after_setup_theme', 'h24_setup');

// Função para enfileirar estilos e scripts
function h24_scripts() {
  // Enfileirar o stylesheet principal do tema
  wp_enqueue_style('h24-style', get_stylesheet_uri());

  // Enfileirar o script de alternância de tema
  wp_enqueue_script('h24-scripts', get_template_directory_uri() . '/js/scripts.js', [], '1.0', true);
}
add_action('wp_enqueue_scripts', 'h24_scripts');

// Função para verificar atualizações automáticas do tema via GitHub
function h24_theme_update($transient) {
  // Verifica se já foi realizada uma verificação de atualização
  if (empty($transient->checked)) {
    return $transient;
  }

  // Configurações do repositório GitHub
  $user       = 'insign';    // Nome de usuário ou organização no GitHub
  $repo       = 'h24';       // Nome do repositório do tema
  $theme_slug = 'h24';       // Slug do tema (diretório do tema)

  // Obtém a versão atual do tema
  $theme           = wp_get_theme($theme_slug);
  $current_version = $theme->get('Version');

  // Monta a URL para a API do GitHub que retorna as tags do repositório
  $url  = "https://api.github.com/repos/{$user}/{$repo}/tags";
  $args = [
    'headers' => [
      'Accept'     => 'application/vnd.github.v3+json',
      'User-Agent' => 'WordPress/' . get_bloginfo('version') . '; ' . get_bloginfo('url'),
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
  $tag_names = array_map(function($tag) {
    return $tag->name;
  }, $tags);

  // Ordena as tags usando version_compare
  usort($tag_names, 'version_compare');

  // Obtém a última tag (maior versão)
  $latest_tag = end($tag_names);

  // Compara a versão remota com a versão atual do tema
  if (version_compare($latest_tag, $current_version, '>')) {
    // Há uma atualização disponível

    // Monta o URL para o arquivo zip da tag
    $package = "https://github.com/{$user}/{$repo}/archive/refs/tags/{$latest_tag}.zip";

    // Define os dados da atualização no objeto $transient
    $transient->response[$theme_slug] = [
      'theme'       => $theme_slug,
      'new_version' => $latest_tag,
      'url'         => "https://github.com/{$user}/{$repo}",
      'package'     => $package,
    ];
  }

  return $transient;
}
// Adiciona filtro para verificar atualizações do tema
add_filter('pre_set_site_transient_update_themes', 'h24_theme_update');

// Função para limpar o cache de atualização do tema após atualização
function h24_clear_theme_update_cache($upgrader_object, $options) {
  if ($options['action'] == 'update' && $options['type'] == 'theme') {
    // Limpa o cache de atualização de temas
    delete_site_transient('update_themes');
  }
}
// Adiciona ação para limpar cache após o processo de atualização
add_action('upgrader_process_complete', 'h24_clear_theme_update_cache', 10, 2);
