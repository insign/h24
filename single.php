<?php get_header(); ?>

  <article id="item-<?php the_ID(); ?>" <?php post_class(); ?>>
    <h1 class='entry-title'><?php the_title(); ?></h1>

    <h2 class='date published'><?php echo get_the_date(); ?></h2>

    <div class="entry-content">
      <?php the_content(); ?>
    </div>
  </article>

<div class="giscus"></div>
<script>
  (function() {
    const theme = localStorage.getItem('theme') || 'system';
    let visualTheme = theme;
    if (theme === 'system') {
      const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      visualTheme = prefersDark ? 'dark' : 'light';
    }

    const script = document.createElement('script');
    script.src = 'https://giscus.app/client.js';
    script.setAttribute('data-repo', 'insign/helio.me-comments');
    script.setAttribute('data-repo-id', 'R_kgDOOaRcaQ');
    script.setAttribute('data-category', 'Announcements');
    script.setAttribute('data-category-id', 'DIC_kwDOOaRcac4CpIkq');
    script.setAttribute('data-mapping', 'pathname');
    script.setAttribute('data-strict', '0');
    script.setAttribute('data-reactions-enabled', '1');
    script.setAttribute('data-emit-metadata', '1');
    script.setAttribute('data-input-position', 'top');
    script.setAttribute('data-theme', visualTheme);
    script.setAttribute('data-lang', 'pt');
    script.setAttribute('data-loading', 'lazy');
    script.setAttribute('crossorigin', 'anonymous');
    script.async = true;

    document.querySelector('.giscus').appendChild(script);
  })();
</script>

<?php get_footer(); ?>
