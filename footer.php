</div> <!-- .content -->
<footer>
  <?php
  if (has_nav_menu('footer')) {
    wp_nav_menu([
                  'theme_location' => 'footer',
                  'menu_class'     => 'footer-menu',
                  'container'      => false,
                ]);
  }
  ?>
  <p>&copy; <?= date('Y'); ?> <?php bloginfo('name'); ?></p>
</footer>
<?php wp_footer(); ?>
</body>
</html>
