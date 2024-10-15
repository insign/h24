<?php get_header(); ?>

<article id="item-<?php the_ID(); ?>" <?php post_class('content hentry'); ?>>
  <h1 class='entry-title'><?php the_title(); ?></h1>

  <div class="entry-content">
    <?php the_content(); ?>
  </div>
</article>

<?php get_footer(); ?>
