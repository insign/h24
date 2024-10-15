<?php get_header(); ?>

<?php
  // Argumentos para a consulta
  $args = [
    'category_name' => 'articles',
    'posts_per_page' => 3,
  ];
  $blog_query = new WP_Query($args);

  if ($blog_query->have_posts()): ?>
    <h1><?php _e('Artigos Recentes', 'h24'); ?></h1>
    <?php
    while ($blog_query->have_posts()):
      $blog_query->the_post(); ?>
      <div class="post">
        <h2><span class="date"><?php echo get_the_date(); ?> - </span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      </div>
    <?php endwhile;
    wp_reset_postdata();
  else:
    echo '<p>' . __('Nenhum artigo encontrado, algo está errado.', 'h24') . '</p>';
  endif;

  // Argumentos para a consulta
  $args = [
    'category_name' => 'ideas',
    'posts_per_page' => 3,
  ];
  $blog_query = new WP_Query($args);

  if ($blog_query->have_posts()): ?>
    <h1><?php _e('Invenções Recentes', 'h24'); ?></h1>
    <?php
    while ($blog_query->have_posts()):
      $blog_query->the_post(); ?>
      <div class="post">
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      </div>
    <?php endwhile; wp_reset_postdata();
  else:
    echo '<p>' . __('Nenhum ideia encontrada, algo está errado.', 'h24') . '</p>';
  endif;
?>

<?php get_footer(); ?>
