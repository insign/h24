<?php get_header(); ?>
<?php if (have_posts()) : ?>
  <h1>
    <?php
    if (is_category()) {
      single_cat_title();
    } elseif (is_tag()) {
      single_tag_title();
    } elseif (is_author()) {
      the_post();
      printf(__('Escritos por %s', 'h24'), get_the_author());
      rewind_posts();
    } elseif (is_day()) {
      printf(__('Dia %s', 'h24'), get_the_date());
    } elseif (is_month()) {
      printf(__('Mês: %s', 'h24'), get_the_date('F Y'));
    } elseif (is_year()) {
      printf(__('Ano: %s', 'h24'), get_the_date('Y'));
    } else {
      _e('Arquivos', 'h24');
    }
    ?>
  </h1>

  <?php while (have_posts()) : the_post(); ?>
    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> - <span class='date'><?php
        echo get_the_date(); ?></span></h2>
  <?php endwhile; ?>

  <?php if ($GLOBALS['wp_query']->max_num_pages > 1) : ?>
    <div class="pagination">
      <?php the_posts_pagination(); ?>
    </div>
  <?php endif; ?>

<?php else : ?>
  <p><?php _e('Nada encontrado.', 'h24'); ?></p>
<?php endif; ?>

<?php get_footer(); ?>
