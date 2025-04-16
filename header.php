<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
   <meta charset="<?php bloginfo('charset') ?>">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <link rel='preconnect' href='https://fonts.googleapis.com'>
   <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
   <link href='https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap' rel='stylesheet'>

  <?php wp_head() ?>
</head>
<body <?php body_class() ?>>
<header>
   <div class="site-title">
      <button class="theme-toggle" aria-label="<?php esc_attr_e('Alternar tema (Sistema, Claro, Escuro)', 'h24') ?>">
         <!-- Ícone para ativar o modo Sistema (exibido quando o modo Escuro está ativo) -->
         <img class="theme-icon system-icon" src="<?= get_template_directory_uri() ?>/img/system-mode.svg" width="24" height="24" alt="<?php esc_attr_e('Mudar para tema claro', 'h24') ?>" title="<?php esc_attr_e('Mudar para tema claro', 'h24') ?>">
         <!-- Ícone para ativar o modo Claro (exibido quando o modo Sistema está ativo) -->
         <img class="theme-icon light-icon" src="<?= get_template_directory_uri() ?>/img/light-mode.svg" width="24" height="24" alt="<?php esc_attr_e('Mudar para tema escuro', 'h24') ?>" title="<?php esc_attr_e('Mudar para tema escuro', 'h24') ?>">
         <!-- Ícone para ativar o modo Escuro (exibido quando o modo Claro está ativo) -->
         <img class="theme-icon dark-icon" src="<?= get_template_directory_uri() ?>/img/dark-mode.svg" width="24" height="24" alt="<?php esc_attr_e('Mudar para tema do sistema', 'h24') ?>" title="<?php esc_attr_e('Mudar para tema do sistema', 'h24') ?>">
      </button>
      <a href="<?= esc_url(home_url('/')) ?>">
		  <?php bloginfo('name') ?>
      </a>
   </div>
   <nav>
	  <?php
	  wp_nav_menu([
			'theme_location' => 'primary',
			'menu_class'     => 'primary-menu',
			'container'      => false,
	  ])
	  ?>
   </nav>
</header>
<div class="content">
