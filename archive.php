<?php get_header(); ?>
    <?php if (have_posts()) : ?>
        <h1 class="text-center">
          <?php
          if (is_category()) {
            single_cat_title();
          } elseif (is_tag()) {
            single_tag_title();
          } elseif (is_author()) {
            the_post();
            echo 'Escritos por ' . get_the_author();
            rewind_posts();
          } elseif (is_day()) {
            echo 'Dia ' . get_the_date();
          } elseif (is_month()) {
            echo 'Mês: ' . get_the_date('F Y');
          } elseif (is_year()) {
            echo 'Ano: ' . get_the_date('Y');
          } else {
            echo 'Arquivos';
          }
          ?>
        </h1>

      <?php while (have_posts()) : the_post(); ?>
            <h2 class="entry-title"><span class="date"><?php the_date(); ?></span> - <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <?php endwhile; ?>

      <div class="pagination">
        <?php the_posts_pagination(); ?>
      </div>

    <?php else : ?>
      <p>Nada encontrado.</p>
    <?php endif; ?>

<?php get_footer(); ?>
