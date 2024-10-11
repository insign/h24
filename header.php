<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo("charset"); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playpen+Sans:wght@300&display=swap" rel="stylesheet">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header>
        <div class="site-title">
          <button class="theme-toggle">
            <img class="dark-button" src="<?php echo get_template_directory_uri(); ?>/img/dark-mode.svg" width="24" height="24" alt="Ativar modo escuro" title="Ativar modo escuro">
            <img class="light-button" src="<?php echo get_template_directory_uri(); ?>/img/light-mode.svg" width="24" height="24" alt="Ativar modo claro" title="Ativar modo claro">
          </button>
            <a href="<?php echo esc_url(home_url("/")); ?>">
                <?php bloginfo("name"); ?>
            </a>
        </div>
        <nav>
            <?php wp_nav_menu([
              "theme_location" => "primary",
              "menu_class" => "primary-menu",
              "container" => false,
            ]); ?>
        </nav>
    </header>
    <div class="content">
