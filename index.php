<?php get_header(); ?>

<?php
// Verificar se está na página inicial
if (is_home() && !is_paged()) {
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
        <h2><span><?php echo get_the_date(); ?> - </span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      </div>
    <?php endwhile;
    wp_reset_postdata();
  else:
    echo '<p>' . __('Nenhum post encontrado na categoria "articles".', 'h24') . '</p>';
  endif;
} else {
  // Outros tipos de páginas (ex: arquivo, busca)
  if (have_posts()):
    while (have_posts()):
      the_post(); ?>
      <div class="post">
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <p><?php echo get_the_date(); ?></p>
        <div class="entry-content">
          <?php the_excerpt(); ?>
        </div>
      </div>
    <?php endwhile; ?>
    <div class="pagination">
      <?php the_posts_pagination(); ?>
    </div>
  <?php else:
    echo '<p>' . __('Nenhum post encontrado.', 'h24') . '</p>';
  endif;
}
?>

<?php get_footer(); ?>
