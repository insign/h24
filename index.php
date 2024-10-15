<?php get_header() ?>

<?php
  // Outros tipos de páginas (ex: arquivo, busca)
  if (have_posts()):
    while (have_posts()):
      the_post() ?>
      <div class="post">
        <?php if (is_page()) : ?>
          <h2 class="entry-title"><?= get_the_title() ?></h2>
        <?php else : ?>
          <h2 class="entry-title"><?= get_the_title() ?> - <span class='date'><?= get_the_date() ?></span></h2>
        <?php endif ?>
        <div class="entry-content">
          <?php echo the_content() ?>
        </div>
      </div>
    <?php endwhile; ?>
    <?php if ( $GLOBALS['wp_query']->max_num_pages > 1 ) : ?>
      <div class="pagination">
        <?= get_the_posts_pagination() ?>
      </div>
    <?php endif; ?>
  <?php else:
    echo '<p>' . __('Nenhum post encontrado.', 'h24') . '</p>';
  endif;
?>

<?php get_footer() ?>
