<?php get_header(); ?>

<?php if (have_posts()):
  while (have_posts()):
    the_post(); ?>
        <div class="post">
            <h1><?php the_title(); ?></h1>
            <p><?php the_date(); ?></p>
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </div>
    <?php
  endwhile;
else:
  echo "<p>Nenhum post encontrado.</p>";
endif; ?>

<?php get_footer(); ?>
