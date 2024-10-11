<?php
function h24_setup()
{
  // Suporte a título dinâmico
  add_theme_support("title-tag");

  // Suporte a imagens destacadas
  add_theme_support("post-thumbnails");

  // Registro de menus
  register_nav_menus([
    "primary" => __("Menu Principal", "meu-tema-basico"),
    "footer" => __("Menu de Rodapé", "meu-tema-basico"),
  ]);
}
add_action("after_setup_theme", "h24_setup");

function h24_scripts()
{
  // Enfileirar o stylesheet principal
  wp_enqueue_style("meu-tema-basico-style", get_stylesheet_uri());

  // Enfileirar o script de alternância de tema
  wp_enqueue_script("meu-tema-basico-scripts", get_template_directory_uri() . "/js/scripts.js", [], "1.0", true);
}
add_action("wp_enqueue_scripts", "h24_scripts");
