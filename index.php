<?php get_header() ?>

<?php
// Outros tipos de páginas (ex: arquivo, busca)
if (have_posts()):
  while (have_posts()): the_post() ?>
    <article id='item-<?php the_ID(); ?>' <?php post_class(); ?>>
      <h2 class="entry-title"><?= get_the_title() ?> - <span class='date'><?= get_the_date() ?></span></h2>
      <div class="entry-content">
        <?php the_content() ?>
      </div>
    </article>
  <?php endwhile; ?>
  <?php if ($GLOBALS['wp_query']->max_num_pages > 1) : ?>
  <div class="pagination">
    <?= get_the_posts_pagination() ?>
  </div>
<?php endif; ?>
<?php else:
  echo '<p>' . __('Nenhum post encontrado.', 'h24') . '</p>';
endif;
?>

<?php get_footer() ?>
