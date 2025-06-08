<?php get_header(); ?>

  <article id="item-<?php the_ID(); ?>" <?php post_class(); ?>>
    <h1 class='entry-title'><?php the_title(); ?></h1>

    <h2 class='date published'><?php echo get_the_date(); ?></h2>

    <div class="entry-content">
      <?php the_content(); ?>
    </div>
  </article>

<comments>
   <script src='//giscus.app/client.js' data-repo='insign/helio.me-comments' data-repo-id='R_kgDOOaRcaQ' data-category='Announcements' data-category-id='DIC_kwDOOaRcac4CpIkq' data-mapping='pathname' data-strict='0' data-reactions-enabled='1' data-emit-metadata='1' data-input-position='top' data-theme='preferred_color_scheme' data-lang='pt' data-loading='lazy' crossorigin='anonymous' async="async"></script>
</comments>

<?php get_footer(); ?>
