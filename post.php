<?php get_header(); ?>

  <article id="post-<?php the_ID(); ?>" <?php post_class('content hentry'); ?>>
    <h1 class='entry-title'><?php the_title(); ?></h1>

    <h2 class='date published'><?php echo get_the_date(); ?></h2>

    <div class="entry-content">
      <?php the_content(); ?>
    </div>
  </article>

<?php get_footer(); ?>
