<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
<article id="item-<?php the_ID(); ?>" <?php post_class('content hentry'); ?>>
   <h1 class='entry-title'><?php the_title(); ?></h1>

   <div class="entry-content">
	  <?php the_content(); ?>
   </div>
</article>

<?php endwhile; ?>
<?php get_footer(); ?>
